<div id="admin-table-pagination">
    <span>Pages: </span>
    <span>
        <input class="text-center" type="text" placeholder="Go direct">
    </span>
    <span>
        <span id="backward-pagination-icons">
            <button id="pagination-back-all" class="rounded btn-light">
                <i class="fas fa-fast-forward"></i>
            </button>
            <button id="pagination-back-one" class="rounded btn-light">
                <i class="fas fa-arrow-right"></i>
            </button>
        </span>
        <span id="forward-pagination-icons">
            <button id="pagination-forward-one" class="rounded btn-light">
                <i class="fas fa-arrow-right"></i>
            </button>
            <button id="pagination-forward-all" class="rounded btn-light">
                <i class="fas fa-fast-forward"></i>
            </button>
        </span>
    </span>
    <span id="admin-table-page-buttons">
        <?php foreach (array_keys(array_chunk($items, 10)) as $key): ?>
            <button class="rounded btn-light admin-table-page-button" data-selected="<?= $key === 0 ? 'true' : 'false' ?>" data-page-num="<?= $key ?>">
                <?= $key + 1 ?>
            </button>
        <?php endforeach; ?>
    </span>
</div>