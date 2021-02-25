<div id="admin-initial-menu">
    <ul>
        <?php foreach($app->getUser()->getPermissions() as $permission): ?>
            <li>
                <a class="btn m-2 btn-secondary" href="<?= $permission->href ?>"><?= $permission->name ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>