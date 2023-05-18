<?php

use app\models\tables\Books;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\filters\BooksFilter $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->registerCss(
    '
        .status-filter {
            width: unset;
        }
        ul.pagination li:not(.prev, .next) {
        margin: 0 5px;
    }'
);

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">
    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'title',
//            'isbn',
//            'pageCount',
            //'thumbnailUrl',
            //'shortDescription:ntext',
            //'longDescription:ntext',
            [
                'attribute' => 'status',
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\tables\Books::find()->select('status')->all(), 'status', 'status'),
                'filterInputOptions' => ['class' => 'form-control status-filter'],
            ],
            [
                'attribute' => 'authors',
                'label' => 'Authors',
                'value' => function($model){
                    if(!empty($model->authors)) {
                        $authors = '';
                        for ($i=0; $i<count($model->authors); $i++) {
                            if ($i !== count($model->authors)-1) {
                                $authors .= $model->authors[$i]->name . ', ';
                            } else {
                                $authors .= $model->authors[$i]->name;
                            }
                        }
                        return $authors;
                    } else {
                        return '';
                    }
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Books $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
