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
 * @var $client       integer
 *
 */

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;


$this->title = "Метал : " . $model->getTypeName();
?>
<div id="buy">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-11 col-xs-11">
                    <div id="clients" class="btn-group" role="group"></div>
                </div>
                <div class="col-sm-1 col-xs-1">
                    <button class="new-client btn btn-default">+</button>
                </div>
            </div>


            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView'     => '_item_view',
                'itemOptions'  => ['class' => 'col-md-3 col-sm-3 col-xs-4'],
                'options'      => ['class' => 'flex flex-w'],
                'summary'      => '',
                'viewParams'   => ['operation' => $model, 'client' => $client],
            ]); ?>
            <div class="flex flex-stretch">
                <?= Html::a(
                    "Отложить в деловой", ['operation/get-move-modal', "id" => 25, 'client' => $client], [
                    'class' => 'btn btn-primary flex-item operation-item',
                ]) ?>
                <?= Html::a(
                    "Отложить в продажу", ['operation/get-move-modal', "id" => 35, 'client' => $client], [
                    'class' => 'btn btn-success flex-item operation-item',
                ]) ?>
            </div>

        </div>
        <div class="col-md-4">
            <h2 class="pricelist-title">Стоимость</h2>
            <?= $priceList ?>
        </div>
    </div>
</div>


