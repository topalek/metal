<?php


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Создать пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">
    <div class="user-create box">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

