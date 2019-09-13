<?php

namespace app\modules\admin\controllers;

use InvalidArgumentException;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;

class ReportController extends Controller {

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionDay(){
        $date = date('Y-m-d');
        $fromDate = date('Y-m-d', strtotime($date));
        $toDate = date('Y-m-d', strtotime($date . "+1 day"));
        $this->runAction('report', ['start' => $fromDate, 'end' => $toDate]);

    }

    public function actionPeriod(){
        $post     = Yii::$app->request->post();
        $fromDate = ArrayHelper::getValue($post, 'from_date');
        $toDate   = ArrayHelper::getValue($post, 'to_date');
        if ( ! $fromDate or ! $toDate){
            throw new InvalidArgumentException('Неверно заполнена дата');
        }
        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate . "+1 day"));
        $this->runAction('report', ['start' => $fromDate, 'end' => $toDate, 'day' => 0]);

    }

    public function actionReport($start, $end, $day = true)
    {
        try {
            $basePath = Yii::$app->basePath . '/';
            $cmd = "php " . $basePath . "yii report/get $start $end $day";

            $locale = 'en_US.UTF-8';
            setlocale(LC_ALL, $locale);
            putenv('LC_ALL=' . $locale);
            shell_exec($cmd);
        } catch (Exception $exception) {
            print_r($exception);
            Yii::$app->end();
        }
        $fileName    = Yii::$app->runtimePath . "/report.xls";
        if ($day) {
            $reportFileName = "Отчет " . date("d.m.Y") . ".xls";
        } else {
            $reportFileName = "Отчет " . date('d.m.Y', strtotime($start)) . "-" . date('d.m.Y', strtotime($end . "-1 day")) . ".xls";
        }
        Yii::$app->session->setFlash('success', Html::a($reportFileName, $fileName));

//        return Yii::$app->response->sendFile($fileName, $reportFileName);
        return $this->redirect(Yii::$app->request->referrer);
    }
}
