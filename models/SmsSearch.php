<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sms;

/**
 * SmsSearch represents the model behind the search form of `app\models\Sms`.
 */
class SmsSearch extends Sms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','sms_count'], 'integer'],
            [['message_id', 'message', 'phone_number', 'status', 'status_date', 'created_at', 'updated_at', 'user_sms_id'], 'safe'],
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
        $query = Sms::find()->orderBy(['id'=>SORT_DESC]);

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
            'id' => $this->id,
            'user_sms_id' => $this->user_sms_id,
            'sms_count' => $this->sms_count,
            'status_date' => $this->status_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'message_id', $this->message_id])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
