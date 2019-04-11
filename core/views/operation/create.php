<?php
/**
 * Created by topalek
 * Date: 31.10.2018
 * Time: 20:31
 *
 * @var $this         yii\web\View
 * @var $model        app\modules\admin\models\Operation
 * @var $dataProvider ActiveDataProvider
 * @var $priceList    string
 *
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;


$this->title = "Метал : " . $model->getTypeName();
?>
    <div class="row">
        <div class="col-md-4">
            <?= $priceList ?>
    </div>
        <div class="col-md-8">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView'     => '_item_view',
            'itemOptions'  => ['class' => 'col-md-3'],
            'options'      => ['class' => 'flex flex-w'],
            'summary'      => '',
            'viewParams'   => ['operation' => $model],
        ]); ?>
    </div>
    </div>



<?php
$this->registerJs(<<<JS
$('.operation').attr('href',$('.operation').attr('href')+{$model->type})
JS
);

