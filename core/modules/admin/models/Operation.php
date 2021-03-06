<?php

namespace app\modules\admin\models;

use app\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "operation".
 *
 * @property int    $id
 * @property int    $type        Тип операции
 * @property int    $user_id     пользователь
 * @property string $sum         Общая сумма
 * @property        $products    [] Товары
 * @property        $comment     Коментарий
 * @property int    $status      Публиковать
 * @property string $updated_at  Дата обновления
 * @property array  $list
 * @property mixed  $typeName
 * @property array  $nameList
 * @property mixed  $user
 * @property string $created_at  Дата создания
 *
 */
class Operation extends ActiveRecord
{
    const TYPE_BUY = 0;
    const TYPE_SELL = 1;
    const TYPE_FILL_CASH = 2;
    const TYPE_REST_CASH = 3;
    const REST_CASH_COMMENT = "Остаток денежных средств ";
    const FILL_CASH_COMMENT = "Пополнение кассы ";

    public static function tableName(){
        return 'operation';
    }

    public static function getArrayForReport(array $operations){
        $productList = Product::getList();
        foreach ($operations as $i => $item) {
            if (array_key_exists('products', $item)) {
                try {
                    $prods = Json::decode($item['products']);
                } catch (\Exception $e) {
                    $prods = [];
                }
                if (!$prods) {
                    $prods = [];
                }
                $type                       = ArrayHelper::getValue($item, "type");
                $operations[$i]['products'] = $prods;

                $out = [];
                foreach ($prods as $prod) {
                    $prodId         = ArrayHelper::getValue($prod, "id");
                    $weight         = ArrayHelper::getValue($prod, "weight");
                    $price          = ArrayHelper::getValue($prod, "sale_price");
                    $total          = ArrayHelper::getValue($prod, "total");
                    $discount       = ArrayHelper::getValue($prod, "discount");
                    $discount_price = ArrayHelper::getValue($prod, "discount_price");
                    if ($type != Operation::TYPE_SELL) {
                        if ($weight) {
                            $weight = floatval($weight);
                        }
                        $dirt = ArrayHelper::getValue($prod, "dirt");
                        if ($dirt) {
                            $dirt   = floatval($dirt);
                            $weight = $weight - ($weight / 100) * $dirt;
                        }
                    }
                    $out[$prodId][] = [
                        "weight"         => $weight,
                        "price"          => $price,
                        "total"          => $total,
                        "discount"       => $discount,
                        "discount_price" => $discount_price,
                    ];
                }
                $max = 0;
                foreach ($out as $oItem){
                    if (count($oItem) > $max){
                        $max = count($oItem);
                    }
                }
                foreach ($productList as $id => $productTitle) {
                    if (array_key_exists($id, $out)) {
                        $products = ArrayHelper::getValue($out, $id);
                        $count    = count($products);
                        while ($count < $max) {
                            $products[] = static::setEmptyProduct();
                            $count++;
                        }
                    } else {
                        $products = [];
                        $count    = 0;
                        while ($count < $max) {
                            $products[] = static::setEmptyProduct();
                            $count++;
                        }
                    }
                    $out[$id] = $products;
                }
                ksort($out);
                $operations[$i]['products'] = $out;
            }
            ksort($operations[$i]['products']);
            $reportArray = [];
            foreach ($operations as $key => $operation) {
                $date              = date("d/m/Y H:i:s", strtotime($operation['created_at']));
                $type              = $operation['type'];
                $total             = $operation['sum'];
                $reportArray[$key] = ['Дата' => $date];
                if ($type == self::TYPE_FILL_CASH) {
                    $reportArray[$key] = ['Касса' => $total];
                } else {
                    $reportArray[$key] = ['Касса' => 0];
                }

            }

        }
        $out = [];

        foreach ($operations as $operation) {
            $date         = date('d-m-Y', strtotime($operation['created_at']));
            $out[$date][] = $operation;
        }
        $operations = $out;
        unset($out);

        return $operations;
    }

    public static function getOperationByPeriod($start, $end){
        return Operation::find()
                        ->where(['>=', 'created_at', $start])
                        ->andWhere(['<=', 'created_at', $end])
                        ->andWhere(['NOT LIKE', 'products', 'Array'])
                        ->asArray()
                        ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['type', 'status', 'user_id'], 'integer'],
            [['sum'], 'number'],
            [['updated_at', 'created_at', 'comment'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id'         => 'ID',
            'type'       => 'Тип операции',
            'typeName'   => 'Тип операции',
            'sum'        => 'Общая сумма',
            'comment'    => 'Коментарий',
            'status'     => 'Проведенные',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }

    public function afterFind(){
        if ($this->products && $this->products !== 'Array') {
            try {
                $this->products = Json::decode($this->products);
            } catch (\Exception $e) {
                $this->products = [];
            }
        }
        parent::afterFind();
    }

    public function beforeSave($insert){
        if ($this->products && $this->products !== 'Array') {
            $this->products = Json::encode($this->products);
        }
        $this->user_id = Yii::$app->user->getId();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
    }

    public function getTypeName(){
        return ArrayHelper::getValue(self::getNameList(), $this->type);
    }

    public function getNameList(){
        return [
            static::TYPE_BUY       => 'Покупка',
            static::TYPE_SELL      => 'Продажа',
            static::TYPE_FILL_CASH => 'Пополнение кассы.',
            static::TYPE_REST_CASH => 'Остаток денежных средств.',
        ];
    }

    public function setComment($comment){
        $this->comment = $comment;
        $this->save();
    }

    public static function getHeadings(array $operationList){
        $list    = Product::getList();
        $headers = [];
        foreach ($operationList as $date => $operations) {
            foreach ($operations as $k => $operation) {
                $products = ArrayHelper::getValue($operation, 'products');
                foreach ($products as $id => $product) {
                    $headers[$id]['title'] = $list[$id];
                    foreach ($product as $item) {
                        $price = ArrayHelper::getValue($item, 'price');
                        if (isset($headers[$id]['prices'])) {
                            if (!in_array($price, $headers[$id]['prices']) && $price != "?") {
                                $headers[$id]['prices'][] = $price;
                            }
                        } else {
                            $headers[$id]['prices'][] = $price;
                        }
                    }
                }
            }
        }

        foreach ($headers as $id => $header){
            $arr = array_filter($headers[$id]['prices']);
            sort($arr);
            $headers[$id]['prices'] = $arr;
        }

        return $headers;
    }

    private static function setEmptyProduct(){
        return [
            'weight'         => null,
            'price'          => null,
            'total'          => null,
            "discount"       => null,
            "discount_price" => null,
        ];
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
