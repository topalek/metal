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
use yii\helpers\Html;
use yii\widgets\ListView;


$this->title = "Метал : " . $model->getTypeName();
?>
    <div class="row">
        <div class="col-md-8">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView'     => '_item_view',
            'itemOptions'  => ['class' => 'col-md-3 col-sm-3 col-xs-4'],
            'options'      => ['class' => 'flex flex-w'],
            'summary'      => '',
            'viewParams'   => ['operation' => $model],
        ]); ?>
            <div class="col-md-3 col-sm-3 col-xs-4">
                <?= Html::a("Остаток денежных средств", ['operation/rest-cash'], ['class' => 'btn btn-primary']) ?>

            </div>
    </div>
        <div class="col-md-4">
            <?= $priceList ?>
        </div>
    </div>



<?php
$this->registerJs(<<<JS
$('.operation').attr('href',$('.operation').attr('href')+{$model->type})
JS
);

