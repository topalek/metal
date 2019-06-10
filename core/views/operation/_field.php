<?php
/**
 * Created by topalek
 * Date: 10.06.2019
 * Time: 14:53
 *
 * @var $model Product
 */

use app\modules\admin\models\Product;
use yii\helpers\Html;

$id = $model->id;
$htmlId = "product-" . $id;
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <?= Html::label($model->title, $htmlId); ?>
            <?= Html::input('number', 'products[' . $model->id . ']', null, [
                'class' => 'form-control col-md-2', 'id' => $htmlId,
            ]); ?>
        </div>
    </div>
</div>