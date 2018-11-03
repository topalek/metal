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
    <form>
        <div class="product-form box-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
	                            <?= Html::label('Вес', 'weight') ?>
	                            <?= Html::input('text', 'weight', 0, ['class' => 'form-control weight']) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
	                            <?= Html::label('Цена', 'sale_price') ?>
	                            <?= Html::input('text', 'sale_price', $model->price, ['class' => 'form-control price']) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
	                            <?= Html::label('Засор %', 'dirt') ?>
	                            <?= Html::input('text', 'dirt', 0, ['class' => 'form-control dirt']) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
	                            <?= Html::label('Стоимость', 'total') ?>
	                            <?= Html::input('text', 'total', $model->price, ['class' => 'form-control total']) ?>
	                            <?= Html::hiddenInput('title', $model->title) ?>
	                            <?= Html::hiddenInput('id', $model->id) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
	                            <?= Html::button('Добавить товар', [
		                            'class'        => 'btn btn-primary add-item',
		                            'data-dismiss' => "modal"
	                            ]) ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-list"></div>
                </div>
            </div>
        </div>
    </form>
<?php Modal::end();

$this->registerJs(<<<JS

buildItemList();
    var product = {};
        var products = (localStorage.getItem('products')) ? JSON.parse(localStorage.getItem('products')) : [];

$('.add-item').on('click',()=>{
    let json = $('form').serializeArray();
    writeToStorage(json);
});
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
