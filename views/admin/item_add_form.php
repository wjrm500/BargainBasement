<?php

use app\core\form\Form;

?>

<div class="row">
    <div class="col-6">
    <?php $form = new Form($model) ?>
    <?= $form->begin() ?>
    <?php foreach ($model->attributes() as $attribute): ?>
        <?= $form->field($attribute) ?>
    <?php endforeach; ?>
    <?= $form->submit() ?>
    <?= $form->end() ?>
    </div>
</div>
