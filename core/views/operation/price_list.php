<?php

/**
 * Created by topalek
 * Date: 09.04.2019
 * Time: 15:35
 *
 * @var $this      yii\web\View
 * @var $products  array
 *
 */

use yii\helpers\Html;

$isAdmin = Yii::$app->user->can('canAdmin');
?>
<ul class="list-group">
    <?php foreach ($products as $product) : ?>
        <?php if ($isAdmin): ?>
            <li class="list-group-item"><?= $product['title'] ?>
                <?php if ($product['discount_price']): ?>
                    <span class="badge"><?= $product['discount_price'] ?> грн.</span>
                <?php else: ?>
                    <span class="badge"><?= $product['price'] ?> грн.</span>
                <?php endif; ?>
                <?= Html::a(' <i class="fa fa-pencil-square-o"></i>', ['/admin/product/update', 'id' => $product['id']]) ?>
            </li>

        <?php else: ?>
            <li class="list-group-item"><?= $product['title'] ?>
                <?php if ($product['discount_price']): ?>
                    <span class="badge"><?= $product['discount_price'] ?> грн.</span>
                <?php else: ?>
                    <span class="badge"><?= $product['price'] ?> грн.</span>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

