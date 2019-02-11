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

$this->title = "Метал : " . $model->getTypeName();
?>
<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView'     => '_item_view',
	'itemOptions'  => ['class' => 'col-md-2'],
	'options'      => ['class' => 'flex flex-w'],
	'summary'      => '',
	'viewParams'   => ['operation' => $model],
]);
$this->registerJs(<<<JS
$('.operation').attr('href',$('.operation').attr('href')+{$model->type})
JS
);
