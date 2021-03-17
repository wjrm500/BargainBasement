<div class="product-widget" data-product-id="<?= $product->id?>">
    <div class="product-widget-overlay d-none"></div>
    <div class="product-widget-row product-widget-image-container">
        <img src="/images/<?= $product->image ?>" class="product-widget-image">
    </div>
    <div class="product-widget-text-container">
        <div class="product-widget-name-container">
            <div class="product-widget-row product-widget-name">
                <?= $product->name ?>
            </div>
        </div>
        <div class="product-widget-row product-widget-detail">
            <div class="product-widget-component product-widget-price">
                £<?= number_format($product->price, 2, '.', '') ?>
            </div>
            <div class="product-widget-component product-widget-weight">
                <?= $product->weight ?>g
            </div>
        </div>
        <div class="product-widget-row product-widget-add">
            <div class="product-widget-add-component">
                <input type="number" class="product-widget-item-number form-control" value="0" disabled>
            </div>
            <div class="product-widget-add-component product-widget-zero">
                <button class="product-widget-button product-widget-add-button product-widget-zero-add-button">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="product-widget-add-component product-widget-non-zero">
                <button class="widget-button product-widget-non-zero-remove-button">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="widget-button product-widget-add-button product-widget-non-zero-add-button">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>