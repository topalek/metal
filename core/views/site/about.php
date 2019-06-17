<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Operation;
use yii\helpers\Html;

$date     = '2019-04-02 00:00:00';
$date     = date('Y-m-d 00:00:00');
$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-1 day"));
$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
//print_r([$fromDate,$toDate]); die;
//        $operations = Operation::getOperationByPeriod($fromDate, $toDate);
$operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));

$headers = Operation::getHeadings($operations);

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

            //print_r($operations);
            ?>
        </pre>

    </div>
    <pre>
<?php //print_r($headers) ?>
    </pre>
</div>
