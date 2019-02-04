<?php include_html('partials/header.php');?>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>
                <?php
                if (isset($user)) {
                    foreach ($user as $user) { ?>
                        <li><?php echo $user->name;?></li>
                    <?php
                    }
                }?>

                <div class="panel-body text-alignment">
                    Welcome
                </div>
            </div>


            <?php echo $render->links?>
        </div>
    </div>
</div>

<?php include_html('partials/footer.php');?>




