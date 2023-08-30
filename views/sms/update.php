<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sms $model */

$this->title = 'Tahrirlash Sms: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sms-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Ortga', ['index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
