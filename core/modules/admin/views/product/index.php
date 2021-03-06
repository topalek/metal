<?php

use app\modules\admin\models\Product;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Product */

$this->title                   = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index box">
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">
        <p>
			<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,
			'columns'      => [

				'id',
				'title',
				'price',
				//'slug',
				'imgUrl:image',
				[
					'attribute' => 'status',
					'filter'    => ['Не опубликован', 'Опублинован'],
					'value'     => function ($model){
						return $model->status == $model::STATUS_PUBLISHED ? 'Опублинован' : 'Не опубликован';
					}
				],
				//'updated_at',
				//'created_at',

				['class' => 'yii\grid\ActionColumn'],
			],
		]); ?>
    </div>

</div>
