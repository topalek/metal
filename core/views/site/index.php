<?php

/* @var $this yii\web\View */

use app\modules\admin\models\Operation;

$this->title = 'My Yii Application';
?>
<div class="site-index">

	<?= \yii\helpers\Html::a('<i class="fa fa-cart-arrow-down"></i>', [
		'operation/create', 'type' => Operation::OPERATION_BUY
	], [
		'class' => 'btn btn-default'
	]) ?>
	<?= \yii\helpers\Html::a('<i class="fa fa-shopping-cart"></i>', [
		'operation/create', 'type' => Operation::OPERATION_SELL
	], [
		'class' => 'btn btn-default'
	]) ?>
</div>
