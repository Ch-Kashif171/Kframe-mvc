<?php include_html('partials/header'); ?>

<div class="container" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="row" style="width:100%;">
        <div class="col-md-8 col-md-offset-2 text-center">
            <h1 style="font-size: 3em; font-weight: bold; color: #d9534f; margin-bottom: 20px;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                Oops! Something went wrong.
            </h1>
            <p style="font-size: 1.3em; color: #555;">
                We're experiencing an internal server error.<br>
                Please try again later or contact support if the problem persists.
            </p>
        </div>
    </div>
</div>

<style>
@keyframes shake {
    0% { transform: translateX(0);}
    25% { transform: translateX(-5px);}
    50% { transform: translateX(5px);}
    75% { transform: translateX(-5px);}
    100% { transform: translateX(0);}
}
</style>

<?php include_html('partials/footer'); ?>
