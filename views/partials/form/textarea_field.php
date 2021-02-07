<textarea
class="form-control <?= $isInvalid ?>"
name="<?= $name ?>"
<?php foreach ($extraProperties as $key => $value): ?>
    <?= sprintf('%s="%s"', $key, $value) ?>
<?php endforeach; ?>
>
    <?= $value ?>
</textarea>