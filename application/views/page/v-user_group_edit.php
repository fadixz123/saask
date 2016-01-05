
<div class="panel panel-primary">
    <div class="panel-heading">
        <b>Edit Group</b>
    </div>
    <div class="panel-body">

        <form id="formku" role="form" method="post">

            <p>
                <label for="group_name">Group Name:</label> <br />
                <input type="text" name="group_name" value="<?= $group_name; ?>" id="group_name" <?= $readonly; ?>/>
            </p>

            <p>
                <label for="description">Description:</label> <br />
                <input type="text" name="group_description" value="<?= $group_description; ?>" id="group_description"/>
            </p>
            <input class="btn btn-primary btn-xs" type="submit" name="submit" value="Save Group"/>
            <p>
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
            url: '<?php echo base_url('user/edit_group/' . $this->uri->segment(3)); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('user/view_group');
                } else {
                    $.notify(result, {
                        className: 'error',
                    });
                }
            },
            error: function(responseData) {
                window.location.href = '';
            }
        });
    })
</script>