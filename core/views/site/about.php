<?php

/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

//$date     = '2019-04-02 00:00:00';
//$date     = date('Y-m-d 00:00:00');
//$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-5 day"));
//$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
//$operations = Operation::getOperationByPeriod($fromDate, $toDate);
//$operations = Operation::getArrayForReport($operations);
//$headings = Operation::getHeadings($operations);
?>
<div class="site-about" style="margin-top: 60px">
    <pre>
    </pre>
    <img class="image img-responsive" src="https://lorempixel.com/600/300" alt="">
    <pre>
<?php echo Html::a('run command', ['/admin/report/run-command']) ?>
<?php print_r(Yii::$app->user->getId() . "\n") ?>
<?php print_r(Yii::$app->user->id . "\n") ?>
    </pre>
</div>
