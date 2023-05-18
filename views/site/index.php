<?php

/** @var yii\web\View $this */
$this->registerCssFile('/css/main-page.css');
$this->registerJsFile('/js/main-page.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->title = 'Books';
?>
<div class="site-index">
    <ul class="books-categories">
        <?php foreach ($categories as $category) {
            echo '<li>' . \yii\helpers\Html::a($category->name, ['/', 'category_id' => $category->id], ['class' => 'category_link']) . '</li>';
        } ?>
    </ul>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '<div class="list-items">{items}</div><div class="pager">{pager}</div>',
        'itemView' => function($model) {
            return $this->render('book-preview', [
                    'model' => $model
            ]);
        },
        'summary' => false,
        'options' => [
            'tag' => 'div',
            'class' => 'preview-container'
        ]
    ])?>
</div>
