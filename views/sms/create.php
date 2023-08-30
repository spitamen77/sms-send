<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sms $model */

$this->title = 'SMS jo\'natish';
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Ortga', ['index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
