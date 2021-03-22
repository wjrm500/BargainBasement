<div class="container mt-5">
    <div class="row">
        <form class="col-8 offset-2 col-md-6 offset-md-3 bg-light rounded border border-primary p-2" method="post">
        <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="country_id">Country</label>
                <div class="col-12 col-md-8">
                    <select class="form-control <?= $registerForm->hasError('country_id') ? 'is-invalid' : '' ?>" name="country_id">
                    <?php foreach($countries as $country): ?>
                        <option value="<?= $country->id ?>" <?= $country->name === 'England' ? 'selected' : '' ?>><?= $country->name ?></option>
                    <?php endforeach; ?>
                    </select>
                    <div class="text-danger"><?= $registerForm->getFirstError('country_id') ?></div>
                </div>
            </div>
            <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="username">Username</label>
                <div class="col-12 col-md-8">
                    <input class="form-control <?= $registerForm->hasError('username') ? 'is-invalid' : '' ?>" type="text" name="username" value="<?= $registerForm->username ?>">
                    <div class="text-danger"><?= $registerForm->getFirstError('username') ?></div>
                </div>
            </div>
            <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="password">Password</label>
                <div class="col-12 col-md-8">
                    <input class="form-control <?= $registerForm->hasError('password') ? 'is-invalid' : '' ?>" type="password" name="password">
                    <div class="text-danger"><?= $registerForm->getFirstError('password') ?></div>
                </div>
            </div>
            <div class="form-group row p-2">
                <label class="col-12 col-md-4 col-form-label" for="confirmPassword">Confirm Password</label>
                <div class="col-12 col-md-8">
                    <input class="form-control <?= $registerForm->hasError('confirmPassword') ? 'is-invalid' : '' ?>" type="password" name="confirmPassword">
                    <div class="text-danger"><?= $registerForm->getFirstError('confirmPassword') ?></div>
                </div>
            </div>
            <div class="form-group p-2">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </form>
    </div>
</div>