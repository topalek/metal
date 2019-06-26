<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form of `app\modules\admin\models\Product`.
 */
class ProductSearch extends Product {

    public $type;

	public function rules(){
		return [
            [['id', 'status', 'sell_only'], 'integer'],
			[['title', 'slug', 'image', 'updated_at', 'created_at'], 'safe'],
			[['price'], 'number'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios(){
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
	public function search($params){
		$query = Product::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => 30,
			],
		]);

		$this->load($params);

		if ( ! $this->validate()){
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
        if ($this->type == Operation::TYPE_BUY) {
            $query->andWhere(['sell_only' => Operation::TYPE_BUY, 'status' => Product::STATUS_PUBLISHED]);
            $query->orderBy('operation_sort');
        } else {
            $query->andWhere([
                'sell_only' => [Operation::TYPE_SELL, Operation::TYPE_BUY], 'status' => Product::STATUS_PUBLISHED
            ]);
        }
		// grid filtering conditions
		$query->andFilterWhere([
			'id'         => $this->id,
			'price'      => $this->price,
			'image'      => $this->image,
			'status'     => $this->status,
			'updated_at' => $this->updated_at,
			'created_at' => $this->created_at,
		]);

		$query->andFilterWhere(['like', 'title', $this->title])
		      ->andFilterWhere(['like', 'slug', $this->slug]);

		//->andFilterWhere(['like', 'image', $this->image])

		return $dataProvider;
	}
}
