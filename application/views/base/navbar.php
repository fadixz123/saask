<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <img alt="" src="<?= base_url('bootstrap/img/login_logo.png'); ?>" class="body-logo">
        <a class="navbar-brand" href="<?= base_url(); ?>"> <?= lang('base_title'); ?></a>
    </div>
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-right"
        <li class="dropdown navbar-static-top">
            <span id="jam" style="color:#265a88 ;">Loading Date/Time. . .</span>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <?= $user->first_name . ' ' . $user->last_name; ?> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#" onclick="BukaPage('user/edit_user/<?= $this->ion_auth->user()->row()->id; ?>/?p=1')"><i class="fa fa-user fa-fw"></i> Ubah Data Diri</a></li>
                <li class="divider"></li>
                <li><a href="<?= base_url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            </ul>
        </li>
    </ul>

    <!-- /.navbar-sidebar -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li><a onclick="BukaPage('dashboard')">Dashboard</a></li>

                <?php if ($this->ion_auth->in_group('sb')) { ?>  
                    <li><a onclick="BukaPage('outbox/surat_biasa')"><i class="fa fa-arrow-right fa-fw"></i> Surat Biasa</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('sp')) { ?>             
                    <li><a onclick="BukaPage('outbox/surat_perintah')"><i class="fa fa-arrow-right fa-fw"></i> Surat Perintah</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('sk')) { ?>             
                    <li><a onclick="BukaPage('outbox/surat_keputusan')"><i class="fa fa-arrow-right fa-fw"></i> Surat Keputusan</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('spt')) { ?>             
                    <li><a onclick="BukaPage('outbox/surat_perintah_tugas')"><i class="fa fa-arrow-right fa-fw"></i> Surat Perintah Tugas</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('nd')) { ?>             
                    <li><a onclick="BukaPage('outbox/nota_dinas')"><i class="fa fa-arrow-right fa-fw"></i> Nota Dinas</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('md')) { ?>             
                    <li><a onclick="BukaPage('outbox/memo_dinas')"><i class="fa fa-arrow-right fa-fw"></i> Memo Dinas</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('skk')) { ?>             
                    <li><a onclick="BukaPage('outbox/surat_kuasa_khusus')"><i class="fa fa-arrow-right fa-fw"></i> Surat Kuasa Khusus</a></li>
                <?php } ?>
                <?php if ($this->ion_auth->in_group('report')) { ?>            
                    <li><a onclick = "BukaPage('report/outbox')"><i class = "fa fa-gears fa-fw"></i> Report</a></li>
                <?php } ?>

                <?php if ($this->ion_auth->in_group('admin')) { ?>
                    <li><a onclick = "BukaPage('user')"><i class = "fa fa-gears fa-fw"></i> User</a></li>
                    <li><a onclick = "BukaPage('user/view_group')"><i class = "fa fa-gears fa-fw"></i> Group</a></li>
                    <?php } ?>
            </ul>
        </div>
    </div>
</nav>

