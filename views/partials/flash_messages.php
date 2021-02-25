<?php if ($app->session->isFlashy()): ?>
    <?php foreach ($app->session->getFlashMessages() as $flashMessage): ?>
        <div class="
            container-fluid
            text-white
            bg-<?= $flashMessage['bootstrapColor'] ?>
            font-weight-bold
            text-center
            ">
            <?= $flashMessage['message'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>