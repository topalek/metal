<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */

/* @var $form yii\widgets\ActiveForm */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
	'id'      => 'fill-cash-form',
	'options' => ['class' => 'form-horizontal'],
]) ?>
    <div class="col-md-6">
		<?= $form->field($model, 'sum')->input('number')->label('Внесите сумму') ?>

        <div class="form-group">
			<?= Html::submitButton('Внести', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?php ActiveForm::end() ?>