<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Введите Ваш логин и пароль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>


	<?php $form = ActiveForm::begin([
		'id'          => 'login-form',
		'layout'      => 'horizontal',
		'fieldConfig' => [
//			'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-1 control-label'],
		],
	]); ?>

	<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

	<?= $form->field($model, 'password')->passwordInput() ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('войти', ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
        </div>
    </div>

	<?php ActiveForm::end(); ?>

</div>
