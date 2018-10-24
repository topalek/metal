<?php


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title                   = 'Создать товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-8">
    <div class="product-create box">

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>

    </div>
</div>

