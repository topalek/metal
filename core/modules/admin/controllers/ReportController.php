<?php

namespace app\modules\admin\controllers;

use InvalidArgumentException;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;

class ReportController extends Controller {

    private $reportFileName;

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionGetFile($fileName)
    {
        $this->reportFileName = $fileName;
        $file = Yii::$app->runtimePath . "/report.xls";
        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file, $this->reportFileName);
        } else {
            Yii::$app->session->setFlash('error', 'Возникла ошибка. Файл не сформирован');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionPeriod($day = 0)
    {
        $fileName = Yii::$app->runtimePath . "/report.xls";
        if ($day) {
            $date = date('d-m-Y');
            $fromDate = date('d-m-Y', strtotime($date));
            $toDate = date('d-m-Y', strtotime($date . "+1 day"));
            $this->reportFileName = "Отчет " . date("d.m.Y") . ".xls";
        } else {
            $post = Yii::$app->request->post();
            $fromDate = ArrayHelper::getValue($post, 'from_date');
            $toDate = ArrayHelper::getValue($post, 'to_date');
            if (!$fromDate or !$toDate) {
                throw new InvalidArgumentException('Неверно заполнена дата');
            }
            $fromDate = date('d-m-Y', strtotime($fromDate));
            $toDate = date('d-m-Y', strtotime($toDate . "+1 day"));
            $this->reportFileName = "Отчет " . date('d.m.Y', strtotime($fromDate)) . "-" . date('d.m.Y', strtotime($toDate . "-1 day")) . ".xls";
        }
        try {
            $basePath = Yii::$app->basePath . '/';
            $cmd = "php " . $basePath . "yii report/get $fromDate $toDate $day";
            $locale = 'en_US.UTF-8';
            setlocale(LC_ALL, $locale);
            putenv('LC_ALL=' . $locale);
            file_put_contents(Yii::$app->runtimePath . "/log.txt", print_r($cmd . "\n", 1));
            if (shell_exec($cmd)) {
                Yii::$app->session->setFlash('success', Html::a('Скачать отчет', ['report/get-file', 'fileName' => $this->reportFileName]));
            }else{
                Yii::$app->session->setFlash('error', 'Возникла ошибка');
            }

        }catch (Exception $exception){
            print_r($exception);
            Yii::$app->session->setFlash('error', 'Возникла ошибка');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRunCommand(){
        $cmd = "php /home/cp369200/kram-met.club/www/core/yii report/test";
        var_dump(shell_exec($cmd));
        //if (shell_exec($cmd)){
        //    Yii::$app->session->setFlash('success', Html::a('Скачать отчет', [
        //        'report/get-file', 'fileName' => $this->reportFileName
        //    ]));
        //}else{
        //    Yii::$app->session->setFlash('error', 'Возникла ошибка');
        //}
        //
        //return $this->redirect(Yii::$app->request->referrer);
    }

}
