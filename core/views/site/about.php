<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\admin\models\Operation;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$date     = date('Y-m-d 00:00:00');
$fromDate = date('Y-m-d 00:00:00', strtotime($date . "-1 day"));
$toDate   = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
//        $operations = Operation::getOperationByPeriod($fromDate, $toDate);
$operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));

$subHeaders = [
    4 => ['mass', 'price', 'disc', 'total'],
    3 => ['mass', 'price', 'total'],
];
$headers    = Operation::getHeadings($operations);


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
            $col = "A";
            print_r($col);
            print_r($col++);
            print_r($col);
            print_r(++$col);
            print_r($col);
            ?>
        </pre>

    </div>
    <pre>
        <table>
            <tr>
                <td></td>
            </tr></table>
        <?php

        $html = "<table class='table table-bordered table-responsive'><tr>";
        foreach ($headers as $header){
            $html .= "<td colspan='{$header["count"]}'>" . $header['title'] . "</td>";
        }
        $html .= "</tr>";
        foreach ($headers as $header){
            $count = ArrayHelper::getValue($header, "count");
            foreach ($subHeaders[$count] as $subHeader){
                $html .= "<td>" . $subHeader . "</td>";
            }
        }
        $html .= "</tr></table>";
        ?>
    </pre>
    <?= $html ?>
</div>
