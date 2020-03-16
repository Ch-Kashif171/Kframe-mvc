
<?php include_html('partials/header'); ?>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="content">
                <div class="not_found">
                    <img src="<?php echo not_fount_image();?>" />
                </div>
                <div class="go_home">
                    <a href="<?php echo home_url(); ?>" class="btn btn-primary text-white not_found_error">Go Home</a>
                </div>
            </div>

        </div>
    </div>
</div>


<?php include_html('partials/footer'); ?>

