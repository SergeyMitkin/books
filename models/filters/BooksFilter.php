<?php

namespace app\models\filters;

use app\models\tables\Authors;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\tables\Books;

/**
 * BooksFilter represents the model behind the search form of `app\models\tables\Books`.
 */
class BooksFilter extends Books
{
    public $authors;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pageCount'], 'integer'],
            [['title', 'isbn', 'publishedDate', 'thumbnailUrl', 'shortDescription', 'longDescription', 'status', 'authors'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Books::find();
        $query->joinWith(['authors']);
        $query->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['authors'] = [
            'asc' => [Authors::tableName().'.name' => SORT_ASC],
            'desc' => [Authors::tableName().'.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'pageCount' => $this->pageCount,
//        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
//            ->andFilterWhere(['like', 'isbn', $this->isbn])
//            ->andFilterWhere(['like', 'publishedDate', $this->publishedDate])
//            ->andFilterWhere(['like', 'thumbnailUrl', $this->thumbnailUrl])
//            ->andFilterWhere(['like', 'shortDescription', $this->shortDescription])
//            ->andFilterWhere(['like', 'longDescription', $this->longDescription])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['like', 'authors.name', $this->authors])
        ;

        return $dataProvider;
    }
}
