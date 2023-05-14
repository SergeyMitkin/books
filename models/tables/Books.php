<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $isbn
 * @property int|null $pageCount
 * @property string|null $publishedDate
 * @property string|null $thumbnailUrl
 * @property string|null $status
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageCount'], 'integer'],
            [['title', 'isbn', 'publishedDate', 'thumbnailUrl', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'isbn' => 'Isbn',
            'pageCount' => 'Page Count',
            'publishedDate' => 'Published Date',
            'thumbnailUrl' => 'Thumbnail Url',
            'status' => 'Status',
        ];
    }
}
