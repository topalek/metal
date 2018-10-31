<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\admin\models\Product;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;


class InitController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     *
     * @param string $message the message to be echoed.
     *
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionImport()
    {
        $products = [
            [
                'price' => 4.70,
                'title' => "Черный металл",
            ], [
                'price' => 125.00,
                'title' => "Медь",
            ], [
                'price' => 29.00,
                'title' => "Аллюминий",
            ], [
                'price' => 75.00,
                'title' => "Латунь",
            ], [
                'price' => 21.00,
                'title' => "Нержавейка",
            ], [
                'price' => 27.00,
                'title' => "Аккумуляторы (слитый)",
            ], [
                'price' => 27.00,
                'title' => "Аккумулятор (залитый)",
            ], [
                'price' => 17.00,
                'title' => "Аккумулятор черный",
            ], [
                'price' => 42.00,
                'title' => "Свинец",
            ], [
                'price' => 29.00,
                'title' => "ЦАМ (цинк, аллюминий, медь)",
            ], [
                'price' => 13.00,
                'title' => "ТНЖ",
            ], [
                'price' => 12.00,
                'title' => "Фера",
            ], [
                'price' => 16.50,
                'title' => "Солярка",
            ], [
                'price' => 1.00,
                'title' => "Скрап",
            ], [
                'price' => 62.00,
                'title' => "Стружка бронзовая ",
            ], [
                'price' => 0,
                'title' => "Стружка аллюминевая",
            ], [
                'price' => 0,
                'title' => "Стружка черная",
            ], [
                'price' => 20.00,
                'title' => "Аллюминевая банка",
            ], [
                'price' => 20.00,
                'title' => "Магний",
            ], [
                'price' => 45.00,
                'title' => "Титан",
            ], [
                'price' => 30.00,
                'title' => "Цинк",
            ], [
                'price' => 10.00,
                'title' => "Платы",
            ], [
                'price' => 350.00,
                'title' => "Платы мобильных телефонов",
            ], [
                'price' => 62.00,
                'title' => "Р.М.Л.",
            ], [
                'price' => 11.00,
                'title' => "Деловой металл (продажа)",
            ],
        ];
//        $model = new Product();
//        print_r(0++);die;

        Console::startProgress($i=0, count($products));
        foreach ($products as $product) {
            Console::updateProgress($i++, count($products));
            $model = new Product();
            $model->attributes = ArrayHelper::merge($model->attributes,$product);
            if(!$model->save()){
                print_r($model->getErrors());die;
            }
//            print_r($model->attributes);die;
        }
        Console::endProgress();

    }
}
