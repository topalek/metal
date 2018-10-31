<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-form box-body">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'type')->textInput() ?>

	<?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'status')->textInput() ?>

	<?= $form->field($model, 'updated_at')->textInput() ?>

	<?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
		?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
