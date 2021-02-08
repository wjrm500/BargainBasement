<div class="product-widget">
    <div class="product-widget-row product-widget-image">
        <img src="/images/<?= $product->image ?>">
    </div>
    <div class="product-widget-row product-widget-name">
        <?= $product->name ?>
    </div>
    <div class="product-widget-row product-widget-detail">
        <div class="product-widget-component">
            £<?= number_format($product->price, 2, '.', '') ?>
        </div>
        <div class="product-widget-component">
            <?= $product->weight ?>g
        </div>
        <div class="product-widget-component">
            £<?= number_format($product->getPricePerKg(), 2, '.', '') ?> / kg
        </div>
    </div>
    <div class="product-widget-row product-widget-add">
        <div class="product-widget-add-component">
            <input type="number" class="form-control">
        </div>
        <div class="product-widget-add-component">
            <button data-product-id="<?= $product->id ?>" class="product-widget-add-button">
                Add
            </button>
        </div>
    </div>
</div>