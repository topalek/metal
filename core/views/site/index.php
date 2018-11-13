<?php

/* @var $this yii\web\View */

use app\modules\admin\models\Operation;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="flex flex-center">
                <?= Html::a(Html::img('/web_assets/images/buy.svg', ['class' => 'img-responsive']), [
                    'operation/create', 'type' => Operation::TYPE_BUY,
                ], [
                    //'class' => 'btn btn-default'
                ]) ?>
                <?= Html::a(Html::img('/web_assets/images/sell.svg', ['class' => 'img-responsive']), [
                    'operation/create', 'type' => Operation::TYPE_SELL,
                ], [
                    //'class' => 'btn btn-default'
                ]) ?>
            </div>
        </div>
    </div>


</div>
