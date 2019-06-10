<?php

use app\modules\admin\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */
/* @var $form yii\widgets\ActiveForm */
/* @var $toRecord boolean */
/* @var $list string */

?>
<div class="operation-sell">
    <?php $form = ActiveForm::begin() ?>
    <div class="check">
        <?php foreach (Product::getList() as $id => $title) : ?>
            <?= Html::checkbox('marks[]', null, ['id' => 'p' . $id, 'value' => $id, 'class' => 'mark', 'data-url' => Url::to(['/operation/get-field', 'id' => $id])]) ?>
            <?= Html::label($title, 'p' . $id, ['class' => 'btn-label']) ?>
        <?php endforeach; ?>
    </div>
        <div class="list">
        </div>
    <?= $form->field($model, 'comment')->textarea() ?>
    <div class="form-group">
        <div class="col-lg-12">
            <?= Html::submitButton('Сдать', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$this->registerJs(<<<JS
$('.mark').on('change',(e)=>{
    let  el = $(e.target);
    $.get(el.data('url'),(resp)=>{
        $('.list').append(resp);
    });
});
JS
)
?>
