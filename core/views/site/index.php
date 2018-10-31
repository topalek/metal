<?php

/* @var $this yii\web\View */

use app\modules\admin\models\Operation;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

	<?= Html::a(Html::img('web_assets/images/buy.svg'), [
		'operation/create', 'type' => Operation::OPERATION_BUY
	], [
		//'class' => 'btn btn-default'
	]) ?>
	<?= Html::a(Html::img('web_assets/images/sell.svg'), [
		'operation/create', 'type' => Operation::OPERATION_SELL
	], [
		//'class' => 'btn btn-default'
	]) ?>
</div>
