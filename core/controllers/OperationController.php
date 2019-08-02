<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
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
            'client'       => 1,
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

            foreach ($emptyArr as $i => $item) {
                $id = ArrayHelper::getValue($item, 'id');
                if (array_key_exists($id, $products)) {
                    $arr[$id] = $emptyArr[$i];
                    $weight = ArrayHelper::getValue($products[$id], 'weight');
                    $sale_price = ArrayHelper::getValue($products[$id], 'sale_price');
                    $total = ArrayHelper::getValue($products[$id], 'total');
                    $dirt = ArrayHelper::getValue($products[$id], 'dirt');

                    $arr[$id]['weight'] = $weight ? $weight : '?';
                    $arr[$id]['sale_price'] = $sale_price ? $sale_price : '?';
                    $arr[$id]['total'] = $total ? $total : '?';
                    $arr[$id]['id'] = $id ? $id : '?';
                    $arr[$id]['dirt'] = $dirt ? $dirt : '?';
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
        if ($model->load(Yii::$app->request->post())){
            $model->comment = Operation::REST_CASH_COMMENT . $model->comment;
            if ($model->save()){
                return $this->goHome();
            }
        }

        return $this->render('rest-cash', [
            'model' => $model,
        ]);
    }

    public function actionGetItem($id, $type = null, $client = 0)
    {
        $model = Product::findOne($id);

        return $this->renderAjax('_item_form', ['model' => $model, 'type' => $type, "client" => $client]);
    }

    public function actionGetMoveModal($client = 0)
    {
        $id = 1;
        $model = Product::findOne($id);
        return $this->renderAjax('_move_to_business_form', ['model' => $model, "client" => $client]);
    }

    public function actionFillCash()
    {
        $this->bodyClass = 'cash';
        $model           = new Operation();
        $model->type     = Operation::TYPE_FILL_CASH;
        $model->products = Product::getEmptyArray();

        if ($model->load(Yii::$app->request->post())){
            $model->comment = Operation::FILL_CASH_COMMENT . $model->comment;
            if ($model->save()){
                return $this->goHome();
            }
        }

        return $this->render('fill-cash', [
            'model' => $model,
        ]);
    }

    public function actionGetField($id, $data = null)
    {
        if ($data) {
            try {
                $data = Json::decode($data);
            } catch (\Exception $e) {
                $data = [];
            }
        }

        $product = Product::findOne($id);
        return $this->renderAjax('_field', ['model' => $product, 'data' => $data]);
    }

    public function actionHistory(){
        $this->bodyClass = 'history';
        $date            = date('Y-m-d 00:00:00');
        $fromDate        = date('Y-m-d 00:00:00', strtotime($date . "-1 day"));
        $toDate          = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
        $query           = Operation::find()
                                    ->where(['>=', 'created_at', $fromDate])
                                    ->andWhere(['<=', 'created_at', $toDate])
                                    ->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSellItem($id)
    {
        $model = Product::findOne($id);

        return $this->renderAjax('_sell_form', ['model' => $model]);
    }

    public function actionMoveBusiness()
    {
        $post = Yii::$app->request->post();
        $data = $post['data'];
        $data = explode("&", $data);
        $metal = [];
        $bMetal = [
//            'sale_price' => 0,
            'dirt'  => 0,
            'total' => 0,
            'title' => 'Деловой металл',
            'id'    => 25,
        ];
        foreach ($data as $item) {
            list($key, $value) = explode("=", $item);
            $metal[$key] = $value;
        }
        $bMetal = array_merge($metal, $bMetal);
        $weight = ArrayHelper::getValue($metal, 'weight');
        $total = ArrayHelper::getValue($metal, 'total');
        $metal['weight'] = -$weight;
        $metal['total'] = -$total;
        $model = new Operation();
        $model->type = Operation::TYPE_BUY;
        $model->products = [$metal, $bMetal];

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->save()) {
            $response = ['status' => true, 'message' => "OK"];
        } else {
            $response = ['status' => false, 'message' => $model->errors];
        }

        return $response;
    }
}
