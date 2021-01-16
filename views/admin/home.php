<div id="admin" class="bg-white container rounded mt-2">
    <div class="row h3 py-2 px-5 bg-light rounded-top">Admin</div>
    <?php foreach($adminPermissions as $adminPermission): ?>
        <div class="row px-5">
            <a class="btn" style="text-align: left" href="<?= $adminPermission->href ?>"><?= $adminPermission->name ?></a>
        </div>
    <?php endforeach; ?>
</div>