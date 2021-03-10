<div id="products" data-product-data=<?= $productData ?>>
    <table id="products-table">
        <?php foreach (array_chunk($productWidgets, 5) as $productWidgetRow): ?>
            <tr class="product-row">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <td><?= $productWidgetRow[$i] ?? '' ?></td>
                <?php endfor; ?>
                </tr>
        <?php endforeach; ?>
    </table>
</div>