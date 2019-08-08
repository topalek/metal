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
<div class="sell">
    <?php $form = ActiveForm::begin() ?>
    <div class="check">
        <?php foreach (Product::getCachePrice() as $id => $prod) : ?>

            <?= Html::checkbox('marks[]', null, [
                'id'       => 'p' . $id,
                'value'    => $id,
                'class'    => 'mark',
                'data-url' => (!$prod['use_form']) ?
                    Url::to(['/operation/get-field', 'id' => $id]) :
                    Url::to(['/operation/sell-item-form', 'id' => $id]),
            ]) ?>
            <?= Html::label($prod['title'], 'p' . $id, ['class' => 'btn-label']) ?>

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
let marks = [];
$('.mark').on('change',(e)=>{
    let  el = $(e.target);
    if (marks.includes(el.val())){
        marks.splice(marks.indexOf(el.val()), 1);
        $('#product-'+el.val()).parents('.row').remove();
    } else{
        marks.push(el.val());
        $.get(el.data('url'),(resp)=>{
            $('.list').append(resp);
        });
    }
    
});
JS
)
?>
