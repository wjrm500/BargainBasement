<div id="admin-nav">
    <button class="btn btn-dark text-warning">
        Admin
    </button>
    <ul id="admin-nav-list">
    <?php foreach($app->getUser()->getPermissions() as $permission): ?>
        <li>
            <a class="btn" href="<?= $permission->href ?>"><?= $permission->name ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>