<?php

use app\modules\admin\models\Operation;
use kartik\date\DatePicker;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OperationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$layout2 = <<< HTML
<div class="input-group-prepend"><span class="input-group-text">Birth Date</span></div>
{input}
<div class="input-group-append"><span class="input-group-text">bef</span></div>
{picker}
HTML;
$this->title = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index box">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">
        <p>
            <? //= Html::a('Создать Операцию', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'emptyText'    => 'Операций не найдено',
            'summary'      => 'Операций не найдено',
            'rowOptions'   => function ($model) {
                return $model->type == Operation::TYPE_BUY ? ['class' => 'info'] : ['class' => 'danger'];
            },
            'afterRow'     => function ($item) {
                $html = '<tr><th>название</th><th>вес</th><th>цена</th><th>засор</th><th>общаяя стоимость</th></tr> ';
                foreach ($item->products as $product) {
                    $html .= '<tr><td>' . $product['title'] . '</td><td>' . $product['weight'] . '</td><td>' . $product['sale_price'] . '</td><td>' . $product['dirt'] . '</td><td>' . $product['total'] . '</td></tr>';
                }

                return $html;
            },
            'columns'      => [

                'id',
                [
                    'attribute' => 'type',
                    'filter'    => ["Покупка", 'Продажа'],
                    "value"     => "typeName",
                ],
                'sum',
                //				'products',
                //'updated_at',
                [
                    'attribute' => 'created_at',
                    'filter'    => DatePicker::widget([
                        'model'         => $searchModel,
                        'attribute'     => 'created_at',
                        'type'          => DatePicker::TYPE_INPUT,
                        'removeButton'  => false,
                        'value'         => '',
                        'language'      => 'ru',
                        'pluginOptions' => [
                            'format'    => 'yyyy-m-dd',
                            'autoclose' => true,
                        ],
                    ]),
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

</div>
