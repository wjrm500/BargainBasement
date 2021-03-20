<div id="search" class="row">
    <div class="search-group col-md-6 col-12">
        <label>
            Search our range:
        </label>
        <input id="search-shop" type="text" class="form-control" name="search" placeholder="Enter product name here...">
    </div>
    <div class="search-group col-md-6 col-12">
        <label>
            Or filter by category:
        </label>
        <select id="category-filter" class="form-control" name="category-filter">
            <option value="all" selected>All categories</option>
            <?php foreach ($productCategories as $productCategory): ?>
                <option value="<?= $productCategory->id ?>"><?= $productCategory->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>