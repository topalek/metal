<?php


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Cash */

$this->title                   = 'Создать Cash';
$this->params['breadcrumbs'][] = ['label' => 'Cashes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-create box">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
