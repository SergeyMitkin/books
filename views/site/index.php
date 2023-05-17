<?php

/** @var yii\web\View $this */
$this->registerCssFile('/css/main-page.css');
$this->title = 'Books';
?>
<div class="site-index">
    <div class="body-content">

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown button
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
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
</div>
