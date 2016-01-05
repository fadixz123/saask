
<div class="panel panel-primary">
    <div class="panel-heading">
        <?php echo lang('index_heading'); ?>
    </div>
    <div class="panel-body">

        <table cellpadding=0 cellspacing=10 class="table table-bordered">
            <tr>
                <th><?php echo lang('index_fname_th'); ?></th>
                <th><?php echo lang('index_lname_th'); ?></th>
                <th><?php echo lang('index_email_th'); ?></th>
                <th><?php echo lang('index_groups_th'); ?></th>
                <th><?php echo lang('index_status_th'); ?></th>
                <th><?php echo lang('index_action_th'); ?></th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php foreach ($user->groups as $group): ?>
                            <?= htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8'); ?><br>
                        <?php endforeach ?>
                    </td>
                    <td><a href="#" onclick="<?= $user->active ? 'BukaPage(\'auth/deactivate/' . $user->id . '\')' : 'activate(\'' . $user->id . '\')'; ?>"><?= $user->active ? lang('index_active_link') : lang('index_inactive_link'); ?></a></td>

                    <td><a href="#" onclick="BukaPage('auth/edit_user/<?= $user->id; ?>')">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>
            <a href="#" onclick="BukaPage('auth/create_user')">Tambah user baru</a>
            | 
            <a href="#" onclick="BukaPage('auth/view_groups')">Lihat grup</a>
        </p>
    </div>
    <div class="panel-footer">
        Page rendered in <strong>{elapsed_time}</strong> seconds.
        &nbsp;
    </div>
</div>
<script>
    function activate(id) //PEGAWAI_TAMPIL
    {
        $.ajax({
            url: '<?php echo base_url('auth/activate'); ?>/' + id,
            type: 'POST',
            data: {nip: id},
            success: function(result) {
                if (result === 'success') {
                    BukaPage('auth')
                }
            }
        });
    }
</script>

