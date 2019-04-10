<?php

use app\modules\admin\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="operation-sell">
    <?php $form = ActiveForm::begin() ?>
    <div class="check">
        <?php foreach (Product::getList() as $id => $title) : ?>
            <?= Html::checkbox('products[]', null, ['id' => 'p' . $id, 'value' => $id]) ?>
            <?= Html::label($title, 'p' . $id, ['class' => 'btn-label']) ?>
        <?php endforeach; ?>
    </div>

    <?= $form->field($model, 'comment')->textarea() ?>
    <div class="form-group">
        <div class="col-lg-12">
            <?= Html::submitButton('Сдать', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
