<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Изменить пользователя: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
?>
<div class="col-md-6">
    <div class="user-update box">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>

