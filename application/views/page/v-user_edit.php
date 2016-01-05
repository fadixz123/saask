<div class="panel panel-primary">
    <div class="panel-heading">
        Edit User 
        <div class="pull-right">
            <?= $this->input->get('p') == NULL ? '<a class="btn btn-default btn-xs" href="#" onclick="BukaPage(\'user\')">kembali</a>' : ''; ?>
        </div>
    </div>
    <div class="panel-body">

        <form id="formku" role="form" method="post">
            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>
            <div class="col-lg-4">
                <p>
                    <label for="first_name">First Name:</label> <br />
                    <input type="text" name="first_name" value="<?= $first_name; ?>" id="first_name"  />
                </p>
                <p>
                    <label for="last_name">Last Name:</label> <br />
                    <input type="text" name="last_name" value="<?= $last_name; ?>" id="last_name"  />
                </p>
                <p>
                    <label for="company">Company Name:</label> <br />
                    <input type="text" name="company" value="<?= $company; ?>" id="company"  />
                </p>
                <p>
                    <label for="email">Email Address</label> <br />
                    <input type="email" name="email" value="<?= $email; ?>" id="email"  />
                </p>
            </div>

            <div class="col-lg-4">
                <p>
                    <label for="phone">Phone:</label> <br />
                    <input type="text" name="phone" value="<?= $phone; ?>" id="phone"  />
                </p>
                <p>
                    <label for="password">Password: (if changing password)</label> <br />
                    <input type="password" name="password" value="" id="password"  />
                </p>
                <p>
                    <label for="password_confirm">Confirm Password: (if changing password)</label><br />
                    <input type="password" name="password_confirm" value="" id="password_confirm"  />
                </p> 
                <p>
                    <input class="btn btn-primary btn-xs" type="submit" name="submit" value="Update User"  />
                </p>
            </div>

            <?php if ($this->ion_auth->is_admin()): ?>
                <div class="col-lg-4">
                    <p><label for="group">Member of groups</label></p>
                    <div class="form-group">
                        <?php foreach ($groups as $group): ?>
                            <label class="checkbox">
                                <?php
                                $gID = $group['id'];
                                $checked = null;
                                $item = null;
                                foreach ($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                        $checked = ' checked="checked"';
                                        break;
                                    }
                                }
                                ?>
                            </label>

                            <input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>"<?php echo $checked; ?>>
                            <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </form>
    </div>
    <div class="panel-footer">
        Page rendered in <strong>0.0468</strong> seconds.
        &nbsp;
    </div>
</div>

<script>
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: '<?= base_url('user/edit_user/' . $user->id); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('user/<?= $this->input->get('p') == NULL ? '' : 'edit_user/' . $user->id; ?>');

                } else {
                    $.notify(result, {
                        className: 'error',
                    });
                    BukaPage('user/edit_user/<?= $user->id; ?>');
                }
            },
            error: function(responseData) {
                window.location.href = '';
            }
        });
    })
</script>