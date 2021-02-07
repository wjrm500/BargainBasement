<?php

use app\models\Permission;

$permission = Permission::find(['name' => $permissionName]);

?>

<div id="admin-header">
    <div>
        <h2>
            <span id="admin-title"><?= $permissionName ?></span>
            <span id="admin-page-type">|| <?= $request->getPathElementCount() > 3 ? ucfirst($request->getSlug()) : 'View' ?></span>
        </h2>
    </div>
    <div>
        <?php if ($request->getSlug() === 'edit'): ?>
            <a href="<?= $permission->href . '/' . $model->id ?>/delete" class="btn btn-danger admin-table-header-item">
                Delete <?= $permission->item_name ?>
            </a>
            <a href="<?= $permission->href ?>" class="btn btn-secondary admin-table-header-item">
                Back to <?= $permissionName ?>
            </a>
        <?php else: ?>
            <a href="<?= $permission->href ?>/add" class="btn btn-success admin-table-header-item">
                Add new <?= $permission->item_name ?>
            </a>
        <?php endif; ?>
    </div>
</div>