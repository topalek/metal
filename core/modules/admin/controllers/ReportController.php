<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use function count;
use function print_r;
use function strtotime;

class ReportController extends Controller {

    public function actionIndex(){


        $spreadsheet       = new Spreadsheet();
        $sheet             = $spreadsheet->getActiveSheet();
        $operations        = Operation::getArrayForReport(Operation::find()->asArray()->all());
        $products          = Product::getList();
        $columnsCount      = (count($products) * 3) + 2;
        $sellStyle         = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'ff7a70', // красный
                ],
            ],
        ];
        $cash              = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'bff6ff', // красный
                ],
            ],
        ];
        $alignStyle        = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ]
        ];
        $lastColumn        = $this->getLetterIdx($columnsCount);
        $rowIndex          = 1;
        $columnNumberIndex = 1;
        foreach ($operations as $i => $operation){
            $columnIndex = $startColumn = 'A';
            $date        = date("d.m.Y", strtotime($operation['created_at']));
            if ($operation['type'] == 0){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($sellStyle);
            }
            if ($operation['type'] == 2){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($cash);
                while ($columnIndex <= $lastColumn){
                    if ($columnIndex == "A"){
                        $sheet->setCellValue($columnIndex . $rowIndex, $date);
                        $columnIndex ++;
                        continue;
                    }
                    if ($columnIndex == "B"){
                        $sheet->setCellValue($columnIndex . $rowIndex, $operation['sum']);
                        $columnIndex ++;
                        continue;
                    }
                    $sheet->setCellValue($columnIndex . $rowIndex, 0);
                    $columnIndex ++;
                }
                continue;
            }

            while ($columnIndex <= $lastColumn){
                print_r($operation);
                die;
                if ($columnIndex == "A"){
                    $sheet->setCellValue($columnIndex . $rowIndex, $date);
                    $columnIndex ++;
                    continue;
                }
                if ($columnIndex == "B"){
                    $sheet->setCellValue($columnIndex . $rowIndex, 0);
                    $columnIndex ++;
                    continue;
                }
                foreach ($operation['products'] as $id => $product){
                    print_r($product);
                    die;
                    $sheet->setCellValue($columnIndex . $rowIndex, $product['weight']);
                    $columnIndex ++;
                    $sheet->setCellValue($columnIndex . $rowIndex, $product['sale_price']);
                    $columnIndex ++;
                    $sheet->setCellValue($columnIndex . $rowIndex, $product['total']);
                    $columnIndex ++;
                    continue;
                }
                continue;
            }
            $rowIndex ++;
        }
        //print_r($headerColumns);die;
        $t = ['масса', 'цена', 'сумма'];
        //$sheet->setCellValue("A1", "Дата")->mergeCells("A1:A2");
        //$sheet->setCellValue("B1", "Касса")->mergeCells("B1:B2");
        //        print_r($this->getLetterIdx($columnsCount));die;
        //$sheet->getStyle("A3:" . $this->getLetterIdx($columnsCount) . 3)->applyFromArray();

        //        $sheet->setCellValue("C3", "Hello world");
        //        $sheet->setCellValue("A1", "Metal");
        //        $sheet->mergeCells("A1:C1")->getStyle("A1:C1")->applyFromArray([
        //            'fill'      => [
        //                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //                'color'    => [
        //                    'rgb' => 'ff7a70', // красный
        //                ],
        //            ],
        //            'alignment' => [
        //                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //            ],
        //        ]);

        $xls = new Xls($spreadsheet);
        $xls->save('file.xls');

        return Yii::$app->response->sendFile('file.xls');

        return $this->render('index');
    }

    public function actionDay(){
        $date       = date('Y-m-d');
        $operations = Operation::find()->where(['created_at' => $date])->all();

        return $this->render('day', ["operations" => $operations, "date" => $date]);

    }

    public function actionPeriod(){
        $post     = Yii::$app->request->post();
        $fromDate = ArrayHelper::getValue($post, 'from_date');
        $toDate   = ArrayHelper::getValue($post, 'to_date');
        if ( ! $fromDate or ! $toDate){
            throw new InvalidArgumentException('Неверно заполнена дата');
        }
        $fromDate   = date('Y-m-d', strtotime($fromDate));
        $toDate     = date('Y-m-d', strtotime($toDate));
        $date       = $fromDate . " - " . $toDate;
        $operations = Operation::find()
            //            ->where(['>=', 'created_at', $fromDate])
            //            ->andWhere(['<=', 'created_at', $toDate])
                               ->asArray()
                               ->all();


        return $this->render('period', ["operations" => $operations, "date" => $date]);

    }

    public function getLetterIdx(int $idx){
        $lIdx = "A";
        for ($i = 1; $i <= $idx; $i ++){
            $lIdx ++;
        }

        return $lIdx;
    }


}
