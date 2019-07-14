<?php
/**
 * Created by topalek
 * Date: 14.07.2019
 * Time: 20:49
 */

use app\modules\admin\models\Operation;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OperationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'История операций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-history box">

    <div class="box-body">
        <h2><?= $this->title ?></h2>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText'    => 'Операций не найдено',
            'rowOptions'   => function ($model){
                if ($model->type == Operation::TYPE_BUY){
                    return ['class' => 'info'];
                }
                if ($model->type == Operation::TYPE_FILL_CASH){
                    return ['class' => 'success'];
                }

                return ['class' => 'danger'];
            },
            'afterRow'     => function ($model){
                if ($model->type == Operation::TYPE_FILL_CASH || $model->type == Operation::TYPE_REST_CASH){
                    return "";
                }
                $html = '<tr><th>название</th><th>вес</th><th>цена</th><th>засор</th><th>общаяя стоимость</th></tr> ';
                foreach ($model->products as $product){
                    $html .= '<tr><td>' . $product['title'] . '</td><td>' . $product['weight'] . '</td><td>' . $product['sale_price'] . '</td><td>' . $product['dirt'] . '</td><td>' . $product['total'] . '</td></tr>';
                }

                return $html;
            },
            'columns'      => [

                'id',
                [
                    'attribute' => 'type',
                    "value"     => "typeName",
                ],
                'sum',
                //[
                //    'attribute' => 'comment',
                //    "value"     => "typeName",
                //],
                'comment',
                'created_at',
            ],
        ]); ?>
    </div>

</div>
