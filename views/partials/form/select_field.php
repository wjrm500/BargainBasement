<select
class="form-control <?= $isInvalid ?>"
name="<?= $name ?>[]"
<?php foreach ($extraProperties as $key => $value): ?>
    <?php if ($key !== 'options'): ?>
        <?= sprintf('%s="%s"', $key, $value) ?>
    <?php endif; ?>
<?php endforeach; ?>
>
    <?php foreach ($extraProperties['options'] as $value => $name): ?>
        <option value="<?= $value ?>"><?= $name ?></option>
    <?php endforeach; ?>
</select>