<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title                   = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view box">
    <div class="box-body">
        <p>
			<?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
				'title',
				'price',
				'slug',
				'image',
				'status',
				'updated_at',
				'created_at',
			],
		]) ?>
        <br>
        <p>
			<?= Html::a('Создать новый', ['create'], ['class' => 'btn btn-success']) ?>

        </p>
    </div>


</div>
