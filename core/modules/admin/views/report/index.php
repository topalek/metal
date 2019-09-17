<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title                   = 'Отчеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="report-index box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <p>
                        <?= Html::a('Отчет за день', ['report/period', 'day' => 1], ['class' => 'btn btn-success report']) ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <?= Html::beginForm(['period']) ?>
                <div class="col-md-6">
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
            </div>
            <div class="row">
                <div class="col-md-6">
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
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= Html::a('Отчет за период', ['report/period'], ['class' => 'btn btn-info report', 'id' => 'period']) ?>

                </div>
                <?= Html::endForm() ?>
            </div>

        </div>


    </div>
</div>
<div class="loader" style="display: none">
    <div class="pills">
        <div class="dash uno"></div>
        <div class="dash dos"></div>
        <div class="dash tres"></div>
        <div class="dash cuatro"></div>
    </div>
    <div class="shade"></div>
</div>
<?php
$this->registerJs(<<<JS
$('.report').on('click',e=>{
    e.preventDefault();
    let btn = $(e.target),
    url = btn.attr('href'),
    data = $('form').serialize();
    $('.loader').fadeIn();
    $.post(url,data,resp=>{console.log(resp);})
});
JS
) ?>

