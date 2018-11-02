<?php

use app\modules\admin\models\Product;

/**
 * Created by topalek
 * Date: 31.10.2018
 * Time: 23:54
 */
/*  @var $this yii\web\View */
/* @var $model Product */

?>

<?= \yii\helpers\Html::a($model->title, [
    'operation/get-item',
    'id' => $model->id,
], [
    'class' => 'btn btn-default operation-item',
]) ?>
