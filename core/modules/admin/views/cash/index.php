<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\CashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Касса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-index box">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">

		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,
			'columns'      => [
				'id',
				'title',
				'sum',
				'created_at',
			],
		]); ?>
    </div>

</div>
