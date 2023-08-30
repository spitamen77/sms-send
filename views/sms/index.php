<?php

use app\helpers\Helpers;
use app\models\Sms;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var app\models\SmsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Jo\'natish', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if (Yii::$app->session->hasFlash('success')): ?>

    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            'user_sms_id',
            'message',
            'phone_number',
            //'sms_count',
//            'status',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status == Helpers::DELIVRD){
                        return "<span class='text-success'>".Helpers::STATUS[$model->status]."</span>";
                    } elseif ($model->status != Helpers::WAITING) {
                        return "<span class='text-danger'>".Helpers::STATUS[$model->status]."</span>";
                    } else {
                        return "<span class='text-warning'>".Helpers::STATUS[$model->status]."</span>";
                    }
                },
                'format' => 'html',
                'filter' => Helpers::STATUS,
            ],

            'message_id',
            //'status_date',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Sms $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],

        ],
    ]); ?>


</div>
