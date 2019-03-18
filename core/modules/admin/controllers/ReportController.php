<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Yii;
use yii\helpers\ArrayHelper;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $operations = Operation::getArrayForReport(Operation::find()->asArray()->all());
        $prods = Product::getList();
        $columnsCount = (count($prods) * 3) + 2;
        $t = ['масса', 'цена', 'сумма'];
        $c = "A";
        $sheet->setCellValue("A1", "Дата")->mergeCells("A1:A2");
        $sheet->setCellValue("B1", "Касса")->mergeCells("B1:B2");
//        print_r($this->getLetterIdx($columnsCount));die;
        $sheet->getStyleByColumnAndRow("A", 3, $this->getLetterIdx($columnsCount), 3)->applyFromArray([
            'fill'      => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color'    => [
                    'rgb' => 'ff7a70', // красный
                ],
            ],
        ]);
        $d = 3;
        $idx = 1;
        for ($i = 3; $i < $columnsCount; $i++) {
            if ($d == 3) {
                $idx++;
                $d = 1;
            }
            $sheet->setCellValue($c . "1", $prods[$d])->getStyle($c . "1")->applyFromArray(['alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]]);

            $sheet->setCellValue($c . "2", $t[$i % 3])->getStyle($c . "2")->applyFromArray(['alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]]);

            $d++;
            $c++;
        }
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

    public function actionDay()
    {
        $date = date('Y-m-d');
        $operations = Operation::find()->where(['created_at' => $date])->all();

        return $this->render('day', ["operations" => $operations, "date" => $date]);

    }

    public function actionPeriod()
    {
        $post = Yii::$app->request->post();
        $fromDate = ArrayHelper::getValue($post, 'from_date');
        $toDate = ArrayHelper::getValue($post, 'to_date');
        if (!$fromDate or !$toDate) {
            throw new InvalidArgumentException('Неверно заполнена дата');
        }
        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate));
        $date = $fromDate . " - " . $toDate;
        $operations = Operation::find()
//            ->where(['>=', 'created_at', $fromDate])
//            ->andWhere(['<=', 'created_at', $toDate])
            ->asArray()
            ->all();


        return $this->render('period', ["operations" => $operations, "date" => $date]);

    }

    public function getLetterIdx(int $idx)
    {
        $lIdx = "A";
        for ($i = 1; $i <= $idx; $i++) {
            $lIdx++;
        }
        return $lIdx;
    }

}
