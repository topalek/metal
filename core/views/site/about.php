<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Product;

$date     = '2019-04-02 00:00:00';
$date     = date('Y-m-d 00:00:00');
$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-1 day"));
$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));


$headers = Product::getCachePrice();
?>
<div class="site-about" style="margin-top: 60px">
    <div class="col-md-12">
        <pre>
<!--            --><?php //print_r(Product::getEmptyArray()); ?>
        </pre>

    </div>
    <pre>
<?php print_r($headers) ?>
    </pre>
</div>
