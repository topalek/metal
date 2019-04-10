<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * OperationController implements the CRUD actions for Operation model.
 */
class OperationController extends Controller
{
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

    public function actionCreate($type)
    {
        $model = new Operation();
        $model->type = $type;
        $searchModel = new ProductSearch();
        if ($type == Operation::TYPE_BUY) {
            $searchModel->type = Operation::TYPE_BUY;
        } else {
            $searchModel->type = Operation::TYPE_SELL;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $priceList = $this->renderPartial('price_list', ['products' => Product::getCachePrice()]);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $products = ArrayHelper::getValue($post, 'products');
            $comment = ArrayHelper::getValue($post, 'Operation.comment');
            if ($products) {
                if ($type == Operation::TYPE_BUY) {
                    $total = 0;
                    foreach ($products as $id => $product) {
                        if (!$product['weight']) {
                            unset($products[$id]);
                            continue;
                        }
                        $total += $product['total'];
                    }
                    $model->sum = $total;
                } else {
                    $prod = [];
                    foreach ($products as $id) {
                        $prod[$id] = ['weight' => '?', 'sale_price' => '?', 'dirt' => '?', 'total' => '?', 'title' => Product::getTitle($id)];
                    }
                    $products = $prod;
                    $model->sum = 0;

                }
                $model->products = $products;
                $model->comment = $comment;

                if ($model->save()) {
                    return $this->redirect(Url::home());
                }
            }
        }

        if ($type == Operation::TYPE_BUY) {
            return $this->render('create', [
                'model'        => $model,
                'dataProvider' => $dataProvider,
                'priceList'    => $priceList,
            ]);
        }
        return $this->render('_check', ['model' => $model]);
    }


    public function actionAddItem($id)
    {

    }

    public function actionGetItem($id, $type = null)
    {
        $model = Product::findOne($id);

        return $this->renderAjax('_item_form', ['model' => $model, 'type' => $type]);
    }

    public function actionFillCash()
    {
        $model = new Operation();
        $model->type = Operation::TYPE_FILL_CASH;
        $model->comment = Operation::TYPE_FILL_CASH;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('fill-cash', [
            'model' => $model,
        ]);
    }
}
