<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ReportController extends Controller {

    public function actionIndex(){

        return $this->render('index');
    }

    public function actionDay(){
        $date       = date('Y-m-d');
        $operations = Operation::find()->where(['created_at' => $date])->asArray()->all();
        $operations = Operation::getArrayForReport($operations);
        $file       = $this->generateReportFile($operations);

        return Yii::$app->response->sendFile($file);

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
        $operations = Operation::find()
                               ->where(['>=', 'created_at', $fromDate])
                               ->andWhere(['<=', 'created_at', $toDate])
                               ->asArray()
                               ->all();


        $operations = Operation::getArrayForReport($operations);
        $file       = $this->generateReportFile($operations);

        return Yii::$app->response->sendFile($file);

    }

    public function getLetterIdx(int $idx){
        $lIdx = "A";
        for ($i = 1; $i < $idx; $i ++){
            $lIdx ++;
        }

        return $lIdx;
    }

    public function generateReportFile(array $operations){
        $fileName          = Yii::$app->runtimePath . "/report.xls";
        $spreadsheet       = new Spreadsheet();
        $sheet             = $spreadsheet->getActiveSheet();
        $products          = Product::getList();
        $columnsCount      = (count($products) * 3) + 2;
        $sellStyle         = [
            'fill'    => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'ffc66d', // orange
                ],
            ],
            'borders' => [
                'top'    => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'left'   => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'right'  => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
            ],
        ];
        $totalStyle        = [
            'fill'    => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'fceabb', // yellow
                ],
            ],
            'borders' => [
                'top'    => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'left'   => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'right'  => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
            ],
        ];
        $cash              = [
            'fill'    => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'bff6ff', // blue
                ],
            ],
            'borders' => [
                'top'    => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'left'   => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
                ],
                'right'  => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => [
                        'rgb' => 'd0d7e5'
                    ]
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
        $columnNumberIndex = 3;
        $columnIndex       = $startColumn = 'C';
        $t                 = ['масса', 'цена', 'сумма'];
        $i                 = 1;
        $sheet->setCellValue("A1", "Дата")->mergeCells("A1:A2")->getStyle("A1:A2")->applyFromArray($alignStyle);
        $sheet->setCellValue("B1", "Касса")->mergeCells("B1:B2")->getStyle("B1:B2")->applyFromArray($alignStyle);

        foreach ($products as $i => $product){
            $endCol = $this->getLetterIdx(($i * 3) + 2);
            $sheet->setCellValue($columnIndex . $rowIndex, $product)->mergeCells($columnIndex . $rowIndex . ":" . $endCol . $rowIndex)->getStyle($columnIndex . $rowIndex . ":" . $endCol . $rowIndex)->applyFromArray($alignStyle);
            $columnIndex ++;
            $columnIndex ++;
            $columnIndex ++;
        }
        $rowIndex ++;
        $columnNumberIndex = 3;
        $columnIndex       = $startColumn = 'C';
        while ($columnNumberIndex < $columnsCount){
            $tIdx = 0;
            foreach ($t as $item){
                if ($tIdx == 2){
                    $sheet->getStyle($columnIndex . ($rowIndex))->applyFromArray(array_merge($totalStyle, $alignStyle));
                }
                $sheet->setCellValue($columnIndex . ($rowIndex), $item)->getStyle($columnIndex . ($rowIndex))->applyFromArray($alignStyle);
                $tIdx ++;
                $columnIndex ++;
                $columnNumberIndex ++;
            }
        }
        $rowIndex ++;
        foreach ($operations as $i => $operation){
            $columnNumberIndex = 1;
            $columnIndex       = $startColumn = 'A';
            $date              = date("d.m.Y", strtotime($operation['created_at']));
            if ($operation['type'] == 1){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($sellStyle);
                $sheet->setCellValue("B" . $rowIndex, $operation['comment']);
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
                    $columnIndex       = "C";
                    $columnNumberIndex = 3;
                    foreach ($operation['products'] as $id => $product){
                        foreach ($product as $key => $item){
                            if ($columnNumberIndex % 3 == 2){
                                $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($totalStyle);
                            }
                            $sheet->setCellValue($columnIndex . $rowIndex, $item);
                            $columnIndex ++;
                            $columnNumberIndex ++;

                        }
                    }
                }
            }
            $rowIndex ++;
        }

        $xls = new Xls($spreadsheet);
        $xls->save($fileName);

        return $fileName;
    }
}
