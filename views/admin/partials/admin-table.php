<div id="admin-table-pages">
    <?php foreach (array_chunk($items, 10) as $pageNum => $paginatedItems): ?>
        <table class="table <?= $pageNum !== 0 ? 'd-none' : '' ?> admin-table-page" data-selected="<?= $pageNum === 0 ? 'true' : 'false' ?>" data-page-num="<?= $pageNum ?>">
            <tr class="d-flex">
                <?php foreach ($itemAttributes as $itemAttribute): ?>
                    <th><?= ucfirst($itemAttribute) ?></th>
                <?php endforeach; ?>
                <th class="col-1"></th>
                <th class="col-1"></th>
            </tr>
            <?php foreach ($paginatedItems as $item): ?>
                <tr class="d-flex">
                    <?php foreach ($item::attributes() as $attribute): ?>
                        <td><?= $item->{$attribute} ?></td>
                    <?php endforeach; ?>
                    <td class="col-1">
                        <a class="btn btn-primary" href="/admin/product/<?= $item->id ?>/edit">
                            <span class="text">Edit</span>
                            <span class="icon"><i class="fas fa-edit p-1"></i></span>
                        </a>
                    </td>
                    <td class="col-1">
                        <a class="btn btn-danger" href="/admin/product/<?= $item->id ?>/delete">
                            <span class="text">Delete</span>
                            <span class="icon"><i class="fas fa-trash-alt p-1"></i></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</div>