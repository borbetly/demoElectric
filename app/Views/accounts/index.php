<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
$statusClasses = [
    'active' => 'bg-success',
    'inactive' => 'bg-secondary',
    'suspended' => 'bg-warning text-dark',
];
?>

<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2"><i class="fas fa-bolt text-warning me-2"></i>Customer Accounts</h1>
                <p class="lead mb-0">Manage the electric-company accounts from the reference database.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a class="btn btn-primary btn-lg" href="<?= esc(site_url(route_to('accounts.new'))) ?>">
                    <i class="fas fa-plus me-2"></i>Add Account
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= esc($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= esc($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-lg-3">
                <div class="card h-100 text-center p-4">
                    <i class="fas fa-users text-primary-custom fs-2 mb-2"></i>
                    <div class="display-6 fw-bold text-primary-custom"><?= esc((string) $counts['total']) ?></div>
                    <div class="text-muted">Total Accounts</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card h-100 text-center p-4">
                    <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                    <div class="display-6 fw-bold text-success"><?= esc((string) $counts['active']) ?></div>
                    <div class="text-muted">Active</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card h-100 text-center p-4">
                    <i class="fas fa-pause-circle text-secondary fs-2 mb-2"></i>
                    <div class="display-6 fw-bold text-secondary"><?= esc((string) $counts['inactive']) ?></div>
                    <div class="text-muted">Inactive</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card h-100 text-center p-4">
                    <i class="fas fa-exclamation-triangle text-warning fs-2 mb-2"></i>
                    <div class="display-6 fw-bold text-warning"><?= esc((string) $counts['suspended']) ?></div>
                    <div class="text-muted">Suspended</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <form method="get" action="<?= esc(site_url(route_to('accounts.index'))) ?>">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold" for="search">Search</label>
                            <input class="form-control" id="search" name="search" type="search" value="<?= esc($search) ?>" placeholder="Account, customer, email, or phone">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <label class="form-label fw-semibold" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All statuses</option>
                                <option value="active" <?= $filterStatus === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $filterStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="suspended" <?= $filterStatus === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <label class="form-label fw-semibold" for="type">Connection type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All types</option>
                                <option value="residential" <?= $filterType === 'residential' ? 'selected' : '' ?>>Residential</option>
                                <option value="commercial" <?= $filterType === 'commercial' ? 'selected' : '' ?>>Commercial</option>
                                <option value="industrial" <?= $filterType === 'industrial' ? 'selected' : '' ?>>Industrial</option>
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button>
                            <?php if ($search !== '' || $filterStatus !== '' || $filterType !== ''): ?>
                                <a class="btn btn-outline-secondary" href="<?= esc(site_url(route_to('accounts.index'))) ?>" aria-label="Clear filters"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Account</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Connection</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($accounts === []): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">No customer accounts match the current filters.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($accounts as $account): ?>
                                    <?php $statusClass = $statusClasses[$account['status']] ?? 'bg-secondary'; ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($account['account_number']) ?></strong>
                                            <div class="small text-muted">Meter: <?= esc($account['meter_number'] ?: '—') ?></div>
                                        </td>
                                        <td>
                                            <?= esc($account['customer_name']) ?>
                                            <div class="small text-muted text-truncate" style="max-width: 230px;"><?= esc($account['address']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= esc($account['email'] ?: '—') ?></div>
                                            <div class="small text-muted"><?= esc($account['phone'] ?: '—') ?></div>
                                        </td>
                                        <td><span class="badge bg-info text-dark"><?= esc(ucfirst($account['connection_type'])) ?></span></td>
                                        <td><span class="badge <?= esc($statusClass) ?>"><?= esc(ucfirst($account['status'])) ?></span></td>
                                        <td class="text-end text-nowrap">
                                            <a class="btn btn-sm btn-outline-primary" href="<?= esc(site_url(route_to('accounts.show', $account['id']))) ?>" title="View account"><i class="fas fa-eye"></i></a>
                                            <a class="btn btn-sm btn-outline-secondary" href="<?= esc(site_url(route_to('accounts.edit', $account['id']))) ?>" title="Edit account"><i class="fas fa-pen"></i></a>
                                            <form class="d-inline" method="post" action="<?= esc(site_url(route_to('accounts.delete', $account['id']))) ?>" onsubmit="return confirm('Delete this customer account? This cannot be undone.');">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete account"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if ($pager->getPageCount('accounts') > 1): ?>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4">
                <span class="text-muted">Page <?= esc((string) $currentPage) ?> of <?= esc((string) $pager->getPageCount('accounts')) ?></span>
                <?= $pager->only(['search', 'status', 'type'])->links('accounts') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
