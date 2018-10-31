<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */

$this->title                   = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-view box">
    <div class="box-body">
        <p>
			<?= Html::a('Обновить', ['update', 'id' => $model->id], [
				'class'
				=> 'btn btn-primary'
			]) ?>
			<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data'  => [
					'confirm' => 'Вы уверены что хотите удалить?',
					'method'  => 'post',
				],
			]) ?>
        </p>

		<?= DetailView::widget([
			'model'      => $model,
			'attributes' => [
				'id',
				'type',
				'sum',
				'status',
				'updated_at',
				'created_at',
			],
		]) ?>
    </div>


</div>
