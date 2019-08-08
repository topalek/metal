<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Product;

$date     = '2019-04-02 00:00:00';
$date     = date('Y-m-d 00:00:00');
$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-1 day"));
$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
$id = 25;
$model    = Product::findOne($id);

$headers = Product::getCachePrice();
?>
<div class="site-about" style="margin-top: 60px">
    <pre>
        <?php print_r($model->origin); ?>
    </pre>

    <pre>
<?php print_r($headers) ?>
    </pre>
</div>
