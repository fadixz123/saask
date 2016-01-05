
<div class="panel panel-default panel-transparent">
    <div class="panel-heading">
        <h1><?php echo lang('create_user_heading'); ?></h1>
        <p><?php echo lang('create_user_subheading'); ?></p>    </div>
    <div class="panel-body">

        <form id="formku" role="form" method="post">

            <p>
                <?php echo lang('create_user_fname_label', 'first_name'); ?> <br />
                <?php echo form_input($first_name); ?>
            </p>

            <p>
                <?php echo lang('create_user_lname_label', 'last_name'); ?> <br />
                <?php echo form_input($last_name); ?>
            </p>

            <?php
            if ($identity_column !== 'email') {
                echo '<p>';
                echo lang('create_user_identity_label', 'identity');
                echo '<br />';
                echo form_error('identity');
                echo form_input($identity);
                echo '</p>';
            }
            ?>

            <p>
                <?php echo lang('create_user_company_label', 'company'); ?> <br />
                <?php echo form_input($company); ?>
            </p>

            <p>
                <?php echo lang('create_user_email_label', 'email'); ?> <br />
                <?php echo form_input($email); ?>
            </p>

            <p>
                <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                <?php echo form_input($phone); ?>
            </p>

            <p>
                <?php echo lang('create_user_password_label', 'password'); ?> <br />
                <?php echo form_input($password); ?>
            </p>

            <p>
                <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
                <?php echo form_input($password_confirm); ?>
            </p>


            <p><?php echo form_submit('submit', lang('create_user_submit_btn')); ?></p>

            <?php echo form_close(); ?>
            <p>
                <a href="#" onclick="BukaPage('auth')">Kembali</a>
            </p>


    </div>
    <div class="panel-footer">
        Page rendered in <strong>{elapsed_time}</strong> seconds.
        &nbsp;
    </div>
</div>

<script>
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: '<?php echo base_url('auth/create_user/'); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('auth');
                } else {
                    $.notify(result, {
                        className: 'error',
                    });
                }
            },
            error: function(responseData) {
                console.log('Ajax request not recieved!');
            }
        });
    })
</script>