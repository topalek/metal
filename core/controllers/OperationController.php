<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

/**
 * OperationController implements the CRUD actions for Operation model.
 */
class OperationController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors(){
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBuy(){
        $model       = new Operation();
        $model->type = Operation::TYPE_BUY;
        $searchModel = new ProductSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $priceList = $this->renderPartial('price_list', ['products' => Product::getCachePrice()]);
        if (Yii::$app->request->isPost){
            $post     = Yii::$app->request->post();
            $products = ArrayHelper::getValue($post, 'products');
            if ($products){
                $total = 0;
                foreach ($products as $id => $product){
                    if ( ! $product['weight']){
                        unset($products[$id]);
                        continue;
                    }
                    $total += $product['total'];
                }
                $model->sum      = $total;
                $model->products = $products;
                if ($model->save()){
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response                   = ['status' => true, 'message' => "OK"];
                }else{
                    $response = ['status' => false, 'message' => $model->errors];
                }

                return $response;
            }
        }

        return $this->render('create', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
            'priceList'    => $priceList,
        ]);

    }

    public function actionSell(){
        $model       = new Operation();
        $model->type = Operation::TYPE_SELL;
        $toRecord    = false;
        $list        = "";
        $priceList   = $this->renderPartial('price_list', ['products' => Product::getCachePrice()]);

        if (Yii::$app->request->isPost){

            $post     = Yii::$app->request->post();
            $products = ArrayHelper::getValue($post, 'products');
            $toRecord = ArrayHelper::getValue($post, 'toRecord');
            $comment  = ArrayHelper::getValue($post, 'Operation.comment');
            if ( ! $toRecord){
                if ( ! $products && $comment){
                    $model->products = Product::getEmptyArray();
                    $model->setComment($comment);
                    Yii::$app->session->setFlash('success', "Коментарий сохранен");

                    return $this->goHome();
                }
                if ($products){
                    $products = Product::find()->where(['id' => $products])->asArray()->all();
                    $list     = "";
                    foreach ($products as $product){
                        $id     = $product['id'];
                        $htmlId = "product-" . $id;
                        $list   .= '<div class="row"><div class="col-md-4">';
                        $list   .= '<div class="form-group">';
                        $list   .= Html::label($product['title'], $htmlId);
                        $list   .= Html::input('number', 'products[' . $product["id"] . ']', null, [
                            'class' => 'form-control col-md-2', 'id' => $htmlId
                        ]);
                        $list   .= '</div></div></div>';
                    }
                    $toRecord = true;
                    if ($comment){
                        $model->comment = $comment;
                    }

                    return $this->render('_check', ['model' => $model, 'toRecord' => $toRecord, 'list' => $list]);

                }
            }else{
                $emptyArr = Product::getEmptyArray();
                $arr      = [];
                foreach ($products as $id => $weight){
                    $arr[$id] = $emptyArr[$id];
                    if ($weight){
                        $arr[$id]['weight'] = $weight;
                    }else{
                        $arr[$id]['weight'] = "?";
                    }

                }
                $model->products = $arr;
                $model->comment  = $comment;
                if ($model->save()){
                    return $this->goHome();
                }
            }

        }

        return $this->render('_check', ['model' => $model, 'toRecord' => $toRecord, 'list' => $list]);
    }


    public function actionRestCash(){
        $model           = new Operation();
        $model->type     = Operation::TYPE_REST_CASH;
        $model->products = Product::getEmptyArray();
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->goHome();
        }

        return $this->render('rest-cash', [
            'model' => $model,
        ]);
    }

    public function actionGetItem($id, $type = null){
        $model = Product::findOne($id);

        return $this->renderAjax('_item_form', ['model' => $model, 'type' => $type]);
    }

    public function actionFillCash(){
        $model           = new Operation();
        $model->type     = Operation::TYPE_FILL_CASH;
        $model->products = Product::getEmptyArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('fill-cash', [
            'model' => $model,
        ]);
    }
}
