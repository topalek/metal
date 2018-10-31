<?php
/**
 * Created by topalek
 * Date: 31.10.2018
 * Time: 20:31
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/*  @var $this yii\web\View */
/* @var $model app\modules\admin\models\Operation */
/* @var $dataProvider ActiveDataProvider */


?>
<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView'     => '_item_view'
]); ?>