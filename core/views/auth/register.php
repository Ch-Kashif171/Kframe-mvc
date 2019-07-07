<?php include_html('partials/header.php'); ?>

<div class="container">

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h3 style="text-align: center; padding-bottom: 10px;">Register Form</h3>
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo url('register')?>" method="post">

                        <?php include_html('flash/messages.php'); ?>

                        <?php csrf_token(); ?>

                        <div class="form-group">
                            <label for="name">Full name:</label>
                            <input type="text" class="form-control <?php echo (has_error('name')?'error':'') ?>" id="name" name="name">

                            <?php if (has_error('name')) { ?>

                            <span class="error" role="alert">
                                        <strong><?php echo errors('name');?></strong>
                                    </span>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input type="email" class="form-control <?php echo (has_error('email')?'error':'') ?>" id="email" name="email">


                            <?php if (has_error('email')) { ?>

                                <span class="error" role="alert">
                                        <strong><?php echo errors('email');?></strong>
                                    </span>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control <?php echo (has_error('password')?'error':'') ?>" id="pwd" name="password">

                            <?php if (has_error('password')) { ?>

                                <span class="error" role="alert">
                                        <strong><?php echo errors('password');?></strong>
                                    </span>
                            <?php } ?>
                        </div>

                            <button class="btn btn-success" type="submit">Register</button>
                    </form>
                </div>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>

<?php include_html('partials/footer.php'); ?>