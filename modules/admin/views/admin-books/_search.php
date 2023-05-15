<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\filters\BooksFilter $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="books-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'isbn') ?>

    <?= $form->field($model, 'pageCount') ?>

    <?= $form->field($model, 'publishedDate') ?>

    <?php // echo $form->field($model, 'thumbnailUrl') ?>

    <?php // echo $form->field($model, 'shortDescription') ?>

    <?php // echo $form->field($model, 'longDescription') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
