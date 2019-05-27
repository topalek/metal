<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="site-about">
    <h1>Тест полей</h1>
    <div class="col-md-3">
        <div class="form-group">
            <?= Html::input('text', '', '', ['placeholder' => 'text', 'class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::input('number', '', '', ['placeholder' => 'number', 'class' => 'form-control']) ?>
        </div>
        <pre>
            <?php

            ?>
        </pre>

    </div>
    <pre>
        <?php
        $date = date('Y-m-d 00:00:00');
        $fromDate = date('Y-m-d 00:00:00', strtotime($date));
        $toDate = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
        //        $operations = Operation::getOperationByPeriod($fromDate, $toDate);
        $operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));
        $list = Product::getList();
        foreach ($operations as $k => $operation) {
            $products = ArrayHelper::getValue($operation, 'products');

            foreach ($products as $id => $product) {
                $discount = ArrayHelper::getValue($product, 'discount');
                $temp = [];
                if ($discount) {
                    $temp['title'] = $list[$id];
                    $temp['count'] = 4;
                } else {
                    $temp['title'] = $list[$id];
                    $temp['count'] = 3;
                }
                $list[$id] = $temp;
            }
        }
        print_r($list);
        ?>
    </pre>

</div>
