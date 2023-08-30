<?php

use app\helpers\Helpers;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sms $model */

$this->title = $model->phone_number;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sms-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'message_id',
            'user_sms_id',
            'message',
            'phone_number',
            'sms_count',
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
            ],

            'status_date',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
