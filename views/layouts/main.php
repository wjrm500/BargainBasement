<?php

use app\core\Application;

$session = Application::$app->session;

?>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/3cd62817c9.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
    <script src="/js/script.js"></script>
    <script src="/js/app.js"></script>
</head>
</head>
<body>
<?php if ($session->isFlashy()): ?>
    <?php foreach ($session->getFlashMessages() as $flashMessage): ?>
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
<?php include __DIR__ . '/../partials/navbar.php'; ?>
    {{ content }}
</body>