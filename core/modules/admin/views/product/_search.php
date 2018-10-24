<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'price') ?>

	<?= $form->field($model, 'slug') ?>

	<?= $form->field($model, 'image') ?>

	<?php // echo $form->field($model, 'status') ?>

	<?php // echo $form->field($model, 'updated_at') ?>

	<?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
