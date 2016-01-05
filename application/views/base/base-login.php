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
        <link rel="icon" href="<?= base_url('bootstrap/img/favicon.ico'); ?>">
        <title><?= lang('base_title_short') . ' - ' . lang('base_company_short'); ?></title>

    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-sm-6  col-sm-offset-3 col-md-4 col-md-offset-4">
                    <div class="account-wall">
                        <h1 class="text-center login-title">
                            <?php echo lang('login_heading'); ?>
                        </h1>
                        <img class="profile-img" src="<?php echo base_url(); ?>bootstrap/img/login_logo.png" alt="">
                        <div class="text-center login-title">
                            <h5 style="color: goldenrod; font-weight: bold; font-family: sans-serif">
                                <p><?php echo lang('login_subheading'); ?></p>                            
                            </h5>
                        </div>
                        <div class="text-center login-title">
                            <h5 style="color: red; font-family: sans-serif">
                                <div id="infoMessage"><?php echo $message; ?></div>
                            </h5>
                        </div>
                        <?php echo form_open("user/login", array('class' => 'form-signin', 'id' => 'formku')); ?>
                        <p>
                            <input type="text" name="identity" value="" id="identity" class="form-control" placeholder="Username"  />
                        </p>

                        <p>
                            <input type="password" name="password" value="" id="password" class="form-control" placeholder="Password"  />
                        </p>
                        <p><button name="submit" type="submit" id="submit" class="btn btn-lg btn-primary btn-block" >Login</button>
                        </p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" value="1"  id="remember"" />
                                Remember Me
                            </label>
                        </div>
                        <p><a href="forgot_password">Lupa Password?</a></p>  
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
        </div>
        <div id="footer" class="col-sm-12">
            <div class="pull-left">
                <?= lang('base_title') . ' | ' . lang('base_company'); ?>
            </div>
            <div class="pull-right">
                Copyright &copy; 2015 Teddy Cahyo Munanto | Based on PHP & MySQL | Powered by Codeigniter & Bootstrap
            </div>
        </div>

    </body>

    <div class="modal"></div>
</html>



