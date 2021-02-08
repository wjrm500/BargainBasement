<div class="container">
    <div class="row">
        <div id="products" class="col-8">
            <?php foreach (array_chunk($productWidgets, 4) as $productWidgetRow): ?>
                <div class="product-row">
                    <?php foreach ($productWidgetRow as $productWidget): ?>
                        <?= $productWidget ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="basket" class="col-4">
            <div>My Basket</div>
            <div id="basket-items"></div>
            <div class="row">
                <div id="basket-price" class="col-6"></div>
                <div class="col-6">
                    <button class="btn btn-success">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>