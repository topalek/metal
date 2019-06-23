<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * OperationController implements the CRUD actions for Operation model.
 */
class OperationController extends Controller
{
    public $bodyClass;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBuy()
    {
        $model           = new Operation();
        $model->type     = Operation::TYPE_BUY;
        $searchModel     = new ProductSearch();
        $this->bodyClass = 'operation-buy';
        $dataProvider    = $searchModel->search(Yii::$app->request->queryParams);

        $priceList = $this->renderPartial('price_list', ['products' => Product::getCachePrice()]);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $products = ArrayHelper::getValue($post, 'products');
            if ($products) {
                $total = 0;
                foreach ($products as $id => $product) {
                    if (!$product['weight']) {
                        unset($products[$id]);
                        continue;
                    }
                    $total += $product['total'];
                }
                $model->sum = $total;
                $model->products = $products;
                if ($model->save()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = ['status' => true, 'message' => "OK"];
                } else {
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

    public function actionSell()
    {
        $model           = new Operation();
        $model->type     = Operation::TYPE_SELL;
        $this->bodyClass = 'operation-sell';

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();
            $products = ArrayHelper::getValue($post, 'products');
            $comment = ArrayHelper::getValue($post, 'Operation.comment');
            if (!$products && $comment) {
                $model->products = Product::getEmptyArray();
                $model->setComment($comment);
                Yii::$app->session->setFlash('success', "Коментарий сохранен");

                return $this->goHome();
            }
            $emptyArr = Product::getEmptyArray();
            $arr = [];
            foreach ($products as $id => $weight) {
                $arr[$id] = $emptyArr[$id];
                if ($weight) {
                    $arr[$id]['weight']     = $weight;
                    $arr[$id]['sale_price'] = '?';
                } else {
                    $arr[$id]['weight']     = "?";
                    $arr[$id]['sale_price'] = "?";
                }

            }
            $model->products = $arr;
            $model->comment = $comment;
            if ($model->save()) {
                return $this->goHome();
            }
        }


        return $this->render('_check', ['model' => $model]);
    }


    public function actionRestCash()
    {
        $this->bodyClass = 'cash';
        $model           = new Operation();
        $model->type     = Operation::TYPE_REST_CASH;
        $model->products = Product::getEmptyArray();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('rest-cash', [
            'model' => $model,
        ]);
    }

    public function actionGetItem($id, $type = null)
    {
        $model = Product::findOne($id);

        return $this->renderAjax('_item_form', ['model' => $model, 'type' => $type]);
    }

    public function actionFillCash()
    {
        $this->bodyClass = 'cash';
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

    public function actionGetField($id)
    {
        $product = Product::findOne($id);
        return $this->renderAjax('_field', ['model' => $product]);
    }
}
