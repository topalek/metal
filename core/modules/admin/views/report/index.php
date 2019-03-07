<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii2tech\spreadsheet\Spreadsheet;

/* @var $this yii\web\View */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index box">

    <?php
    $data = [[
        'column1' => '1.1',
        'column2' => '1.2',
        'column3' => '1.3',
        'column4' => '1.4',
        'column5' => '1.5',
        'column6' => '1.6',
        'column7' => '1.7',
    ], [
        'column1' => '2.1',
        'column2' => '2.2',
        'column3' => '2.3',
        'column4' => '2.4',
        'column5' => '2.5',
        'column6' => '2.6',
        'column7' => '2.7',
    ],
    ];
    $exporter = new Spreadsheet([
        'dataProvider'       => new ArrayDataProvider([
            'allModels' => $data,
        ]),
        'headerColumnUnions' => [
            [
                'header' => 'Skip 1 column and group 2 next',
                'offset' => 1,
                'length' => 2,
            ],
            [
                'header' => 'Skip 2 columns and group 2 next',
                'offset' => 2,
                'length' => 2,
            ],
        ],
    ]);
    /*    $i = "A";
        foreach ($data[0] as $datum) {
            $exporter->renderCell($i."4",null,[
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ff7a70', // красный
                    ],
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
            $i++;
        }
        $i = "A";
        foreach ($data[0] as $datum) {
            $exporter->renderCell($i."3",null,[
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '66c365', // зеленый
                    ],
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
            $i++;
        }*/
    print_r($exporter->getLast)
    //    $exporter->save('file.xls');
    ?>
    <div class="box-body">
        <p>
            <!--            --><? //= Html::input($type)?>
            <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
            <?php print_r(Yii::$app->user->identity->role) ?>

        </p>


    </div>


</div>
