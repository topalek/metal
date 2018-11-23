<?php

namespace app\modules\admin\models;

use app\common\BaseModel;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int    $id
 * @property string $title         Название
 * @property string $price         Цена
 * @property string $sale_price    Цена продажи
 * @property string $slug          Слаг
 * @property string $image         картинка
 * @property int    $status        Публиковать
 * @property int    $sell_only     Только продажа
 * @property string $updated_at    Дата обновления
 * @property string $created_at    Дата создания
 */
class Product extends BaseModel
{
    public $file;

    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price'], 'required'],
            [['price', 'sale_price'], 'number'],
            [['status', 'sell_only'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['title', 'slug', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'title'      => 'Название',
            'price'      => 'Цена за кг.',
            'sale_price' => 'Цена за кг.',
            'slug'       => 'Слаг',
            'image'      => 'картинка',
            'img'        => 'картинка',
            'imgUrl'     => 'картинка',
            'file'       => 'Картинка',
            'status'     => 'Публиковать',
            'sell_only'  => 'Только продажа',
            'statusName' => 'Публиковать',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'     => SluggableBehavior::class,
                'attribute' => 'title',
                // 'slugAttribute' => 'slug',
            ],
        ];
    }


    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // ...custom code here...
        $this->saveImg();

        return true;
    }

    public function saveImg()
    {

        $file = UploadedFile::getInstance($this, 'file');

        if ($file) {
            $dir = $this->moduleUploadsPath();
            if (!is_dir($dir) && !file_exists($dir)) {
                FileHelper::createDirectory($dir);
            }
            $filePath = $dir . $this->slug . "." . $file->extension;
            if (!$file->saveAs($filePath)) {
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
    public function getImg($htmlOptions = [])
    {
        if (!$this->image) {
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
     */
    public function getImgUrl()
    {
        $imgSrc = null;
        if ($this->image) {
            $imgSrc = $this->moduleUploadsUrl() . $this->image;
        }

        return $imgSrc;
    }

}
