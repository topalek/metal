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
<div id="buy">
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
        </div>
        <div class="col-md-4">
            <h2 class="pricelist-title">Стоимость</h2>
            <?= $priceList ?>
        </div>
    </div>
</div>
<div id="clients" class="btn-group container" role="group"></div>



