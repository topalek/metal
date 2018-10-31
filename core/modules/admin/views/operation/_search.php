<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OperationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'type') ?>

	<?= $form->field($model, 'sum') ?>

	<?= $form->field($model, 'status') ?>

	<?= $form->field($model, 'updated_at') ?>

	<?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
