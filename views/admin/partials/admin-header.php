<div id="admin-header">
    <h2>
        <span id="admin-title"><?= $permissionName ?></span>
        <span id="admin-page-type">|| <?= $request->getPathElementCount() > 3 ? ucfirst($request->getSlug()) : 'View' ?></span>
    </h2>
</div>