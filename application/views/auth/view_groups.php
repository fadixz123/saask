
<div class="panel panel-primary">
    <div class="panel-heading">
        <?php echo lang('edit_user_heading'); ?>
    </div>
    <div class="panel-body">

        <form id="formku" role="form" method="post">

            <b>Groups</b>
            <div class="form-group">
                <?php if ($this->ion_auth->is_admin()): ?>
                    <?php foreach ($groups as $group): ?>
                        <label class="checkbox">   
                            <div><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?> [
                                <a href="#" onclick="BukaPage('auth/edit_group/<?= $group['id']; ?>')">edit</a>
                                |                                
                                <a href="#" onclick="ConfirmDelete('<?= $group['id']; ?>')">delete</a>
                                ]
                            </div>
                        </label>
                    <?php endforeach ?>

                <?php endif ?>
            </div>
        </form>
        <a href="#" onclick="BukaPage('auth')">Kembali</a>
        |
        <a href="#" onclick="BukaPage('auth/create_group')">Buat grup baru</a>

    </div>
    <div class="panel-footer">
        Page rendered in <strong>{elapsed_time}</strong> seconds.
        &nbsp;
    </div>
</div>

<script>
    function ConfirmDelete(id) //PEGAWAI_TAMPIL
    {
        var e = confirm('Apakah yakin group akan dihapus?');
        if (e) {
            $.ajax({
                url: '<?php echo base_url('auth/delete_group'); ?>/' + id,
                type: 'POST',
                data: {id: id},
                success: function(result) {
                    if (result === 'success') {
                        BukaPage('auth/view_groups')
                    }
                }
            });
        }
    }
</script>

