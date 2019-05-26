<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\common\Inflector;
use app\modules\admin\models\Operation;
use app\modules\admin\models\Product;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;


class InitController extends Controller
{
    public $products = [
        [
            'price'       => 4.70,
            'sale_price'  => 4.70,
            'title'       => "Черный металл",
            'report_sort' => 10
        ], [
            'price'       => 125.00,
            'sale_price'  => 125.00,
            'title'       => "Медь",
            'report_sort' => 1
        ], [
            'price'       => 29.00,
            'sale_price'  => 29.00,
            'title'       => "Аллюминий",
            'report_sort' => 7
        ], [
            'price'       => 75.00,
            'sale_price'  => 75.00,
            'title'       => "Латунь",
            'report_sort' => 2
        ], [
            'price'       => 21.00,
            'sale_price'  => 21.00,
            'title'       => "Нержавейка",
            'report_sort' => 11
        ], [
            'price'       => 27.00,
            'sale_price'  => 27.00,
            'title'       => "Аккумуляторы (слитый)",
            'report_sort' => 14
        ], [
            'price'       => 27.00,
            'sale_price'  => 27.00,
            'title'       => "Аккумулятор (залитый)",
            'report_sort' => 15
        ], [
            'price'       => 17.00,
            'sale_price'  => 17.00,
            'title'       => "Аккумулятор черный",
            'report_sort' => 16
        ], [
            'price'       => 42.00,
            'sale_price'  => 42.00,
            'title'       => "Свинец",
            'report_sort' => 18
        ], [
            'price'       => 29.00,
            'sale_price'  => 29.00,
            'title'       => "ЦАМ",
            'report_sort' => 9
        ], [
            'price'       => 13.00,
            'sale_price'  => 13.00,
            'title'       => "ТНЖ",
            'report_sort' => 17
        ], [
            'price'       => 12.00,
            'sale_price'  => 12.00,
            'title'       => "Фера",
            'report_sort' => 19
        ], [
            'price'       => 16.50,
            'sale_price'  => 16.50,
            'title'       => "Солярка",
            'report_sort' => 20
        ], [
            'price'       => 1.00,
            'sale_price'  => 1.00,
            'title'       => "Скрап",
            'report_sort' => 22
        ], [
            'price'       => 62.00,
            'sale_price'  => 62.00,
            'title'       => "Стружка бронзовая ",
            'report_sort' => 4
        ], [
            'price'       => 0,
            'sale_price'  => 0,
            'title'       => "Стружка аллюминевая",
            'report_sort' => 5
        ], [
            'price'       => 0,
            'sale_price'  => 0,
            'title'       => "Стружка черная",
            'report_sort' => 6
        ], [
            'price'       => 20.00,
            'sale_price'  => 20.00,
            'title'       => "Аллюминевая банка",
            'report_sort' => 8
        ], [
            'price'       => 20.00,
            'sale_price'  => 20.00,
            'title'       => "Магний",
            'report_sort' => 13
        ], [
            'price'       => 45.00,
            'sale_price'  => 45.00,
            'title'       => "Титан",
            'report_sort' => 12
        ], [
            'price'       => 30.00,
            'sale_price'  => 30.00,
            'title'       => "Цинк",
            'report_sort' => 21
        ], [
            'price'       => 10.00,
            'sale_price'  => 10.00,
            'title'       => "Платы",
            'report_sort' => 23
        ], [
            'price'       => 350.00,
            'sale_price'  => 350.00,
            'title'       => "Платы моб. тел.",
            'report_sort' => 24
        ], [
            'price'       => 62.00,
            'sale_price'  => 62.00,
            'title'       => "Р.М.Л.",
            'report_sort' => 3
        ], [
            'price'       => 11.00,
            'sale_price'  => 11.00,
            'title'       => "Деловой металл",
            'sell_only'   => 1,
            'report_sort' => 25
        ],
    ];

    /**
     * This command echoes what you have entered as the message.
     *
     * @param string $message the message to be echoed.
     *
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world'){
        echo $message . "\n";

        return ExitCode::OK;
    }

    /**
     * Команда создает товары из массива
     */
    public function actionImport(){

        Console::startProgress($i = 0, count($this->products));
        foreach ($this->products as $product){
			$model             = new Product();
			$product['slug']   = Inflector::slug($product['title']);
			$model->attributes = $product;
			if ( ! $model->save(false)){
				print_r($model->getErrors());
				die;
			}
            Console::updateProgress($i ++, count($this->products));
		}
		Console::endProgress();

	}

    /**
     *
     * Команда создает Роли и Разрешения
     *
     * @throws Exception
     */
    public function actionRbac(){

        //if (\Yii::$app->db->getTableSchema('{{%table_name}}', true) !== null) {
        //    // какой-то код для работы с данной таблицей...
        //}
		$role              = Yii::$app->authManager->createRole('admin');
		$role->description = 'Администратор';
		Yii::$app->authManager->add($role);

		$role              = Yii::$app->authManager->createRole('user');
		$role->description = 'Пользователь';
		Yii::$app->authManager->add($role);

		$permit              = Yii::$app->authManager->createPermission('canAdmin');
		$permit->description = 'Право входа в админку';
		Yii::$app->authManager->add($permit);

		$role   = Yii::$app->authManager->getRole('admin');
		$permit = Yii::$app->authManager->getPermission('canAdmin');
		Yii::$app->authManager->addChild($role, $permit);

		$userRole = Yii::$app->authManager->getRole('admin');
		Yii::$app->authManager->assign($userRole, 1);

		echo "Roles & rule created";
	}

    public function actionUpdateTime(){
        $operations = Operation::findAll(['status' => 0]);
        foreach ($operations as $operation){
            $operation->created_at = $operation->updated_at;
            $operation->save(false);
        }
        print_r("Done \n");
    }
}
