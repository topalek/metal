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
$attr = ['title' => $model->title, 'class' => 'btn btn-default flex-item'];
if ($model->image){
	$attr['title'] = $model->getImg();
	$attr['class'] = 'flex-item';
}
?>
<?= \yii\helpers\Html::a(
	$attr['title'], [
	'operation/get-item',
	'id'   => $model->id,
	'type' => $operation->type,
], [
	'class' => $attr['class'] . ' operation-item',
]) ?>

