<?php

namespace app\controllers;

use app\modules\admin\models\Operation;
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
		$model       = new Operation();
		$model->type = $type;
		if ($model->load(Yii::$app->request->post()) && $model->save()){
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

}
