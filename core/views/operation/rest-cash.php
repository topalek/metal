<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */

/* @var $form yii\widgets\ActiveForm */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id'      => 'rest-cash-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<div class="col-md-6">
    <?= $form->field($model, 'sum')->input('number', ['min' => 0])->label('Внесите сумму') ?>
    <?= $form->field($model, 'comment')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Внести', ['class' => 'btn btn-primary', 'id' => 'rest-cash']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>

<?php $this->registerJs(<<<JS


JS
) ?>
