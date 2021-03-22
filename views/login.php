<div class="container mt-5">
    <div class="row">
        <form class="col-8 offset-2 col-md-6 offset-md-3 bg-light rounded border border-primary p-2" method="post">
            <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="username">Username</label>
                <div class="col-12 col-md-8">
                    <input class="form-control <?= $loginForm->hasError('username') ? 'is-invalid' : '' ?>" type="text" name="username" value="<?= $loginForm->username ?>">
                    <div class="text-danger"><?= $loginForm->getFirstError('username') ?></div>
                </div>
            </div>
            <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="password">Password</label>
                <div class="col-12 col-md-8">
                    <input class="form-control <?= $loginForm->hasError('password') ? 'is-invalid' : '' ?>" type="password" name="password" autocomplete="new-password">
                    <div class="text-danger"><?= $loginForm->getFirstError('password') ?></div>
                </div>
            </div>
            <div class="form-group p-2">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </form>
        <div class="col-6 offset-3 d-flex flex-column align-items-center">
            <label class="text-center font-weight-bold mb-2">Don't have an account?</label>
            <a href="<?= $app->request->isRedirected() ? $app->response->getForwardedRedirectUrl($app->request, '/register') : '/register' ?>" class="btn btn-success">Register here</a>
        </div>
    </div>
</div>