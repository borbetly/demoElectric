<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $success = session()->getFlashdata('success'); ?>

<section class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-bolt text-warning me-2"></i>Customer Dashboard</h1>
        <p class="lead mb-0">Welcome, <?= esc($userName) ?>.</p>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= esc($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="feature-icon"><i class="fas fa-users"></i></div>
                        <h2 class="h3 text-primary-custom">Customer Account Management</h2>
                        <p class="text-muted mb-4">View, add, edit, and remove electric-company customer accounts.</p>
                        <a class="btn btn-primary btn-lg" href="<?= esc(site_url(route_to('accounts.index'))) ?>"><i class="fas fa-address-book me-2"></i>Manage Accounts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
