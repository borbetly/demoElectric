<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-bolt text-warning me-2"></i>Welcome Back</h1>
        <p class="lead mb-0">Log in to access the customer account management system.</p>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h3 text-primary-custom text-center mb-4">Log In</h2>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= esc($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle me-2"></i><?= esc($error) ?></div>
                        <?php endif; ?>

                        <form method="post" action="<?= esc(site_url(route_to('auth.login'))) ?>">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="email">Email Address</label>
                                <input class="form-control form-control-lg <?= isset($validation['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" maxlength="100" required autofocus value="<?= esc(old('email')) ?>">
                                <?php if (isset($validation['email'])): ?><div class="invalid-feedback"><?= esc($validation['email']) ?></div><?php endif; ?>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="password">Password</label>
                                <input class="form-control form-control-lg <?= isset($validation['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" required>
                                <?php if (isset($validation['password'])): ?><div class="invalid-feedback"><?= esc($validation['password']) ?></div><?php endif; ?>
                            </div>
                            <button class="btn btn-primary btn-lg w-100" type="submit"><i class="fas fa-sign-in-alt me-2"></i>Log In</button>
                        </form>

                        <p class="text-center text-muted mt-4 mb-0">New to Puihaha Electric? <a class="text-primary-custom fw-semibold" href="<?= esc(site_url('register')) ?>">Create an account</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
