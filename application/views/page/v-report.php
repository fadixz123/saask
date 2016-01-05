
<div class="panel panel-default">
    <div class="panel-heading">
        Report
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form role="form" action="" method="post" id="formku">
                    <div class="col-lg-3">
                        <div class="form-group form-group-sm">
                            <label>Bagian</label>
                            <select id="kd_bagian"class="form-control">
                                <option value="00">0 - Semua Bagian</option>
                                <?php
                                if (!empty($data_bagian)) {
                                    foreach ($data_bagian as $a) {
                                        echo "<option value='" . $a->kd_bagian . "'>" . $a->order . " - " . $a->nama_bagian . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group form-group-sm">
                            <label>Jenis</label>
                            <select id="kd_jenis"class="form-control">
                                <option value="00">0 - Semua Jenis</option>
                                <?php
                                if (!empty($data_jenis)) {
                                    $n = 0;
                                    foreach ($data_jenis as $a) {
                                        $n++;
                                        echo "<option value='" . $a->kd_jenis . "'>" . $n . " - " . $a->nama_jenis . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">                
                        <label>Periode</label>
                        <div class="form-group form-group-sm">
                            <div class='input-group date' id='datetimepicker_surat'>    
                                <input type='text' class="form-control" id="tgl_awal" class="form-control" value="<?= date('d-m-Y', strtotime('-1 month', now())) ?>" placeholder="DD-MM-YYYY" />
                                <span class="input-group-addon">
                                    -
                                </span>
                                <input type='text' class="form-control" id="tgl_akhir" class="form-control" value="<?= date('d-m-Y') ?>" placeholder="DD-MM-YYYY" />

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">                
                        <label>Semua User</label>
                        <div class="form-group form-group-sm">
                            <input type="checkbox" class="checkbox-inline" id="user">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-2">                
                        <label>&nbsp;</label>
                        <div class="form-group form-group-sm">
                            <button type="submit" class="btn btn-default">Cetak</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- /.col-lg-6 (nested) -->
        </div>
        <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
</div>

<script>

    $('#formku').on('submit', function(e) {
        e.preventDefault();

        var link = "<?= base_url('report/ekspedisi'); ?>/" + $('#kd_bagian').val() + "/" + $('#kd_jenis').val() + "/" + $('#tgl_awal').val() + "/" + $('#tgl_akhir').val() + "/" + $('#user').prop('checked') + "/";
        window.open(link, 'newStuff'); //open's link in newly opened tab!

    });


    $('#tgl_awal').datetimepicker({
        format: 'dd-mm-yyyy',
        pickerPosition: 'bottom-right',
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2,
    });
    $('#tgl_akhir').datetimepicker({
        format: 'dd-mm-yyyy',
        pickerPosition: 'bottom-right',
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2,
    });
</script>