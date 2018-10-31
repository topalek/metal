<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */

$this->title                   = 'Обновить операцию: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operations', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="operation-update box">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
