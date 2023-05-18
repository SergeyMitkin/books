<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\tables\Books $model */

\yii\web\YiiAsset::register($this);
?>
<div class="book-preview">
    <h5 class="book-title"><?=$model->title?></h5>
    <div class="book-cover">
        <img src="<?=$model->thumbnailUrl?>" alt="cover">
    </div>
    <div class="book-info">
        <p class="book-authors"><?=$model->authorsToString()?></p>
        <p class="book-short-description"><?=$model->getCroppedDescription()?></p>
    </div>
</div>
