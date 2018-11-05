<?php

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;

/**
 * Created by topalek
 * Date: 31.10.2018
 * Time: 23:54
 */
/*  @var $this yii\web\View */
/* @var $model Product */
/* @var $operation Operation */

?>
<div class="col-md-2">
    <?= \yii\helpers\Html::a($model->title, [
        'operation/get-item',
        'id'   => $model->id,
        'type' => $operation->type,
    ], [
        'class' => 'btn btn-default operation-item',
    ]) ?>
</div>

