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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <?= Html::label('Вес', 'weight') ?>
                        <?= Html::input('text', 'weight', 0, ['class' => 'form-control weight']) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <?= Html::label('Цена', 'sale_price') ?>
                        <?= Html::input('text', 'sale_price', $model->price, ['class' => 'form-control price']) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <?= Html::label('Засор %', 'dirt') ?>
                        <?= Html::input('text', 'dirt', 0, ['class' => 'form-control dirt']) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <?= Html::label('Стоимость', 'total') ?>
                        <?= Html::input('text', 'total', $model->price, ['class' => 'form-control total']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::a('Добавить товар', [
                                'operation/add-item', 'id' => $model->id, 'sale_price' => $model->price,
                            ], ['class' => 'btn btn-primary', 'data-dismiss' => "modal"]) ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

<?php Modal::end();

$this->registerJs(<<<JS

$('input').on('input',(e)=>{
    let el = $(e.target),
    price = $('.price').val(),
    weight = $('.weight').val(),
    dirt = $('.dirt').val(),
    total = $('.total');
    
   el.val(el.val().replace(',','.'));
   let totalPrice = Math.round((price*(weight - weight*dirt/100))*100)/100;
   total.val(totalPrice);
});
var modal = $('#item-modal');
$(modal).modal('show');

$(modal).on('hidden.bs.modal', function (e) {
  $('.modals').remove();
});
JS
);
