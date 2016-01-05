
<div class="panel panel-primary">
    <div class="panel-heading">
        <b><?php echo lang('deactivate_heading'); ?></b>
    </div>
    <div class="panel-body">
        <form id="formku" role="form" method="post">

            <p>
                Mau deaktivasi user ini?
            </p>
            <input name="confirm" value="yes" type="hidden" />

            <?php echo form_hidden($csrf); ?>
            <?php echo form_hidden(array('id' => $user->id)); ?>

            <p><?php echo form_submit('submit', 'Yes'); ?></p>
            <p>
                <a href="#" onclick="BukaPage('auth')">Kembali</a>
            </p>

        </form>
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
            url: '<?php echo base_url('auth/deactivate/' . $this->uri->segment(3)); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('auth');
                } else {
                    BukaPage('auth');
                }
            },
            error: function(responseData) {
                console.log('Ajax request not recieved!');
            }
        });
    })
</script>