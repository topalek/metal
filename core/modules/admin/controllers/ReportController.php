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
use function print_r;

class ReportController extends Controller {

    public function actionIndex(){

        print_r(Yii::$app->runtimePath);
        die;

        $spreadsheet  = new Spreadsheet();
        $sheet        = $spreadsheet->getActiveSheet();
        $operations   = Operation::getArrayForReport(Operation::find()->asArray()->all());
        $products     = Product::getList();
        $columnsCount = (count($products) * 3) + 2;
        $sellStyle    = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'ffc66d', // orange
                ],
            ],
        ];
        $totalStyle   = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'fceabb', // yellow
                ],
            ],
        ];
        $cash         = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'bff6ff', // blue
                ],
            ],
        ];
        $alignStyle   = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ]
        ];
        $lastColumn   = $this->getLetterIdx($columnsCount);
        $rowIndex     = 1;
        foreach ($operations as $i => $operation){
            $columnNumberIndex = 1;
            $columnIndex       = $startColumn = 'A';
            $date              = date("d.m.Y", strtotime($operation['created_at']));
            if ($operation['type'] == 1){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($sellStyle);
            }
            if ($operation['type'] == 2){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($cash);
                while ($columnNumberIndex < $columnsCount){
                    if ($columnNumberIndex == 1){
                        $sheet->setCellValue($columnIndex . $rowIndex, $date);
                        $columnIndex ++;
                        $columnNumberIndex ++;
                        continue;
                    }
                    if ($columnNumberIndex == 2){
                        $sheet->setCellValue($columnIndex . $rowIndex, $operation['sum']);
                        $columnIndex ++;
                        $columnNumberIndex ++;
                        continue;
                    }
                    $sheet->setCellValue($columnIndex . $rowIndex, 0);
                    $columnIndex ++;
                    $columnNumberIndex ++;
                }
                $rowIndex ++;
                continue;
            }

            for ($columnNumberIndex = 1; $columnNumberIndex < $columnsCount; $columnNumberIndex ++){
                if ($columnNumberIndex == 1){
                    $sheet->setCellValue($columnIndex . $rowIndex, $date);
                    $columnIndex ++;
                    $columnNumberIndex ++;
                    continue;
                }
                if ($columnNumberIndex == 2){
                    $sheet->setCellValue($columnIndex . $rowIndex, 0);
                    $columnIndex ++;
                    $columnNumberIndex ++;
                    continue;
                }

                if (isset($operation['products']) && $operation['products']){
                    $columnIndex = "C";
                    foreach ($operation['products'] as $id => $product){
                        foreach ($product as $key => $item){
                            $sheet->setCellValue($columnIndex . $rowIndex, $item);
                            $columnIndex ++;

                        }
                    }
                }
            }
            $rowIndex ++;
        }

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

        //return Yii::$app->response->sendFile('file.xls');

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
        for ($i = 1; $i < $idx; $i ++){
            $lIdx ++;
        }

        return $lIdx;
    }


}
