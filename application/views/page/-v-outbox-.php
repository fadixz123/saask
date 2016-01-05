<div class="row">
    <div class="col-lg-12">
        <h4>Manajemen User</h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                Data User
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Last Login</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tz = new DateTimeZone('Asia/Hong_kong');
                            foreach ($data_user as $user) {
                                ?>
                                <tr>
                                    <td class="center"><?= $user->user_name; ?></td>
                                    <td class="center">
                                        <?php
                                        if ($user->last_login != '') {
                                            $dt = new DateTime($user->last_login);
                                            $dt->setTimezone($tz);
                                            echo $dt->format('Y-m-d H:i:s');
                                        }
                                        ?>
                                    </td>
                                    <td class="center"><?= $user->user_level; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tambah User
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" action="<?php echo base_url() . 'user/simpan'; ?>" method="post">
                            <?php
                            $username = array(
                                'name' => 'nusername',
                                'id' => 'username',
                                'autofocus' => 'autofocus',
                                'class' => 'form-control',
                            );
                            $pass = array(
                                'name' => 'npass',
                                'type' => 'password',
                                'class' => 'form-control',
                            );
                            $dropdown = array(
                                '5' => 'Guest',
                                '4' => 'Member',
                                '3' => 'Contributor',
                                '2' => 'Admin',
                            );
                            $dropdown_class = '';
                            ?>
                            <div class="form-group">
                                <label>Username</label>
                                <?= form_input($username); ?>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <?= form_input($pass); ?>
                            </div>

                            <div class="form-group">
                                <label>Level</label>
                                <?= form_dropdown('level', $dropdown, '5', 'class="form-control"'); ?>
                            </div>
                            <input value="tambah" type="hidden" name="mode">
                            <button type="submit" class="btn btn-default">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </form>
                    </div>

                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>