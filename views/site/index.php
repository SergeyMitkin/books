<?php

/** @var yii\web\View $this */
$this->registerCssFile('/css/main-page.css');
$this->title = 'Books';
?>
<div class="site-index">
    <!-- Для широкого экрана -->
    <ul class="books-categories">
        <li><h5>Категории</h5></li>
        <li><?= \yii\helpers\Html::a('Все категории', ['/'], ['class' => 'category_link'])?></li>
        <?php foreach ($categories as $category) {
            echo '<li>' . \yii\helpers\Html::a($category->name, ['/', 'category_id' => $category->id], ['class' => 'category_link']) . '</li>';
        } ?>
    </ul>

    <!-- Для узкого экрана -->
    <div class="categories-dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Категории
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><?= \yii\helpers\Html::a('Все категории', ['/'], ['class' => 'category_link'])?></li>
            <?php foreach ($categories as $category) {
                echo '<li>' . \yii\helpers\Html::a($category->name, ['/', 'category_id' => $category->id], ['class' => 'category_link']) . '</li>';
            } ?>
        </ul>
    </div>

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
