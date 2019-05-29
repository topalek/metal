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
                    'rgb' => '000000',
                ]
            ]
        ],
    ];
    public $borders = [
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => '000000',
                ],
            ],
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
                    'rgb' => '000000',
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
                    'rgb' => '000000',
                ]
            ]
        ],
        ''
    ];
    public $rest = [
        'fill'    => [
            'fillType' => Fill::FILL_SOLID,
            'color'    => [
                'rgb' => 'ffc0f2', // pink
            ],
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => [
                    'rgb' => "000000"//'d0d7e5'
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
                    'rgb' => '000000',
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
                    'rgb' => '000000',
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
    public $headers;

    public function actionIndex(){

        return $this->render('index');
    }

    public function getHeaders($operations){
        if ( ! $this->headers){
            $this->headers = Operation::getHeadings($operations);
        }

        return $this->headers;
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
        $fromDate   = date('Y-m-d 00:00:00', strtotime($fromDate));
        $toDate     = date('Y-m-d 00:00:00', strtotime($toDate . "+1 day"));
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
        $headers     = $this->getHeaders($operations);
        $colCount    = $this->getColumnCount($headers);
        $lastColumn  = $this->getLetterIdx($colCount);
        $sheet       = $this->buildHeaders($sheet, $headers);
        $sheet->setCellValue("A1", "Дата")->mergeCells("A1:A2")
            ->getStyle("A1:A2")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("B1", "Касса")->mergeCells("B1:B2")
            ->getStyle("B1:B2")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("C1", "Коментарий")->mergeCells("C1:C2")
            ->getStyle("C1:C2")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $row = 3;

        foreach ($operations as $operation){
            $col = "A";
            $type    = ArrayHelper::getValue($operation, "type");
            $date    = ArrayHelper::getValue($operation, "created_at");
            $sum     = ArrayHelper::getValue($operation, "sum");
            $comment = ArrayHelper::getValue($operation, "comment");
            $sheet->setCellValue($col . $row, $date);
            $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);
            $sheet->setCellValue($col . $row, $sum);
            $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);
            $sheet->setCellValue($col . $row, $comment);
            $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);

            switch ($type){
                case Operation::TYPE_SELL:
                    $sheet->getStyle("A" . $row . ":" . $lastColumn . $row)->applyFromArray($this->sellStyle);
                    break;
                case Operation::TYPE_FILL_CASH:
                    $sheet->getStyle("A" . $row . ":" . $lastColumn . $row)->applyFromArray($this->cash);
                    break;
                case Operation::TYPE_REST_CASH:
                    $sheet->getStyle("A" . $row . ":" . $lastColumn . $row)->applyFromArray($this->rest);
                    break;
            }
            $products = ArrayHelper::getValue($operation, 'products');
            foreach ($headers as $id => $header) {
                $count = ArrayHelper::getValue($header, 'count');
                if (array_key_exists($id, $products)) {
                    $weight = ArrayHelper::getValue($products[$id], "weight");
                    $price = ArrayHelper::getValue($products[$id], "price");
                    $total = ArrayHelper::getValue($products[$id], "total");
                    $discount_price = ArrayHelper::getValue($products[$id], "discount_price");
                    $discount = ArrayHelper::getValue($products[$id], "discount");
                    $sheet->setCellValue($col . $row, $weight);
                    if (strpos($weight, "-") !== false) {
                        $sheet->getStyle($col . $row)->applyFromArray($this->negative);
                    }
                    $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);

                    if (!$discount) {
                        $sheet->setCellValue($col . $row, $price);
                        $sheet->getStyle($col . $row)->applyFromArray($this->borders);
                        $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);
                    } else {
                        $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);
                        $sheet->setCellValue($col . $row, $discount_price);
                        $sheet->getStyle($col++ . $row)->applyFromArray($this->borders);
                    }

                    $sheet->setCellValue($col . $row, $total);
                    $sheet->getStyle($col . $row)->applyFromArray($this->totalStyle);
                    if (strpos($total, "-") !== false) {
                        $sheet->getStyle($col . $row)->applyFromArray($this->negative);
                    }
                    $col++;
                } else {
                    $col++;
                    $col++;
                    $col++;
                    if ($count == 4) {
                        $col++;
                    }
                }
            }

            $row ++;
        }
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $xls = new Xls($spreadsheet);
        $xls->save($fileName);

        return $fileName;
    }

    public function buildHeaders(Worksheet $sheet, array $headers){

        $col = "D";
        $row = 1;

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
        $col = "D";
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

    private function buildRow(Worksheet $sheet, array $operation, $row){
        $headers = $this->getHeaders([]);
        $type    = ArrayHelper::getValue($operation, "type");
        switch ($type){
            case Operation::TYPE_SELL:
                break;
            case Operation::TYPE_FILL_CASH:
                break;
            case Operation::TYPE_REST_CASH:
                break;
        }
        foreach ($headers as $id => $header){
            $count = ArrayHelper::getValue($header, "count");

        }
    }

    private function getColumnCount(array $headers){
        $colCount = 3;
        foreach ($headers as $header){
            $colCount += ArrayHelper::getValue($header, "count");
        }

        return $colCount;
    }
}
