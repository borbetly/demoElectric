<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?= base_url('style/loginview_cs.css') ?>">

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<div class="container" id="container">
    <div class="form-container sign-in">
        <form action="<?= base_url('login'); ?>" method="POST">
            <?= csrf_field() ?>
            <h1>Log In</h1>
            <input type="text" name="username" id="username" placeholder="Username" value="<?= esc(old('username')) ?>" required autofocus>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i id="eyePassword" class="fa-solid fa-eye-slash toggle-icon" onclick="togglePassword('password', 'eyePassword')"></i>
            </div>


            <button type="submit" id="sign-in-btn">Log In</button>
        </form>
    </div>
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome!</h1>
                <p>Remember your password?</p>
                <button class="hidden" id="login">Log In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Use an account stored in your database.</p>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('javascript/login_js.js') ?>"></script>
