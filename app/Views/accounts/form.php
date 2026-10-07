<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$errors = session()->getFlashdata('validation') ?? [];
$error = session()->getFlashdata('error');
?>

<section class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-bolt text-warning me-2"></i><?= esc($heading) ?></h1>
        <p class="lead mb-0">Use the customer-account fields defined in the electric-company database.</p>
    </div>
</section>

<section class="section-padding bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-8 mx-auto">
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle me-2"></i><?= esc($error) ?></div>
                        <?php endif; ?>

                        <form method="post" action="<?= esc(site_url($formAction)) ?>">
                            <?= csrf_field() ?>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="account_number">Account Number <span class="text-danger">*</span></label>
                                    <input class="form-control <?= isset($errors['account_number']) ? 'is-invalid' : '' ?>" id="account_number" name="account_number" maxlength="50" required value="<?= esc(old('account_number', $account['account_number'] ?? '')) ?>" placeholder="EC-2024-0001">
                                    <?php if (isset($errors['account_number'])): ?><div class="invalid-feedback"><?= esc($errors['account_number']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="meter_number">Meter Number</label>
                                    <input class="form-control <?= isset($errors['meter_number']) ? 'is-invalid' : '' ?>" id="meter_number" name="meter_number" maxlength="50" value="<?= esc(old('meter_number', $account['meter_number'] ?? '')) ?>" placeholder="MTR-001">
                                    <?php if (isset($errors['meter_number'])): ?><div class="invalid-feedback"><?= esc($errors['meter_number']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                    <input class="form-control <?= isset($errors['customer_name']) ? 'is-invalid' : '' ?>" id="customer_name" name="customer_name" maxlength="150" required value="<?= esc(old('customer_name', $account['customer_name'] ?? '')) ?>">
                                    <?php if (isset($errors['customer_name'])): ?><div class="invalid-feedback"><?= esc($errors['customer_name']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="address">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" rows="3" required><?= esc(old('address', $account['address'] ?? '')) ?></textarea>
                                    <?php if (isset($errors['address'])): ?><div class="invalid-feedback"><?= esc($errors['address']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="email">Email Address</label>
                                    <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" maxlength="100" value="<?= esc(old('email', $account['email'] ?? '')) ?>">
                                    <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= esc($errors['email']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="phone">Phone Number</label>
                                    <input class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" maxlength="20" value="<?= esc(old('phone', $account['phone'] ?? '')) ?>">
                                    <?php if (isset($errors['phone'])): ?><div class="invalid-feedback"><?= esc($errors['phone']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $selectedType = old('connection_type', $account['connection_type'] ?? 'residential'); ?>
                                    <label class="form-label fw-semibold" for="connection_type">Connection Type <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($errors['connection_type']) ? 'is-invalid' : '' ?>" id="connection_type" name="connection_type" required>
                                        <option value="residential" <?= $selectedType === 'residential' ? 'selected' : '' ?>>Residential</option>
                                        <option value="commercial" <?= $selectedType === 'commercial' ? 'selected' : '' ?>>Commercial</option>
                                        <option value="industrial" <?= $selectedType === 'industrial' ? 'selected' : '' ?>>Industrial</option>
                                    </select>
                                    <?php if (isset($errors['connection_type'])): ?><div class="invalid-feedback"><?= esc($errors['connection_type']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $selectedStatus = old('status', $account['status'] ?? 'active'); ?>
                                    <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" id="status" name="status" required>
                                        <option value="active" <?= $selectedStatus === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $selectedStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="suspended" <?= $selectedStatus === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                    </select>
                                    <?php if (isset($errors['status'])): ?><div class="invalid-feedback"><?= esc($errors['status']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-12 d-flex flex-wrap justify-content-between gap-3 pt-2">
                                    <a class="btn btn-outline-secondary" href="<?= esc(site_url(route_to('accounts.index'))) ?>"><i class="fas fa-arrow-left me-2"></i>Cancel</a>
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i><?= esc($submitLabel) ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
