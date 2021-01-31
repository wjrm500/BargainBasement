<?php

use app\core\Application;

?>

<div class="row">
    <div class="col-lg-2 col-6">
        <?php include Application::$root . '/views/admin/partials/admin-navbar.php' ?>
    </div>
    <div class="col-lg-10 col-6">
        <div id="admin-body">
            <?php include Application::$root . '/views/admin/partials/admin-header.php' ?>
            <?php include Application::$root . '/views/admin/partials/admin-table.php' ?>
            <?php include Application::$root . '/views/admin/partials/admin-pagination.php' ?>
        </div>
    </div>
</div>