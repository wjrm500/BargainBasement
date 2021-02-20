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