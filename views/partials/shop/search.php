<div id="search" class="row">
    <div class="search-group col-md-6 col-12">
        <input id="search-shop" type="text" class="form-control" name="search" placeholder="Search for products">
    </div>
    <div class="search-group col-md-6 col-12">
        <select id="category-filter" class="form-control" name="category-filter">
            <option value="" disabled selected>Or filter by category</option>
            <option value="all">All categories</option>
            <?php foreach ($productCategories as $productCategory): ?>
                <option value="<?= $productCategory->id ?>"><?= $productCategory->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>