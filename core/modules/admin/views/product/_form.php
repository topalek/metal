<?php

use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<?php //dd($model->attributes)?>
<div class="product-form box-body">
	<?= $model->img ?>
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <div class="col-md-2">
		<?= $form->field($model, 'price')->textInput() ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'dirt')->input('number') ?>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-7">
				<?= $form->field($model, 'amount_for_discount')->textInput() ?>
            </div>
            <div class="col-md-5">
				<?= $form->field($model, 'discount_price')->textInput() ?>
            </div>
        </div>
    </div>
    <div class="col-md-2">
		<?= $form->field($model, 'status')->widget(SwitchInput::class, [
			'pluginOptions' => [
				'onText'   => 'Да',
				'offText'  => 'Нет',
				'offColor' => 'danger',
			]
		]) ?>
    </div>
    <div class="col-md-2">
		<?= $form->field($model, 'sell_only')->widget(SwitchInput::class, [
			'pluginOptions' => [
				'onText'   => 'Да',
				'offText'  => 'Нет',
				'offColor' => 'danger',
			],
		]) ?>
    </div>

	<?= $form->field($model, 'file')->widget(\kartik\file\FileInput::class, [
		'language'      => 'ru',
		'pluginOptions' => [
			'showCaption'    => false,
			'showRemove'     => false,
			'showUpload'     => false,
			'browseClass'    => 'btn btn-primary btn-block',
			'browseIcon'     => '<i class="glyphicon glyphicon-camera"></i> ',
			'browseLabel'    => 'Выберите картинку',
			'initialPreview' => $model->image ? [$model->getImg()] : [],
		],
		'options'       => ['accept' => 'image/*']

	]) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Создать новый', ['create'], ['class' => 'btn btn-primary']) ?>

    </div>

	<?php ActiveForm::end(); ?>

</div>
