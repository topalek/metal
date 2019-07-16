<?php
/**
 * Created by topalek
 * Date: 10.06.2019
 * Time: 14:53
 *
 * @var $model Product
 * @var $data  array
 */

use app\modules\admin\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$fields = [
    'weight',
    'sale_price',
    'price',
    'dirt',
    'total',
    'title',
    'id',
];
$weight = ArrayHelper::getValue($data, 'weight');
$id = $model->id;
$htmlId = "product-" . $id;
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <?= Html::label($model->title, $htmlId); ?>
            <?= Html::input('number', 'products[' . $model->id . '][weight]', $weight, [
                'class' => 'form-control col-md-2', 'id' => $htmlId,
            ]); ?>
            <?= Html::hiddenInput('products[' . $model->id . '][id]', $model->id) ?>
            <?php if ($data) {
                foreach ($data as $key => $value) {
                    if (in_array($key, $fields)) {
                        echo Html::hiddenInput('products[' . $model->id . '][' . $key . ']', $value);
                    }
                }
            }
            ?>
        </div>
    </div>
</div>