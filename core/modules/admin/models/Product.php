<?php

namespace app\modules\admin\models;

use app\common\BaseModel;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int     $id
 * @property int     $origin_id
 * @property string  $title                       Название
 * @property string  $price                       Цена
 * @property string  $amount_for_discount         Цена
 * @property string  $discount_price              Цена
 * @property string  $sale_price                  Цена продажи
 * @property int     $dirt                           Засор
 * @property string  $slug                        Слаг
 * @property string  $image                       картинка
 * @property int     $status                         Публиковать
 * @property int     $use_form                       Использовалать форму для продажи
 * @property int     $operation_sort                 Сортировка для вывода
 * @property int     $sell_only                      Только продажа
 * @property string  $updated_at                  Дата обновления
 * @property string  $imgUrl
 * @property string  $created_at                  Дата создания
 * @property mixed   error
 * @property Product origin
 */
class Product extends BaseModel {

    public $file;

    public static function tableName(){
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(){
        return [
            [['title', 'price'], 'required'],
            [['price', 'sale_price', 'dirt', 'amount_for_discount', 'discount_price'], 'number'],
            [['status', 'origin_id', 'sell_only', 'use_form', 'operation_sort'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['title', 'slug', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id'                  => 'ID',
            'title'               => 'Название',
            'price'               => 'Цена за кг.',
            'sale_price'          => 'Цена продажи',
            'amount_for_discount' => 'Кол-во для надбавки',
            'discount_price'      => 'Цена с надбавкой',
            'dirt'                => 'Засор%',
            'slug'                => 'Слаг',
            'image'               => 'картинка',
            'img'                 => 'картинка',
            'imgUrl'              => 'картинка',
            'file'                => 'Картинка',
            'status'              => 'Публиковать',
            'origin_id'           => 'Исходный товар',
            'use_form'            => 'Использовалать форму для продажи',
            'operation_sort'      => 'Сортировка для вывода',
            'sell_only'           => 'Только продажа',
            'statusName'          => 'Публиковать',
            'updated_at'          => 'Дата обновления',
            'created_at'          => 'Дата создания',
        ];
    }

    public function behaviors(){
        return [
            [
                'class'     => SluggableBehavior::class,
                'attribute' => 'title',
                // 'slugAttribute' => 'slug',
            ],
        ];
    }


    public function beforeSave($insert){
        if ($insert){
            if (!$this->sale_price) {
                $this->sale_price = $this->price;
            }
        }
        if ( ! parent::beforeSave($insert)){
            return false;
        }

        // ...custom code here...
        $this->saveImg();

        return true;
    }

    public function saveImg(){

        $file = UploadedFile::getInstance($this, 'file');

        if ($file){
            $dir = $this->moduleUploadsPath();
            if ( ! is_dir($dir) && ! file_exists($dir)){
                FileHelper::createDirectory($dir);
            }
            $filePath = $dir . $this->slug . "." . $file->extension;
            if ( ! $file->saveAs($filePath)){
                dd($this->error);
            }
            $this->image = $this->slug . "." . $file->extension;
        }

        return true;
    }

    /**
     * @param array $htmlOptions
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getImg($htmlOptions = []){
        if ( ! $this->image){
            return null;
        }
        $defaultOptions = [
            'alt'   => $this->title,
            'class' => 'img-responsive',
        ];

        $options = ArrayHelper::merge($defaultOptions, $htmlOptions);

        $imgSrc = $this->moduleUploadsUrl() . $this->image;

        return Html::img($imgSrc, $options);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getImgUrl(){
        $imgSrc = null;
        if ($this->image){
            $imgSrc = $this->moduleUploadsUrl() . $this->image;
        }

        return $imgSrc;
    }

    public static function getCachePrice(){
        $data = Yii::$app->cache->getOrSet('price_list', function (){
            return Product::find()->where(['status' => Product::STATUS_PUBLISHED])->select([
                'title', 'price', 'id', 'discount_price', 'use_form',
            ])->indexBy('id')->asArray()->all();
        });

        return $data;
    }

    public function afterSave($insert, $changedAttributes){
        $this->refreshCash();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->refreshCash();
    }


    public static function getEmptyArray(){
        $arr      = [];
        $keys     = ['weight', 'sale_price', 'dirt', 'total', 'title', 'id'];
        $products = Product::getCachePrice();
        foreach ($products as $product){
            $arr[] = array_combine($keys, array_merge(array_fill(0, 4, null), [$product['title'], $product['id'],]));
        }

        return $arr;
    }

    private function refreshCash()
    {
        Yii::$app->cache->set('price_list', Product::find()->where(['status' => Product::STATUS_PUBLISHED])->select([
            'title', 'price', 'id', 'discount_price', 'use_form',
        ])->indexBy('id')->asArray()->all());
    }

    public function removeImg(){
        $file = getBaseUploadsPath() . DIRECTORY_SEPARATOR . strtolower($this::getModelName()) . DIRECTORY_SEPARATOR . $this->image;

        if (file_exists($file) && $this->image){
            unlink($file);
            $this->image = null;
            $this->save();

            return true;
        }

        return false;
    }

    public function getOrigin()
    {
        return $this->hasOne(Product::class, ['id' => 'origin_id']);
    }

}
