
<?php if(session()->has('success')){ ?>
    <div class="alert alert-success">
        <i class="fa fa-check-circle"> </i> <?php echo session()->get('success');?>
    </div>
<?php } ?>

<?php if(session()->has('error')){ ?>
    <i class="fa fa-exclamation-circle"></i>
        <i class="fa fa-window-close"> </i> <?php echo session()->get('error');?>
    </div>
<?php } ?>
