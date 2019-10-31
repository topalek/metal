<?php

namespace app\commands;

use app\models\User;
use app\modules\admin\models\Operation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class ReportController extends Controller {

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
    public $boldText = [
        'font' => [
            'bold' => true,
        ],
    ];
    public $headers;
    public $productStartCol = "F";
    public $operationRow = 1;

    public function actionGet($start, $end, $day = true){
        $fromDate   = date('Y-m-d 00:00:00', strtotime($start));
        $toDate     = date('Y-m-d 00:00:00', strtotime($end));
        $operations = Operation::getArrayForReport(Operation::getOperationByPeriod($fromDate, $toDate));
        $file       = $this->generateReportFile($operations);

        return $file;
    }

    public function generateReportFile(array $operationList){
        $fileName    = Yii::$app->runtimePath . "/report.xls";
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $headers     = $this->getHeaders($operationList);
        $colCount    = $this->getColumnCount($headers);
        $lastColumn  = $this->getLetterIdx($colCount);
        $users       = User::find()->select(['username', 'id'])->indexBy('id')->column();
        $row         = 1;
        $dayRow      = 2;
        foreach ($operationList as $listDate => $operations){
            $col = "A";
            $sheet->setCellValue($col . $row ++, $listDate);
            $sheet = $this->buildHeaders($sheet, $headers, $row ++);
            $row ++;
            foreach ($operations as $operation){
                $col     = "A";
                $type    = ArrayHelper::getValue($operation, "type");
                $user    = ArrayHelper::getValue($users, ArrayHelper::getValue($operation, "user_id"));
                $date    = ArrayHelper::getValue($operation, "created_at");
                $sum     = ArrayHelper::getValue($operation, "sum");
                $comment = ArrayHelper::getValue($operation, "comment");
                $sheet->setCellValue($col . $row, $date);
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                $sheet->setCellValue($col . $row, $user);
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                if ($type != Operation::TYPE_BUY){
                    if ($type == Operation::TYPE_FILL_CASH){
                        $sheet->setCellValue($col . $row, $sum);
                        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                    }else{
                        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                        $sheet->setCellValue($col . $row, $sum);
                    }

                }
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                $sheet->setCellValue($col . $row, $comment);
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

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
                $productsArray      = ArrayHelper::getValue($operation, 'products');
                $operationRowsCount = count(ArrayHelper::getValue($productsArray, 1));
                $k                  = 0;
                while ($k < $operationRowsCount){
                    $col = $this->productStartCol;
                    foreach ($productsArray as $id => $products){
                        $weight = ArrayHelper::getValue($productsArray[$id][$k], "weight");
                        $price  = ArrayHelper::getValue($productsArray[$id][$k], "price");
                        if ($price == "?"){
                            $price = null;
                        }
                        $total = ArrayHelper::getValue($productsArray[$id][$k], "total");
                        if ($total == "?"){
                            $total = null;
                        }
                        $prices = ArrayHelper::getValue($headers[$id], 'prices');
                        $count  = count($prices);
                        if ($count == 0){
                            $count = 1;
                        }
                        // SELL
                        if ($type == Operation::TYPE_SELL){
                            //заполняем вес
                            if ($weight){
                                if ($weight != "?"){
                                    $sheet->setCellValue($col . $row, "-" . $weight);
                                }else{
                                    $sheet->setCellValue($col . $row, "???");
                                }
                            }
                            $sheet->getStyle($col . $row)->applyFromArray($this->boldText);
                            $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

                            while ($count > 1){
                                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                                $count --;
                            }

                            //заполняем цену
                            if ($weight){
                                if ($price){
                                    $sheet->setCellValue($col . $row, $price);
                                }else{
                                    $sheet->setCellValue($col . $row, "???");
                                }
                                $sheet->getStyle($col . $row)->applyFromArray($this->boldText);
                            }
                            $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

                            //заполняем сумму
                            if ($weight){
                                if ($total){
                                    $sheet->setCellValue($col . $row, "-" . $total);
                                }else{
                                    $sheet->setCellValue($col . $row, "???");
                                }
                                $sheet->getStyle($col . $row)->applyFromArray($this->totalStyle);
                                $sheet->getStyle($col . $row)->applyFromArray($this->boldText);
                            }
                            $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

                        }else{

                            //заполняем вес
                            while ($count >= 1){
                                $idx = $count - 1;
                                if (isset($prices[$idx])){
                                    if ($price == $prices[$idx]){
                                        $sheet->setCellValue($col . $row, $weight);
                                        if (strpos($weight, "-") !== false){
                                            $sheet->getStyle($col . $row)->applyFromArray($this->negative);
                                        }
                                        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                                    }else{
                                        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                                    }
                                }else{
                                    $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                                }
                                $count --;
                            }
                            //заполняем цену
                            $sheet->setCellValue($col . $row, $price);
                            $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

                            //заполняем сумму
                            $sheet->setCellValue($col . $row, $total);
                            if (strpos($total, "-") !== false){
                                $sheet->getStyle($col . $row)->applyFromArray($this->negative);
                            }else{
                                $sheet->getStyle($col . $row)->applyFromArray($this->totalStyle);
                            }
                            $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                        }

                    }
                    $row ++;
                    $k ++;
                }
            }

            $sheet = $this->setFormulas($sheet, $headers, $dayRow, $row);
            $row   = $dayRow = $row + 2;
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
        }

        $sheet->freezePane('A4');
        $xls = new Xls($spreadsheet);
        $xls->save($fileName);

        return $fileName;
    }

    public function getHeaders($operations){
        if ( ! $this->headers){
            $this->headers = Operation::getHeadings($operations);
        }

        return $this->headers;
    }

    private function getColumnCount(array $headers){
        $colCount = 3;
        foreach ($headers as $header){
            $count = count(ArrayHelper::getValue($header, "prices"));
            if ($count == 0){
                $count = 1;
            }
            $count    += 2;
            $colCount += $count;
        }

        return $colCount;
    }

    public function getLetterIdx($idx){
        $lIdx = "A";
        for ($i = 1; $i < $idx; $i ++){
            $lIdx ++;
        }

        return $lIdx;
    }

    public function buildHeaders(Worksheet $sheet, array $headers, $row){
        $sheet = $this->setColumnTitles($sheet, $row);
        $col   = $this->productStartCol;
        // Заполняем название товаров
        foreach ($headers as $header){
            $startCol = $col;
            $count    = count(ArrayHelper::getValue($header, "prices"));
            if ($count == 0){
                $count = 1;
            }
            $count += 2;
            $title = ArrayHelper::getValue($header, "title");
            $sheet->getCell($col . $row)->setValue($title);
            while ($count > 1){
                $col ++;
                $count --;
            }
            $sheet->mergeCells($startCol . $row . ":" . $col . $row);
            $sheet->getStyle($startCol . $row . ":" . $col . $row)->applyFromArray($this->centerBold);
            $col ++;
        }
        $col = $this->productStartCol;
        $row ++;

        // Заполняем название столбцов
        foreach ($headers as $header){
            $count = count(ArrayHelper::getValue($header, "prices"));
            if ($count == 0){
                $count = 1;
            }
            while ($count >= 1){
                $sheet->getCell($col . $row)->setValue('масса');
                $sheet->getCell($col ++ . $row)->getStyle()->applyFromArray($this->alignStyle);
                $count --;
            }

            $sheet->getCell($col . $row)->setValue('цена');
            $sheet->getCell($col ++ . $row)->getStyle()->applyFromArray($this->alignStyle);

            $sheet->getCell($col . $row)->setValue('сумма');
            $sheet->getCell($col ++ . $row)->getStyle()->applyFromArray(array_merge($this->alignStyle, $this->totalStyle));

        }

        return $sheet;
    }

    private function setColumnTitles(Worksheet $sheet, $row){
        $nextRow = $row + 1;
        $sheet->setCellValue("A$row", "Дата")->mergeCells("A$row:A$nextRow")
              ->getStyle("A$row:A$nextRow")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("B$row", "Пользователь")->mergeCells("B$row:B$nextRow")
              ->getStyle("B$row:B$nextRow")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("C$row", "Касса")->mergeCells("C$row:C$nextRow")
              ->getStyle("C$row:C$nextRow")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("D$row", "Остаток")->mergeCells("D$row:D$nextRow")
              ->getStyle("D$row:D$nextRow")->applyFromArray(array_merge($this->centerBold, $this->borders));
        $sheet->setCellValue("E$row", "Коментарий")->mergeCells("E$row:E$nextRow")
              ->getStyle("E$row:E$nextRow")->applyFromArray(array_merge($this->centerBold, $this->borders));

        return $sheet;
    }

    private function setFormulas(Worksheet $sheet, array $headers, $startRow, $endRow){
        $col        = "A";
        $row        = $endRow;
        $formulaRow = $row - 1;
        $sheet->setCellValue($col . $row, "Итого:");
        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

        $formula = "=SUM($col$startRow:$col$formulaRow)";
        $sheet->setCellValue($col . $row, $formula);
        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);

        $formula = "=SUM($col$startRow:$col$formulaRow)";
        $sheet->setCellValue($col . $row, $formula);
        $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
        $col ++;
        foreach ($headers as $id => $header){
            $count = count(ArrayHelper::getValue($header, "prices"));
            if ($count){
                // формула для каждого веса
                while ($count >= 1){
                    $formula = "=SUM($col$startRow:$col$formulaRow)";
                    $sheet->setCellValue($col . $row, $formula);
                    $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
                    $count --;
                }
                // формула для цены ?
                $col ++;
                // формула для сумм
                $formula = "=SUM($col$startRow:$col$formulaRow)";
                $sheet->setCellValue($col . $row, $formula);
                $sheet->getStyle($col ++ . $row)->applyFromArray($this->borders);
            }else{
                $col ++;
                $col ++;
                $col ++;
            }
        }

        return $sheet;
    }
}
