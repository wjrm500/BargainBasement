<?php

use app\models\Permission;

?>

<div id="admin-table-header" class="row">
    <div class="col-5">
        <input class="form-control admin-table-header-item" type="text" placeholder="Search <?= lcfirst($permissionName) ?>...">
    </div>
    <div class="col-3">
        <div class="admin-table-header-labelled-group">
            <label for="filter">Filter by...</label>
            <select class="form-control admin-table-header-item" name="filter"></select>
        </div>
    </div>
    <div class="col-3">
        <div class="admin-table-header-labelled-group">
            <label for="sort">Sort by...</label>
            <select class="form-control admin-table-header-item" name="sort"></select>
        </div>
    </div>
    <div class="col-1">
        <a href="<?= Permission::find(['name' => $permissionName])->href ?>/add" class="btn btn-success admin-table-header-item">
            Add
        </a>
    </div>
</div>