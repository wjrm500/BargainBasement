<div class="container">
    <table class="table" id="admin-table">
        <tr class="d-flex">
            <?php foreach ($productAttributes as $productAttribute): ?>
                <th><?= ucfirst($productAttribute) ?></th>
            <?php endforeach; ?>
            <th class="col-1"></th>
            <th class="col-1"></th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr class="d-flex">
                <?php foreach ($product::attributes() as $attribute): ?>
                    <td><?= $product->{$attribute} ?></td>
                <?php endforeach; ?>
                <td class="col-1">
                    <a class="btn btn-primary" href="/admin/product/<?= $product->id ?>/edit">
                        <span class="text">Edit</span>
                        <span class="icon"><i class="fas fa-edit p-1"></i></span>
                    </a>
                </td>
                <td class="col-1">
                    <a class="btn btn-danger" href="/admin/product/<?= $product->id ?>/delete">
                        <span class="text">Delete</span>
                        <span class="icon"><i class="fas fa-trash-alt p-1"></i></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>