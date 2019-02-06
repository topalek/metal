<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $type integer */
?>
<?php Modal::begin([
	'id'            => 'item-modal',
	'headerOptions' => [
		'data-type' => $type
	],
	'header'        => '<h3 class="modal-title">' . $model->title . '</h3>',
	'size'          => Modal::SIZE_DEFAULT,
	'footer'        => '<div class="col-md-12">
                            <div class="form-group">' . Html::button('Добавить товар', [
			'class' => 'btn btn-primary add-item',
			'data'  => [
				'dismiss' => "modal",
				'type'    => $type
			],
		]) . Html::button('Провести', [
			'class' => 'btn btn-danger process',
			'data'  => [
				'url'  => Url::to(['operation/create', 'type' => $type]),
				'type' => $type
			],
		]) . '
                            </div>
                        </div>'
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
var type = $('#item-modal').data('type');
buildItemList(type);
$('.process').on('click',(e)=>{
    let json = $('form').serializeArray();
    let type = $(e.target).data('type');
    writeToStorage(json,type);
    let name;
    if (type == 0){
        name = 'buy';
    } else {
        name = 'sale';
    }
    var products = getFromStorage(name);
    let url = $(e.target).data('url');
        localStorage.removeItem(name);

    $.post(url,{'products':products},(resp)=>{});
});
$('.add-item').on('click',(e)=>{
    let json = $('form').serializeArray();
    let type = $(e.target).data('type');
    writeToStorage(json,type);
});

$('.remove-item').on('click',(e)=>{
    let el = $(e.target);
    let id = el.data('id');
    el.parents('li').remove();
    removeItem(id);
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
    var products = getFromStorage();
    if (products){
        $('.operation').removeClass('hidden');
        $('.operation').on('click',(e)=>{
            e.preventDefault();
            let url = $(e.target).attr('href');
            localStorage.removeItem('products');
            $.post(url,{'products':products},(resp)=>{
            });
        });
    } 
  $('.modals').remove();
  
});
JS
);
