<?php include_html('partials/header.php'); ?>

<div class="auth-centered-section">
    <div class="auth-card">
        <h2 class="auth-title">Sign Up</h2>
        <form action="<?php echo url('register')?>" method="post">
            <?php include_html('flash/messages.php'); ?>
            <?php csrf_token(); ?>
            <div class="form-group">
                <label for="name">Full name</label>
                <input type="text" class="form-control <?php echo (has_error('name')?'error':'') ?>" id="name" name="name" placeholder="Enter your full name">
                <?php if (has_error('name')) { ?>
                    <span class="text text-danger" role="alert">
                        <strong><?php echo errors('name');?></strong>
                    </span>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control <?php echo (has_error('email')?'error':'') ?>" id="email" name="email" placeholder="Enter your email">
                <?php if (has_error('email')) { ?>
                    <span class="text text-danger" role="alert">
                        <strong><?php echo errors('email');?></strong>
                    </span>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" class="form-control <?php echo (has_error('password')?'error':'') ?>" id="pwd" name="password" placeholder="Create a password">
                <?php if (has_error('password')) { ?>
                    <span class="text text-danger" role="alert">
                        <strong><?php echo errors('password');?></strong>
                    </span>
                <?php } ?>
            </div>
            <button class="btn btn-auth-primary" type="submit">Sign Up</button>
            <div class="auth-switch-link">Already have an account? <a href="<?php echo url('/signin'); ?>">Sign In</a></div>
        </form>
    </div>
</div>

<?php include_html('partials/footer.php'); ?>