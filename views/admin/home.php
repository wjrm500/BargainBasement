<?php foreach($permissions as $permission): ?>
    <div class="row px-5">
        <a class="btn" style="text-align: left" href="<?= $permission->href ?>"><?= $permission->name ?></a>
    </div>
<?php endforeach; ?>