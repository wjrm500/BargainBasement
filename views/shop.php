<?php

use app\core\Application;

?>

<div id="shop" class="px-5 mt-2">
    <div class="row">
        <div class="col-8">
            <?php include Application::$root . '/views/partials/shop/products.php' ?>
        </div>
        <div class="col-4">
        <?php include Application::$root . '/views/partials/shop/basket.php' ?>
        </div>
    </div>
</div>