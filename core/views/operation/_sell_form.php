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
    'id'      => 'sell-modal',
    'options' => [],
    'header'  => '<h3 class="modal-title">' . $model->title . '</h3>',
    'size'    => Modal::SIZE_DEFAULT,
    'footer'  => '<div class="col-md-12">
                            <div class="form-group">'
        . Html::button('Продать', [
            'class' => 'btn btn-danger add-sell',
            'data'  => [
                'dismiss' => "modal",
                'url'     => Url::to(['operation/sell-item']),
            ],
        ])
        . '
                            </div>
                        </div>',
]); ?>
    <form>
        <div class="product-form box-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Вес', 'weight') ?>
                                <?= Html::input('number', 'weight', null, [
                                        'class' => 'form-control weight',
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Цена', 'sale_price') ?>
                                <?= Html::input('number', 'sale_price', $model->sale_price, ['class' => 'form-control price']) ?>
                                <?= Html::hiddenInput('discount', null, ['class' => "discount"]) ?>
                                <?= Html::hiddenInput('discount_price', null, ['class' => "discount_price"]) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Засор %', 'dirt') ?>
                                <?= Html::input('number', 'dirt', $model->dirt, ['class' => 'form-control dirt']) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Стоимость', 'total') ?>
                                <?= Html::input('number', 'total', 0, ['class' => 'form-control total']) ?>
                                <?= Html::hiddenInput('title', $model->title) ?>
                                <?= Html::hiddenInput('id', $model->id) ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-7">
                    <div class="item-list"></div>
                    <div id="total"></div>
                </div>
            </div>
        </div>
    </form>
<?php Modal::end();

$this->registerJs(<<<JS
try {
    modal = $('#sell-modal');
} catch(e) {
    let modal = $('#sell-modal');
}

$(modal).on('shown.bs.modal', function () {
    $('.weight').focus();
}); 
$(modal).on('hide.bs.modal', function () {
    modal.remove();
    delete(modal);
}); 
$(modal).modal('show');

var form = $('form');

$('.weight').on('input',(e)=>{
    let weight = parseFloat($(e.target).val()),
    price = parseFloat($('.price').val()),
    discount_price = parseFloat($('.weight').data('discount_price')),
    discount_weight = parseFloat($('.weight').data('discount_weight')),
    discount = $('.discount'),
    discountPrice = $('.discount_price'),
    _price = parseFloat($('.weight').data('price'));
    if (discount_price && discount_weight){
        if (weight>= discount_weight){
            price = discount_price;
            discount.val(1);
            discountPrice.val(price);
            $('.price').val(price);
            
        } else {
            price = _price;
            discount.val(null);
            discountPrice.val(null);
            $('.price').val(_price);
        }
    }
    calcTotal();
});
$('.price, .dirt').on('input',()=>{
    calcTotal();
});
function calcTotal(){
     let weight = parseFloat($('.weight').val()),
    price = parseFloat($('.price').val()),
    dirt = parseFloat($('.dirt').val()),
    total = $('.total');
     let totalPrice = Math.round((price*(weight - weight*dirt/100))*100)/100;
   total.val(totalPrice);
}

$('.add-sell').on('click',(e)=>{
    let url = $(e.target).data('url');
    let data = form.serializeArray();
    console.log(data);
    log('_sell_form',data);
    $.post(url,{data:data},(resp)=>{
// console.log(resp);return false;
        if (resp.status){
            // if (!clients){
                 window.location = "/";
            // } 
        } else {
            alert(resp.message);
        }
    });
    
});
JS
);
