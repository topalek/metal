<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\modules\admin\models\Cash;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
rmrevin\yii\fontawesome\AssetBundle::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
        'brandLabel' => "<span class='date'></span> | <span class='time'></span>",
        'brandUrl'   => Yii::$app->homeUrl,
        'options'    => [
	        'class' => 'navbar navbar-fixed-top navbar-default',
		],
	]);
	$cas = Cash::find()->orderBy('id DESC')->one();
	if ($cas){
		$cas = $cas->sum;
	}else{
		$cas = 0;
	}
	echo Nav::widget([
		'options'      => ['class' => 'navbar-nav navbar-right'],
		'encodeLabels' => false,
		'items'        => [
//			['label' => 'Home', 'url' => ['/site/index']],
//			['label' => 'About', 'url' => ['/site/about']],
['label' => '<i class="fa fa-calculator"></i> ' . $cas, 'url' => ['/site/contact']],
Yii::$app->user->isGuest ? (
			['label' => 'Login', 'url' => ['/site/login']]
			) : (
            ['label' => 'ВЫХОД', 'url' => ['/site/logout']]
			)
		],
	]);
	NavBar::end();
	?>

    <div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left" style="font-weight: bold;">&copy; {<span style="color: red;">RED</span>} October
            [<?= date('Y') ?>]</p>

        <!--        <p class="pull-right">--><? //= Yii::powered() ?><!--</p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
