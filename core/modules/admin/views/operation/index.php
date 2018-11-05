<?php

use app\modules\admin\models\Operation;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OperationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index box">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">
        <p>
			<?= Html::a('Создать Операцию', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

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
				'created_at',

				['class' => 'yii\grid\ActionColumn'],
			],
		]); ?>
    </div>

</div>
