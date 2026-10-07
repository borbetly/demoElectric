<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$success = session()->getFlashdata('success');
$statusClasses = [
    'active' => 'bg-success',
    'inactive' => 'bg-secondary',
    'suspended' => 'bg-warning text-dark',
];
$statusClass = $statusClasses[$account['status']] ?? 'bg-secondary';
?>

<section class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-bolt text-warning me-2"></i>Account Details</h1>
        <p class="lead mb-0"><?= esc($account['account_number']) ?> · <?= esc($account['customer_name']) ?></p>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-8 mx-auto">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= esc($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <a class="btn btn-outline-secondary" href="<?= esc(site_url(route_to('accounts.index'))) ?>"><i class="fas fa-arrow-left me-2"></i>All Accounts</a>
                    <div class="d-flex gap-2">
                        <a class="btn btn-primary" href="<?= esc(site_url(route_to('accounts.edit', $account['id']))) ?>"><i class="fas fa-pen me-2"></i>Edit</a>
                        <form method="post" action="<?= esc(site_url(route_to('accounts.delete', $account['id']))) ?>" onsubmit="return confirm('Delete this customer account? This cannot be undone.');">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-2"></i>Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white p-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h2 class="h4 text-primary-custom mb-1"><?= esc($account['customer_name']) ?></h2>
                            <span class="text-muted">Account <?= esc($account['account_number']) ?></span>
                        </div>
                        <span class="badge <?= esc($statusClass) ?> fs-6"><?= esc(ucfirst($account['status'])) ?></span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Account Number</div>
                                <div><?= esc($account['account_number']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Meter Number</div>
                                <div><?= esc($account['meter_number'] ?: '—') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Connection Type</div>
                                <div><span class="badge bg-info text-dark"><?= esc(ucfirst($account['connection_type'])) ?></span></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Phone</div>
                                <div><?= esc($account['phone'] ?: '—') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Email</div>
                                <div><?= esc($account['email'] ?: '—') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small text-uppercase fw-semibold">Created / Updated</div>
                                <div><?= esc($account['created_at']) ?> <span class="text-muted">/</span> <?= esc($account['updated_at']) ?></div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small text-uppercase fw-semibold">Service Address</div>
                                <div class="border rounded p-3 bg-light"><?= nl2br(esc($account['address'])) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
