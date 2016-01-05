<?php
//print_r($data_user);
$list_level = array(
    '5' => 'Guest',
    '4' => 'Member',
    '3' => 'Contributor',
    '2' => 'Admin',
);
$option = '';
if (!empty($data_user)) {
    $mode = 'edit';
    $user_name = $data_user->user_id;
    $user_name = $data_user->user_name;
    $user_level = $data_user->user_level;
    $user_profile = $data_user->user_profile;
} else {
    $mode = 'tambah';
    $user_name = '';
    $user_name = '';
    $user_level = '';
    $user_profile = '';
}
?>
<div class="panel panel-<?= $tema; ?>">    
    <div class="panel-heading">
        <b><?= $header; ?></b>
    </div>
    <div class="panel-body">
        <form id="formku" role="form" method="post">
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-6 col-lg-3 form-group form-group-sm">
                        <label>Username</label><input type="text" name="user_name" <?= $option; ?> value="<?= $user_name; ?>" id="user_name" maxlength="100"  class="form-control" required="" /> 
                    </div>
                    <div class="col-md-6 col-lg-3 form-group form-group-sm">
                        <label>Nama Profil</label><input type="text" name="user_profile" <?= $option; ?> value="<?= $user_profile; ?>" id="user_profile"  class="form-control" /> 
                    </div>
                    <div class="col-md-6 col-lg-2 form-group form-group-sm">
                        <label>Level</label> 
                        <?= form_dropdown('user_level', $list_level, $user_level, 'class="form-control"'); ?>
                    </div>
                    <div class="col-md-6 col-lg-3 form-group form-group-sm">
                        <label>Password</label><input type="text" name="user_pass" <?= $option; ?>  id="user_pass" class="form-control" <?= $mode == 'edit' ? '' : 'required'; ?> placeholder="<?= $mode == 'edit' ? 'kosongkan jika tidak ingin mengubah' : ''; ?>" /> 
                    </div>
                </div>
            </div> 
            <div class="pull-right">
                <input value="<?= $mode; ?>" type="hidden" name="mode">
                <input value="<?= $mode == 'edit' ? $data_user->user_id : ''; ?>" type="hidden" name="user_id">
                <input type="submit" value="Simpan" class="btn btn-success btn-xs"/>
                <input type="button" value="Kembali" onclick="BukaPage('user')"class="btn btn-danger btn-xs"/>
            </div>
        </form>

    </div>
</div>
<script>
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: '<?php echo base_url('user/simpan'); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(responseData) {
                $.notify(responseData, {
                    className: 'success',
                });
                //BukaPage('user/select/' + $('#nip').val());
            },
            error: function(responseData) {
                console.log('Ajax request not recieved!');
            }
        });
    })
</script>