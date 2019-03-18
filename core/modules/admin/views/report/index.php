<?php

use app\models\User;
use app\modules\admin\models\Operation;
use kartik\date\DatePicker;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii2tech\spreadsheet\Spreadsheet;

/* @var $this yii\web\View */

$this->title = 'Отчеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">
    <div class="report-index box">
<pre>
    <? $data = Operation::getArrayForReport(Operation::find()->asArray()->all()); ?>
</pre>
        <?php
        //die;
        //        $data          = [
            //[
            //    'column1' => '1.1',
            //    'column2' => '1.2',
            //    'column3' => '1.3',
            //    '1.4',
            //    '1.4',
            //    '1.5',
            //    '1.6',
            //    '1.7',
            //], [
            //    'column1' => '2.1',
            //    'column2' => '2.2',
            //    'column3' => '2.3',
            //    '2.4',
            //    '2.4',
            //    '2.5',
            //    '2.6',
            //    '2.7',
            //],
        //        ];
        //        $data[]        = array_fill(1, 77, rand(1, 4560));
        //        $data[]        = array_fill(1, 77, rand(1, 4560));
        //        $data[]        = array_fill(1, 77, rand(1, 4560));
        //        $products      = Product::getList();
        //        $headerColumns = [];
        //        foreach ($products as $prod){
        //            if (empty($headerColumns)){
        //                $headerColumns[] = [
        //                    'header' => $prod,
        //                    'offset' => 2,
        //                    'length' => 3,
        //                ];
        //            }else{
        //                $headerColumns[] = [
        //                    'header' => $prod,
        //                    'offset' => 0,
        //                    'length' => 3,
        //                ];
        //            }
        //        }
        //echo "<pre>";
        //print_r($data);die;
        $exporter = new Spreadsheet([
            'dataProvider'       => new ArrayDataProvider([
                'allModels' => $data,
            ]),
            /* 'headerColumnUnions' => $headerColumns[
                 [
                     'header' => 'Skip 1 column and group 2 next',
                     'offset' => 2,
                     'length' => 3,
                 ],
                 [
                     'header' => 'Skip 2 columns and group 2 next',
                     'offset' => 0,
                     'length' => 3,
                 ],
             ],*/
        ]);
        $i        = "A";
        $t        = ['масса', 'цена', 'сумма'];
        $idx      = 1;
            foreach ($data[0] as $datum) {
                if ($idx < 3){
                    $idx ++;
                    $i ++;
                    continue;
                }
                $exporter->renderCell($i . "2", $t[$idx % 3], [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => [
                            'rgb' => 'ff' . (($idx % 3) * 2) . 'a70', // красный
                        ],
                    ],
                ]);
                $idx ++;
                //$exporter->renderCell($i."2",null,[
                //    'fill' => [
                //        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //        'color' => [
                //            'rgb' => 'ff7a70', // красный
                //        ],
                //    ],
                //    'borders' => [
                //        'top' => [
                //            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //        ],
                //        'left' => [
                //            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //        ],
                //        'right' => [
                //            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //        ],
                //        'bottom' => [
                //            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //        ],
                //    ],
                //]);
                $i++;
            }
        /* $i = "A";
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
        $exporter->send('file.xls');
        ?>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <p>
                        <?= Html::a('Отчет за день', ['day'], ['class' => 'btn btn-success']) ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <?= Html::beginForm(['period']) ?>
                <div class="col-md-2">
                    <div class="form-group field-user-username">
                        <label class="control-label" for="from_date">Начало периода</label>
                        <?= DatePicker::widget([
                            'name'          => 'from_date',
                            'id'            => 'from_date',
                            'value'         => date('d-m-Y', strtotime('-1 day')),
                            'type'          => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format'    => 'dd-mm-yyyy',
                            ],
                        ]) ?>

                        <div class="help-block"></div>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group field-user-username">
                        <label class="control-label" for="to_date">Конец периода</label>
                        <?= DatePicker::widget([
                            'type'          => DatePicker::TYPE_INPUT,
                            'name'          => 'to_date',
                            'id'            => 'to_date',
                            'value'         => date('d-m-Y'),
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format'    => 'dd-mm-yyyy',
                            ],
                        ]) ?>

                        <div class="help-block"></div>
                    </div>

                </div>
                <div class="col-md-6">
                    <?= Html::submitButton('Отчет за период', ['class' => 'btn btn-info', 'id' => 'period']) ?>

                </div>
                <?= Html::endForm() ?>
            </div>
            <p>
                <?php
                $user = User::findOne(1);
                var_dump($user->assignment) ?>
            </p>


        </div>


    </div>
</div>

<?php
$css = "
#period{
    position:relative;
    top:25px;
}
";
$this->registerCss($css) ?>

