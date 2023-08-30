<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Sms $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="container">
<div class="row">
    <div class="col-md-8">
        <?php $form = ActiveForm::begin(); ?>


        <?= $form->field($model, 'message')->textarea(['rows'=>2, 'placeholder'=>'Yuboriladigan matnni yozing']) ?>

        <?= $form->field($model, 'phone_number')->textarea(['rows'=>5, 'placeholder'=>'998977740369,998901235080,998941357648']) ?>


        <div class="form-group">
            <?= Html::submitButton('Jo\'natish', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


</div>
