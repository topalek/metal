<?php

use yii\helpers\Html;

/*
 * @var $this yii\web\View
 * @var $operations app\modules\admin\models\Operation[]
 * @var $date string
 */

$this->title = 'Отчет за ' . $date;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">
    <div class="report-day box">
        <div class="box-body">
            <?php if ($operations) : ?>
                <pre>
            <?php print_r([$operations]) ?>
        </pre>
            <?php else: ?>
                <h2>
                    Операций за <?= $date ?> не найдено.
                </h2>
                <p>
                    <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>

                </p>
            <?php endif; ?>
        </div>


    </div>
</div>

