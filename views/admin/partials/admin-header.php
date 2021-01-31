<div id="admin-header">
    <h2>
        <span id="admin-title"><?= $title ?></span>
        <span id="admin-page-type">\\ View</span>
    </h2>
    <div class="row">
        <div class="col-5">
            <input class="form-control admin-header-item" type="text" placeholder="Search <?= lcfirst($title) ?>...">
        </div>
        <div class="col-3">
            <div class="admin-header-labelled-group">
                <label for="filter">Filter by...</label>
                <select class="form-control admin-header-item" name="filter"></select>
            </div>
        </div>
        <div class="col-3">
            <div class="admin-header-labelled-group">
                <label for="sort">Sort by...</label>
                <select class="form-control admin-header-item" name="sort"></select>
            </div>
        </div>
        <div class="col-1">
            <div class="btn btn-success admin-header-item">
                Add
            </div>
        </div>
    </div>
</div>