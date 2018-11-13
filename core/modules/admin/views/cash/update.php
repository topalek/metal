<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Cash */

$this->title                   = 'Обновить кассу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="cash-update box">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
