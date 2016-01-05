<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <link href="<?php echo base_url('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('bootstrap/css/tedd_login.css'); ?>" rel="stylesheet">

    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-sm-6  col-sm-offset-3 col-md-4 col-md-offset-4">
                    <div class="account-wall">
                        <h1 class="text-center login-title">
                            <?php echo lang('forgot_password_heading'); ?>
                        </h1>
                        <img class="profile-img" src="<?php echo base_url(); ?>bootstrap/img/login_logo.png" alt="">
                        <div class="text-center login-title">
                            <h5 style="color: goldenrod; font-weight: bold; font-family: sans-serif">
                                <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>                            
                            </h5>
                        </div>
                        <div class="text-center login-title">
                            <h5 style="color: red; font-family: sans-serif">
                                <div id="infoMessage"><?php echo $message; ?></div>
                            </h5>
                        </div>
                        <?php echo form_open("auth/forgot_password", array('class' => 'form-signin', 'id' => 'formku')); ?>

                        <p>
                            <?php echo form_input($email); ?>
                        </p>

                        <p><?php echo form_button($submit, lang('forgot_password_submit_btn')); ?></p>

                        <p><a href="login"><?php echo lang('login_heading'); ?></a></p>  
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
        </div>
        <div id="footer" class="col-sm-12">
            <div class="pull-left">
                Civil Service Integrated System | Kantor Regional VIII BKN
            </div>
            <div class="pull-right">
                Copyright &copy; 2015 Teddy Cahyo Munanto | Based on PHP & MySQL | Powered by Codeigniter & Bootstrap
            </div>
        </div>

    </body>

    <div class="modal"></div>
</html>
