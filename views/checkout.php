<div id="shop" class="px-5 mt-2">
    <div class="row">
        <div class="col-12">
            <h1>Checkout</h1>
            <div id="basket-items">
                <?php foreach ($shoppingCart->getItems() as $shoppingCartItem): ?>
                    <?= $shoppingCartItem->name() ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>