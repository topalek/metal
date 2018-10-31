<?php


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */

$this->title                   = 'Создать Operation';
$this->params['breadcrumbs'][] = ['label' => 'Operations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-create box">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
