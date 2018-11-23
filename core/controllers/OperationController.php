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

	public function actionCreate($type){
		$model        = new Operation();
        $model->type = $type;
        $searchModel = new ProductSearch();
        if ($type == Operation::TYPE_BUY) {
            $searchModel->type = Operation::TYPE_BUY;
        } else {
            $searchModel->type = Operation::TYPE_SELL;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
                $model->products = $products;
                $model->sum = $total;
                if ($model->save()) {
                    return $this->redirect(Url::home());
                }
            }
        }

		return $this->render('create', [
			'model'        => $model,
			'dataProvider' => $dataProvider,
		]);
	}


	public function actionAddItem($id){

	}

    public function actionGetItem($id, $type = null)
    {
		$model = Product::findOne($id);

        return $this->renderAjax('_item_form', ['model' => $model, 'type' => $type]);
	}

}
