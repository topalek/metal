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
        <?= $form->field($model, 'sum')->input('number', ['min' => 0])->label('Внесите сумму') ?>

        <div class="form-group">
            <?= Html::submitButton('Внести', ['class' => 'btn btn-primary', 'id' => 'fill-cash']) ?>
        </div>
    </div>

<?php ActiveForm::end() ?>

<?php $this->registerJs(<<<JS
$('#fill-cash').on('click',()=>{
    let cash = $('#operation-sum').val();
    if(!cash){
        alert('Заполните поле');
        return false;
    }else {
        if (parseInt(cash)<=0){
            alert('Сумма меньше или равна 0');
            return false;
        } 
    }
})

JS
) ?>
