<?php

use app\core\form\Form;

?>

<?php $form = new Form($model) ?>
<?= $form->begin() ?>
<?php foreach ($model->attributes() as $attribute): ?>
    <?= $form->field($attribute) ?>
<?php endforeach; ?>
<?= $form->end() ?>