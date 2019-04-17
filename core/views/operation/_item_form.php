<?php

use app\modules\admin\models\Operation;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $type integer */

$data = ($type == Operation::TYPE_BUY) ? [
    'discount_weight' => $model->amount_for_discount,
    'discount_price'  => $model->discount_price,
    'price'           => $model->price
] : ['price' => $model->price];
?>
<?php Modal::begin([
    'id'      => 'item-modal',
    'options' => [
        'data-type' => $type
    ],
    'header'  => '<h3 class="modal-title">' . $model->title . '</h3>',
    'size'    => Modal::SIZE_DEFAULT,
    'footer'  => '<div class="col-md-12">
                            <div class="form-group">' . Html::button('Просчитать', [
            'class' => 'btn btn-success', 'id' => 'calculate'
        ]) . Html::button('Добавить товар', [
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
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Вес', 'weight') ?>
                                <?= Html::input('number', 'weight', null, [
                                        'class' => 'form-control weight',
                                        'data'  => $data
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::label('Цена', 'sale_price') ?>
                                <?= Html::input('number', 'sale_price', $model->price, ['class' => 'form-control price']) ?>
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
var modal = $('#item-modal');
var type = modal.data('type');
$(modal).modal('show');
$(modal).on('hidden.bs.modal', function (e) {
    var products = getFromStorage(type);
    if (products){
        $('.operation').removeClass('hidden');
        $('.operation').on('click',(e)=>{
            e.preventDefault();
            let url = $(e.target).attr('href');
            localStorage.removeItem(getStorageName(type));
            $.post(url,{'products':products},(resp)=>{
            });
        });
    } 
  $('.modals').remove();
  
});
var form = $('form');

buildItemList(type);
$('.process').on('click',(e)=>{
    let json = form.serializeArray(),
        type = $(e.target).data('type'),
        weight = $('.weight').val(),
        products = getFromStorage(type),
        url = $(e.target).data('url');
    
    if(!weight && isEmpty(products)){
        alert('Заполните Вес');
        return false;
    }else{
         weight = parseFloat(weight);
        if (weight == 0){
            alert('Вес 0');
            return false;
        }
        writeToStorage(json,type);
        localStorage.removeItem(getStorageName(type));
    
        $.post(url,{'products':products},(resp)=>{});
    }
   
});
$('.add-item').on('click',(e)=>{
    let json = $('form').serializeArray(),
        type = $(e.target).data('type'),
        weight = $('.weight').val(),
        products = getFromStorage(type);
    
     if(!weight && isEmpty(products)){
        alert('Заполните Вес');
        return false;
    }else{
         weight = parseFloat(weight);
        if (weight == 0){
            alert('Вес 0');
            return false;
        }
        writeToStorage(json,type);
    }
    
});

$('.remove-item').on('click',(e)=>{
    let el = $(e.target);
    let id = el.data('id');
    el.parents('li').remove();
    removeItem(id,type);
});
$('input').on('input',(e)=>{
    let el = $(e.target),
    price = parseFloat($('.price').val()),
    weight = parseFloat($('.weight').val()),
    discount_price = parseFloat($('.weight').data('discount_price')),
    discount_weight = parseFloat($('.weight').data('discount_weight')),
    _price = parseFloat($('.weight').data('price')),
    dirt = parseFloat($('.dirt').val()),
    total = $('.total');
    if (discount_price && discount_weight){
        if (weight>= discount_weight){
            price = discount_price;
            $('.price').val(price);
            
        } else {
            price = _price;
            $('.price').val(_price);
        }
    }
   // el.val(el.val().replace(',','.'));
   let totalPrice = Math.round((price*(weight - weight*dirt/100))*100)/100;
   total.val(totalPrice);
});
$('#calculate').on('click',()=>{
    let json = form.serializeArray(),
        weight = $('.weight').val();
    let total = 0;
    if (weight){
        writeToStorage(json,type);
        buildItemList(type);
        form[0].reset();
    } 
    let products = getFromStorage(type); 
    Object.keys(products).forEach((i)=>{
        total+= parseFloat(products[i].total);
    });
    // let formTotal = $('.total').val();
    // total += parseFloat(formTotal);
    total = Math.ceil(total*100)/100;
    $('#total').html("Всего: "+total+" грн." );
});
JS
);
