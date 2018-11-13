<?php

use app\modules\admin\models\Cash;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$cas = Cash::find()->orderBy('id DESC')->one();
if ($cas){
	$cas = $cas->sum;
}else{
	$cas = 0;
}
?>

<header class="main-header">

	<?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="messages-menu">
		            <?= Html::a('<i class="fa fa-calculator"></i> ' . $cas, ['cash/index']) ?>

                </li>
            </ul>
        </div>
    </nav>
</header>
