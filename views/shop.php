<div id="shop" class="px-5 mt-2">
    <div class="row">
        <div class="col-8">
            <div id="products">
                <table id="products-table">
                    <?php foreach (array_chunk($productWidgets, 4) as $productWidgetRow): ?>
                        <tr class="product-row">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <td><?= $productWidgetRow[$i] ?? '' ?></td>
                            <?php endfor; ?>
                            </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div class="col-4">
            <div id="basket">
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
</div>