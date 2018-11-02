<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Modal::begin([
    'id'     => 'item-modal',
    'header' => '<h3 class="modal-title">' . $model->title . '</h3>',
    'size'   => Modal::SIZE_DEFAULT,
]); ?>

<div class="product-form box-body">

    <div class="col-md-12">
        <?= Html::input('text', 'sale_price', $model->price, ['class' => 'form-control']) ?>
    </div>
    <div class="col-md-12">
        <?= Html::input('text', 'sale_price', $model->price, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
		<?= Html::a('Add', [
			'operation/add-item', 'id' => $model->id, 'sale_price' => $model->price
		], ['class' => 'btn btn-primary']) ?>

    </div>


</div>

<?php Modal::end();

$this->registerJs(<<<JS
var modal = $('#item-modal');
$(modal).modal('show');

$(modal).on('hidden.bs.modal', function (e) {
  $('.modals').remove();
});
JS
);
