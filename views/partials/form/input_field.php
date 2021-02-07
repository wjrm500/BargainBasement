<input
class="form-control <?= $isInvalid ?>"
name="<?= $name ?>"
type="<?= $type ?>"
value="<?= $value ?>"
<?php foreach ($extraProperties as $key => $value): ?>
    <?= sprintf('%s="%s"', $key, $value) ?>
<?php endforeach; ?>
>