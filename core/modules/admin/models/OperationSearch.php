<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OperationSearch represents the model behind the search form of `app\modules\admin\models\Operation`.
 */
class OperationSearch extends Operation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status'], 'integer'],
            [['sum'], 'number'],
            [['updated_at', 'created_at'], 'safe'],
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
        $query = Operation::find()->orderBy('id DESC');

// add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to return any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

// grid filtering conditions
        $query->andFilterWhere([
	        'id'         => $this->id,
	        'type'       => $this->type,
	        //'typeName'   => $this->type,
	        'sum'        => $this->sum,
	        'status'     => $this->status,
	        'updated_at' => $this->updated_at,
	        'created_at' => $this->created_at,
        ]);
	    //if ($this->created_at){
	    //    $query->andFilterWhere(['between', 'created_at', $this->created_at." 00:00:00", date('Y-m-d', strtotime($this->created_at." 00:00:00" . '+1 day'))]);
	    //}


        return $dataProvider;
    }
}
