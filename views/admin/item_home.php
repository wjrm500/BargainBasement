<?php

use app\core\Application;

?>

<div id="admin-table">
    <?php include Application::$root . '/views/admin/partials/admin-table/admin-table-header.php' ?>
    <?php include Application::$root . '/views/admin/partials/admin-table/admin-table-pages.php' ?>
    <?php include Application::$root . '/views/admin/partials/admin-table/admin-table-pagination.php' ?>
</div>