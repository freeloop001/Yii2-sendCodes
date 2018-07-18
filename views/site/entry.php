<?php
/**
 * User: cy
 * Date: 2018/7/15
 * Time: 18:27
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->label('输入姓名') ?>

<?= $form->field($model, 'email')->label('输入邮箱') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>