<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Modal::begin() ?>
<div class="product-form box-body">
    <h3><?= $model->title ?></h3>
    <div class="col-md-6">

		<?= Html::input('text', 'sale_price', $model->price) ?>
    </div>

    <div class="form-group">
		<?= Html::a('Add', [
			'operation/add-item', 'id' => $model->id, 'sale_price' => $model->price
		], ['class' => 'btn btn-primary']) ?>

    </div>


</div>
<?php Modal::end() ?>
