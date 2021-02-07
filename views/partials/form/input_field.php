<?php

use app\core\form\FileInputField;
use app\core\form\FloatInputField;

?>

<input
class="form-control <?= $isInvalid ?>"
name="<?= $name ?>"
type="<?= $type ?>"
value="<?= $value ?>"
<?= $field instanceof FileInputField ? 'access="image/jpeg, image/png"' : '' ?>
<?= $field instanceof FloatInputField ? 'step="0.01"' : '' ?>
>