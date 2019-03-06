<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="box-body">
        <p>
            <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
            <?php print_r(Yii::$app->user->identity->role) ?>

        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'email:email',
                'username',
//                'password',
                'statusName',
                'role',
                //'access_token',
                //'updated_at',
                //'created_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>


</div>
