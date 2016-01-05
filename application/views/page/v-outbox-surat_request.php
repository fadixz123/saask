<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-<?= $jenis_panel ?>">
            <div class="panel-heading">
                Request Nomor <?= ucwords(str_replace('-', ' ', $jenis_surat)) ?>
            </div>
            <div class="panel-body">
                <?php
                echo get_user_id();
                if (!empty($agenda)) {
                    foreach ($agenda as $a) {
                        
                    }
                }
                ?>
                <form id="formku" role="form" action="#" method="post" autocomplete="off" >
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Tanggal Masuk</label>
                                <input class="form-control" value="<?= date('d-m-Y') ?>" readonly="" name="tgl_masuk" id="tgl_masuk">
                                <input type="hidden" value="<?= date('H:i:s') ?>" readonly="" name="jam_masuk">
                            </div>
                        </div>
                        <div class="col-lg-2">                
                            <label>Tanggal Surat</label>
                            <div class="form-group form-group-sm">
                                <div class='input-group date' id='datetimepicker_surat'>    
                                    <input type='text' class="form-control" name="tgl_surat" id="tgl_surat" class="form-control" value="<?= date('d-m-Y') ?>" placeholder="DD-MM-YYYY" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Bagian</label>
                                <select name="kd_bagian"class="form-control" required="">
                                    <option value="">- pilih -</option>
                                    <?php
                                    if (!empty($bagian)) {
                                        foreach ($bagian as $a) {
                                            echo "<option value='" . $a->kd_bagian . "'>" . $a->order . " - " . $a->nama_bagian . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Yang Meminta</label>
                                <input class="form-control uppercase"  required="" name="user" id="user">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>&nbsp;</label>
                                <input class="form-control uppercase"  name="nama" id="nama_nip" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Jumlah</label>
                                <input class="form-control uppercase" required="" value="1" name="jumlah" id="jumlah">
                            </div>
                        </div>

                    </div>


                    <input type="hidden" value="<?= $kd_jenis; ?>" name="kd_jenis">
                    <input type="hidden" value="1" name="request">
                    <input type="hidden" value="1" name="pos">
                    <button type="submit" class="btn btn-default btn-sm btn-success">Simpan</button>
                    <button type="reset" class="btn btn-default btn-sm" onclick="BukaPage('outbox/<?= ucfirst(str_replace('-', '_', $jenis_surat)) ?>')">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

    $('#tgl_surat').datetimepicker({
        format: 'dd-mm-yyyy',
        pickerPosition: 'bottom-right',
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2
    });
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: 'outbox/simpan_request', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(responseData) {
                $.notify(responseData, {
                    className: 'success',
                    autoHideDelay: 60000,
                    gap: 10,
                });
                $(".isi").load("<?= base_url('outbox/' . ucfirst(str_replace('-', '_', $jenis_surat))); ?>");
            },
            error: function(responseData) {
                console.log('Ajax request not recieved!');
            }
        });
    })

    var nipnama = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '<?= base_url('json/nip_nama'); ?>?q=y',
        remote: {
            url: '<?= base_url('json/nip_nama'); ?>?q=%QUERY',
            wildcard: '%QUERY'
        }
    });
    $('#user').click(function(e) {
        $('#user').select();
    })
    $('#user').typeahead(null, {
        name: 'best-pictures',
        display: 'nip',
        limit: 10,
        source: nipnama,
        templates: {
            empty: [
                '<div class="empty-message">',
                'pegawai tidak ditemukan',
                '</div>'
            ].join('\n'),
            suggestion: function(data) {
                return '<p><strong>' + data.nip + '</strong> – ' + data.nama + '</p>';
            }
        }
    }).on('typeahead:selected', function(event, data) {
        $('#nama_nip').val(data.nama);
    });

    $('#tgl_masuk').focus();
</script>