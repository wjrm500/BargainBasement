<?php

use app\core\Application;

?>

<div class="container border border-dark">
    <div class="row product-widget-image">
        <img src="/images/<?= $product->image ?>">
    </div>
    <div class="row product-widget-name bg-primary">
        <?= $product->name ?>
    </div>
    <div class="row product-widget-details bg-secondary">
        <div class="col-4">
            <?= $product->price ?>
        </div>
        <div class="col-4">
            <?= $product->weight ?>
        </div>
        <div class="col-4">
        </div>
    </div>
    <div class="row product-widget-add bg-secondary">
        <div class="col-6">
            <input type="number">
        </div>
        <div class="col-6">
            <button data-product-id="<?= $product->id ?>" class="btn btn-success product-add">
        </div>
    </div>
</div>