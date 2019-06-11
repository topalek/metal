<?php

/* @var $this yii\web\View */

$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;


?>
<div class="site-about">
    <h1>Тест полей</h1>
    <div class="col-md-3">
        <div class="form-group">
            <?= Html::input('text', '', '', ['placeholder' => 'text', 'class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::input('number', '', '', ['placeholder' => 'number', 'class' => 'form-control']) ?>
        </div>
        <pre>
            <?php

            //print_r($col);
            ?>
        </pre>

    </div>
    <pre>
<?php print_r('$headers') ?>
    </pre>
</div>
