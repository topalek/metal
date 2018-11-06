<?php

use app\modules\admin\models\Operation;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;

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
            <?= Html::a('Создать Операцию', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= date('Y-m-d', strtotime('2018-11-05 16:58:38' . '-1 day')) ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'rowOptions'   => function ($model) {
                return $model->type == Operation::TYPE_BUY ? ['class' => 'danger'] : ['class' => 'info'];
            },
            'columns'      => [

                'id',
                'typeName',
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
