<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title                   = 'Обновить товар: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить: ' . $model->title;
?>
<div class="col-lg-8">
    <div class="product-update box">
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
    </div>
</div>

