
<div class="panel panel-primary">
    <div class="panel-heading">
        <b><?php echo lang('edit_group_heading'); ?></b>
    </div>
    <div class="panel-body">

        <form id="formku" role="form" method="post">

            <p>
                <?php echo lang('edit_group_name_label', 'group_name'); ?> <br />
                <?php echo form_input($group_name); ?>
            </p>

            <p>
                <?php echo lang('edit_group_desc_label', 'description'); ?> <br />
                <?php echo form_input($group_description); ?>
            </p>

            <p><?php echo form_submit('submit', lang('edit_group_submit_btn')); ?></p>
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
            url: '<?php echo base_url('auth/edit_group/' . $this->uri->segment(3)); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('auth/view_groups');
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