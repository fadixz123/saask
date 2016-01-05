<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-<?= $jenis_panel ?>">
            <div class="panel-heading">
                &nbsp;
                <?= $this->pagination->create_links(); ?>
                <button type="reset" class="btn btn-default btn-xs  pull-right btn-warning" onclick="BukaPage('outbox/<?= ucfirst(str_replace('-', '_', $jenis_surat)) ?>')"><span class="fa fa-angle-double-left"></span> Kembali</button>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <?php require 'v-outbox-surat_included.php'; ?>

            </div>
        </div>
    </div>
</div>