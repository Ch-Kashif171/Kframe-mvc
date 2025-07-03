
<?php include_html('partials/header'); ?>

<div class="container">

    <div class="row" style="text-align: center">
        <div class="col-md-12">
            <div class="content">
                <div class="not_found">
                    <img src="<?php echo not_fount_image();?>" />
                </div>
                <div class="go_home" style="    margin: 20px 0 20px 0;">
                    <a href="<?php echo home_url(); ?>" class="btn btn-primary text-white not_found_error">Go Home</a>
                </div>
            </div>

        </div>
    </div>
</div>


<?php include_html('partials/footer'); ?>

