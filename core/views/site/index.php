<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Metal';
?>
<div class="site-index">
    <!--    <div class="row">-->
    <!--        <div class="col-md-8 col-md-offset-2">-->
    <div class="flex flex-center flex-column">
        <?= Html::a('купить', [
                    'operation/buy'
                ], [
            'class' => 'operation-btn'
                ]) ?>
        <?= Html::a("сдать&nbsp;&nbsp;", [
                    'operation/sell'
                ], [
            'class' => 'operation-btn'
                ]) ?>
            </div>
    <!--        </div>-->
    <!--    </div>-->


</div>
