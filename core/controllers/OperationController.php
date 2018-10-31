<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use app\modules\admin\models\ProductSearch;
use Yii;
use yii\filters\VerbFilter;
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
		$searchModel  = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$model->type = $type;
		if ($model->load(Yii::$app->request->post()) && $model->save()){
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model'        => $model,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	public function actionAddItem($id){

	}

	public function actionGetItem($id){
		$model = Product::findOne($id);

		return $this->renderAjax('_item_form', ['model' => $model]);
	}

}
