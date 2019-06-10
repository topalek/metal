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
                'url'  => Url::to(['operation/buy']),
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
var modal = $('#item-modal');
var type = modal.data('type');

$(modal).modal('show');
$(modal).on('hidden.bs.modal', function (e) {
    var products = getFromStorage();
    if (products){
        $('.operation').removeClass('hidden');
        $('.operation').on('click',(e)=>{
            e.preventDefault();
            let url = $(e.target).attr('href');
            localStorage.removeItem(storageName);
            $.post(url,{'products':products},(resp)=>{
            });
        });
    } 
    isCalculated = false;
  $('.modals').remove();
  
});
var form = $('form');

buildItemList();
$('.process').on('click',(e)=>{
    let json = form.serializeArray(),
        weight = $('.weight').val(),
        products = getFromStorage(),
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
        if (weight){
            writeToStorage(json);
        } 
        if (isCalculated){
            products = getFromStorage();
            $.post(url,{'products':products},(resp)=>{
                
                if (resp.status){
                    localStorage.removeItem(storageName);
                    window.location = "/";
                } else {
                    alert(resp.message);
                }
            });
        } else {
            alert('Просчитайте товары');
        }
        
    }
   
});
$('.add-item').on('click',(e)=>{
    let json = $('form').serializeArray(),
        weight = $('.weight').val();
     if(!weight){
        alert('Заполните Вес');
        return false;
    }else{
         weight = parseFloat(weight);
        if (weight == 0){
            alert('Вес 0');
            return false;
        }
        writeToStorage(json);
    }
   
});

$('.remove-item').on('click',(e)=>{
    let el = $(e.target);
    let id = el.data('id');
    el.parents('li').remove();
    removeItem(id);
});

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
$('#calculate').on('click',()=>{
    let json = form.serializeArray(),
        weight = $('.weight').val();
    let total = 0;
    isCalculated = true;
    if (weight){
        writeToStorage(json);
        buildItemList();
        form[0].reset();
    } 
    let products = getFromStorage(); 
    Object.keys(products).forEach((i)=>{
        total+= parseFloat(products[i].total);
    });
    total = Math.ceil(total*100)/100;
    $('#total').html("Всего: "+total+" грн." );
});
JS
);
