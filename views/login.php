<div class="container mt-5">
    <div class="row">
        <form class="col-6 offset-3 bg-light rounded border border-primary" method="post">
            <div class="form-group row p-2">
                <label class="col-4 col-form-label" for="username">Username</label>
                <div class="col-8">
                    <input class="form-control <?= $loginForm->hasError('username') ? 'is-invalid' : '' ?>" type="text" name="username" value="<?= $loginForm->username ?>">
                    <div class="text-danger"><?= $loginForm->getFirstError('username') ?></div>
                </div>
            </div>
            <div class="form-group row p-2">
                <label class="col-4 col-form-label" for="password">Password</label>
                <div class="col-8">
                    <input class="form-control <?= $loginForm->hasError('password') ? 'is-invalid' : '' ?>" type="password" name="password" autocomplete="new-password">
                    <div class="text-danger"><?= $loginForm->getFirstError('password') ?></div>
                </div>
            </div>
            <div class="form-group p-2">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </form>
    </div>
</div>