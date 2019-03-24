<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Отчеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">
    <div class="report-index box">

        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <p>
                        <?= Html::a('Отчет за день', ['day'], ['class' => 'btn btn-success']) ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <?= Html::beginForm(['period']) ?>
                <div class="col-md-2">
                    <div class="form-group field-user-username">
                        <label class="control-label" for="from_date">Начало периода</label>
                        <?= DatePicker::widget([
                            'name'          => 'from_date',
                            'id'            => 'from_date',
                            'value'         => date('d-m-Y', strtotime('-1 day')),
                            'type'          => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format'    => 'dd-mm-yyyy',
                            ],
                        ]) ?>

                        <div class="help-block"></div>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group field-user-username">
                        <label class="control-label" for="to_date">Конец периода</label>
                        <?= DatePicker::widget([
                            'type'          => DatePicker::TYPE_INPUT,
                            'name'          => 'to_date',
                            'id'            => 'to_date',
                            'value'         => date('d-m-Y'),
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format'    => 'dd-mm-yyyy',
                            ],
                        ]) ?>

                        <div class="help-block"></div>
                    </div>

                </div>
                <div class="col-md-6">
                    <?= Html::submitButton('Отчет за период', ['class' => 'btn btn-info', 'id' => 'period']) ?>

                </div>
                <?= Html::endForm() ?>
            </div>

        </div>


    </div>
</div>

<?php
$css = "
#period{
    position:relative;
    top:25px;
}
";
$this->registerCss($css) ?>

