<div id="admin-table-pages">
    <?php foreach (array_chunk($searchItems ?? $items, 10) as $pageNum => $paginatedItems): ?>
        <table class="table <?= $pageNum !== 0 ? 'd-none' : '' ?> admin-table-page" data-selected="<?= $pageNum === 0 ? 'true' : 'false' ?>" data-page-num="<?= $pageNum ?>">
            <tr class="d-flex">
                <?php foreach ($itemAttributes as $itemAttribute): ?>
                    <?php if (!in_array($itemAttribute, ['energy_kcal', 'fat_g', 'saturates_g', 'sugars_g', 'salt_g'])): ?>
                        <th><?= ucfirst($itemAttribute) ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th class="col-1"></th>
                <th class="col-1"></th>
            </tr>
            <?php foreach ($paginatedItems as $item): ?>
                <tr class="d-flex">
                    <?php foreach ($item::attributes() as $attribute): ?>
                        <?php if (!in_array($attribute, ['energy_kcal', 'fat_g', 'saturates_g', 'sugars_g', 'salt_g'])): ?>
                            <td>
                                <?php

                                    if ($attribute === 'price') {
                                        echo '£' . (string) number_format($item->{$attribute}, 2, '.', '');
                                    } elseif ($attribute === 'weight') {
                                        echo $item->{$attribute} . ' g';
                                    } else {
                                        echo $item->{$attribute};
                                    }

                                ?>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td class="col-1">
                        <a class="btn btn-primary" href="<?= $app->request->getPath() . '/' . $item->id ?>/edit">
                            <span class="text">Edit</span>
                            <span class="icon"><i class="fas fa-edit p-1"></i></span>
                        </a>
                    </td>
                    <td class="col-1">
                        <a class="btn btn-danger" href="<?= $app->request->getPath() . '/' . $item->id ?>/delete">
                            <span class="text">Delete</span>
                            <span class="icon"><i class="fas fa-trash-alt p-1"></i></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</div>