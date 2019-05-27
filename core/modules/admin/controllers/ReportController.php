<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Operation;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use function array_merge;

class ReportController extends Controller {

    public $subHeaders = [
        4 => ['масса', 'цена', 'цена с надбавкой', 'сумма'],
        3 => ['масса', 'цена', 'сумма'],
    ];

    public $sellStyle = [
        'fill'    => [
            'fillType' => Fill::FILL_SOLID,
            'color'    => [
                'rgb' => 'ffc66d', // orange
            ],
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => 'd0d7e5'
                ]
            ]
        ],
    ];
    public $totalStyle = [
        'fill'    => [
            'fillType' => Fill::FILL_SOLID,
            'color'    => [
                'rgb' => 'fceabb', // yellow
            ],
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => 'd0d7e5'
                ]
            ]
        ],
    ];
    public $cash = [
        'fill'    => [
            'fillType' => Fill::FILL_SOLID,
            'color'    => [
                'rgb' => 'bff6ff', // blue
            ],
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => 'd0d7e5'
                ]
            ]
        ],
        ''
    ];
    public $negative = [
        'fill'    => [
            'fillType' => Fill::FILL_SOLID,
            'color'    => [
                'rgb' => '84ca84', // green
            ],
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => 'd0d7e5'
                ]
            ]
        ],
        ''
    ];
    public $alignStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
        'borders'   => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => 'd0d7e5'
                ]
            ]
        ],
    ];
    public $centerBold = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical'   => Alignment::VERTICAL_CENTER,
        ],
        'borders'   => [
            'outline' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color'       => [
                    'rgb' => '000000'
                ]
            ]
        ],
    ];


    public function actionIndex(){

        return $this->render('index');
    }

    public function actionDay(){
        $date       = date('Y-m-d 00:00:00');
        $fromDate   = date('Y-m-d 00:00:00', strtotime($date));
        $toDate     = date('Y-m-d 00:00:00', strtotime($date . "+1 day"));
        $operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));
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
        $fromDate = date('Y-m-d 00:00:00', strtotime($fromDate));
        $toDate   = date('Y-m-d 00:00:00', strtotime($toDate));

        $operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));
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
        $fileName    = Yii::$app->runtimePath . "/report.xls";
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet       = $this->buildHeaders($sheet, $operations);
        /*
        $products          = Product::getList();
        $columnsCount      = (count($products) * 3) + 2;
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
            $date              = date("d.m.Y H:i:s", strtotime($operation['created_at']));

            // продажа
            if ($operation['type'] == 1){
                $sheet->getStyle($startColumn . $rowIndex . ":" . $lastColumn . $rowIndex)->applyFromArray($sellStyle);
                $sheet->setCellValue("B" . $rowIndex, $operation['comment']);
            }

            // пополнение кассы
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
            //  покупка
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
                            if (strpos($item, "-") !== false){
                                $sheet->getStyle($columnIndex . $rowIndex)->applyFromArray($negative);
                            }
                            $columnIndex ++;
                            $columnNumberIndex ++;

                        }
                    }
                }
            }
            $rowIndex ++;
        }
        */
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $xls = new Xls($spreadsheet);
        $xls->save($fileName);

        return $fileName;
    }

    public function buildHeaders(Worksheet $sheet, array $operations){

        $headers = Operation::getHeadings($operations);
        $col     = "C";
        $row     = 1;

        // Заполняем название товаров
        foreach ($headers as $header){
            $startCol = $col;
            $count    = ArrayHelper::getValue($header, "count");
            $title    = ArrayHelper::getValue($header, "title");
            $sheet->getCell($col . $row)->setValue($title);
            while ($count > 1){
                $col ++;
                $count --;
            }
            $sheet->mergeCells($startCol . $row . ":" . $col . $row);
            $sheet->getStyle($startCol . $row . ":" . $col . $row)->applyFromArray($this->centerBold);
            $col ++;
        }
        $col = "C";
        $row = 2;

        // Заполняем название столбцов
        foreach ($headers as $header){
            $count = ArrayHelper::getValue($header, "count");
            foreach ($this->subHeaders[$count] as $i => $subHeader){
                $sheet->getCell($col . $row)->setValue($subHeader);
                if (($count == 4 && $i == 2)){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                if (($count == 4 && $i == 3) || ($count == 3 && $i == 2)){
                    $sheet->getCell($col . $row)->getStyle()->applyFromArray(array_merge($this->alignStyle, $this->totalStyle));

                }else{
                    $sheet->getCell($col . $row)->getStyle()->applyFromArray($this->alignStyle);
                }

                $col ++;
            }

        }

        return $sheet;
    }
}
