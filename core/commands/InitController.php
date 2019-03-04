<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\common\Inflector;
use app\modules\admin\models\Product;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
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
	public function actionIndex($message = 'hello world'){
		echo $message . "\n";

		return ExitCode::OK;
	}

	public function actionImport(){
		$products = [
			[
				'price'      => 4.70,
				'sale_price' => 4.70,
				'title'      => "Черный металл",
			], [
				'price'      => 125.00,
				'sale_price' => 125.00,
				'title'      => "Медь",
			], [
				'price'      => 29.00,
				'sale_price' => 29.00,
				'title'      => "Аллюминий",
			], [
				'price'      => 75.00,
				'sale_price' => 75.00,
				'title'      => "Латунь",
			], [
				'price'      => 21.00,
				'sale_price' => 21.00,
				'title'      => "Нержавейка",
			], [
				'price'      => 27.00,
				'sale_price' => 27.00,
				'title'      => "Аккумуляторы (слитый)",
			], [
				'price'      => 27.00,
				'sale_price' => 27.00,
				'title'      => "Аккумулятор (залитый)",
			], [
				'price'      => 17.00,
				'sale_price' => 17.00,
				'title'      => "Аккумулятор черный",
			], [
				'price'      => 42.00,
				'sale_price' => 42.00,
				'title'      => "Свинец",
			], [
				'price'      => 29.00,
				'sale_price' => 29.00,
				'title'      => "ЦАМ (цинк, аллюминий, медь)",
			], [
				'price'      => 13.00,
				'sale_price' => 13.00,
				'title'      => "ТНЖ",
			], [
				'price'      => 12.00,
				'sale_price' => 12.00,
				'title'      => "Фера",
			], [
				'price'      => 16.50,
				'sale_price' => 16.50,
				'title'      => "Солярка",
			], [
				'price'      => 1.00,
				'sale_price' => 1.00,
				'title'      => "Скрап",
			], [
				'price'      => 62.00,
				'sale_price' => 62.00,
				'title'      => "Стружка бронзовая ",
			], [
				'price'      => 0,
				'sale_price' => 0,
				'title'      => "Стружка аллюминевая",
			], [
				'price'      => 0,
				'sale_price' => 0,
				'title'      => "Стружка черная",
			], [
				'price'      => 20.00,
				'sale_price' => 20.00,
				'title'      => "Аллюминевая банка",
			], [
				'price'      => 20.00,
				'sale_price' => 20.00,
				'title'      => "Магний",
			], [
				'price'      => 45.00,
				'sale_price' => 45.00,
				'title'      => "Титан",
			], [
				'price'      => 30.00,
				'sale_price' => 30.00,
				'title'      => "Цинк",
			], [
				'price'      => 10.00,
				'sale_price' => 10.00,
				'title'      => "Платы",
			], [
				'price'      => 350.00,
				'sale_price' => 350.00,
				'title'      => "Платы мобильных телефонов",
			], [
				'price'      => 62.00,
				'sale_price' => 62.00,
				'title'      => "Р.М.Л.",
			], [
				'price'      => 11.00,
				'sale_price' => 11.00,
				'title'      => "Деловой металл",
				'sell_only'  => 1
			],
		];


		Console::startProgress($i=0, count($products));
		foreach ($products as $product) {
			$model             = new Product();
			$product['slug']   = Inflector::slug($product['title']);
			$model->attributes = $product;
			if ( ! $model->save(false)){
				print_r($model->getErrors());
				die;
			}
			Console::updateProgress($i ++, count($products));
		}
		Console::endProgress();

	}

	public function actionRbac(){

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
}
