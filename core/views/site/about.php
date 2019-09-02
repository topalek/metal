<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Operation;

$date     = '2019-04-02 00:00:00';
$date     = date('Y-m-d 00:00:00');
$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-5 day"));
$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
$operations = Operation::getOperationByPeriod($fromDate, $toDate);
$operations = Operation::getArrayForReport($operations);
$headings = Operation::getHeadings($operations);
?>
<div class="site-about" style="margin-top: 60px">
    <pre>
    </pre>
    <img class="image img-responsive" src="https://drive.google.com/open?id=1rq3uAH8vQOdS_8IpD6cDNhJbdLhm7eTH" alt="">
    <pre>
<?php print_r($headings) ?>
    </pre>
</div>
