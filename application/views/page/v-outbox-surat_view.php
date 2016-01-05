<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-<?= $jenis_panel ?>">
            <div class="panel-heading">
                Edit Agenda
            </div>
            <div class="panel-body">
                <?php
                if (!empty($agenda)) {
                    foreach ($agenda as $a) {
                        
                    }
                }
                ?>
                <form id="formku" role="form" action="#" method="post" autocomplete="off" >
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Nomor Surat</label>
                                <input type="text" class="form-control" readonly="" value="<?= $a->no_surat ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Tanggal Masuk</label>
                                <input class="form-control" value="<?= date("d-m-Y", strtotime($a->tgl_masuk)) ?>" readonly="">
                            </div>
                        </div>

                        <div class="col-lg-2">                
                            <label>Tanggal Surat</label>
                            <div class="form-group form-group-sm">
                                <div class='input-group date' id='datetimepicker_surat'>    
                                    <input type='text' class="form-control"  readonly="" value="<?= date("d-m-Y", strtotime($a->tgl_masuk)) ?>" />
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

                                <select class="form-control" readonly="" >
                                    <?php
                                    if (!empty($bagian)) {
                                        foreach ($bagian as $b) {
                                            $selected = $a->kd_bagian == $b->kd_bagian ? 'selected' : '';
                                            echo "<option value='" . $b->kd_bagian . "' " . $selected . ">" . $b->order . " - " . $b->nama_bagian . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Tujuan Surat</label>
                                <input class="form-control uppercase" readonly=""  value="<?= $a->tujuan ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Penandatangan</label>
                                <input class="form-control uppercase" readonly="" value="<?= $a->ptt ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group form-group-sm">
                                <label>Perihal</label>
                                <input class="form-control uppercase" readonly="" value="<?= $a->perihal ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Yang Meminta</label>
                                <input class="form-control uppercase" value="<?= $a->user ?>" readonly="" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>&nbsp;</label>
                                <input class="form-control uppercase" value="<?= $a->nama_nip ?>" required="" id="nama_nip" disabled>
                            </div>
                        </div>

                        <?php if ($jenis_surat == 'surat-biasa') { ?>
                            <div class="col-lg-2  form-group form-group-sm">
                                <label>Kirim</label><br>
                                <div class="btn-group"  class="col-lg-2" >
                                    <input type="radio" name="pos" id="pos" disabled="" value="0" <?= $a->pos == 0 ? 'checked=""' : ''; ?>>Langsung
                                    &nbsp;
                                    <input type="radio" name="pos" id="pos" disabled="" value="1" <?= $a->pos == 1 ? 'checked=""' : ''; ?>>Pos
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="pull-right">
                        <button type="reset" class="btn btn-default btn-sm" onclick="BukaPage('outbox/<?= ucfirst(str_replace('-', '_', $jenis_surat)) . '/' . $this->uri->segment(5) . '/' . $this->uri->segment(6) ?>')">Batal</button>
                    </div>
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
            url: 'outbox/simpan', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(responseData) {
                $.notify('Data telah disimpan', {
                    className: 'success',
                });
                $(".isi").load("<?= base_url('outbox/' . ucfirst(str_replace('-', '_', $jenis_surat))); ?>");
            },
            error: function(responseData) {
                console.log('Ajax request not recieved!');
            }
        });
    })
    function ConfirmDelete(id) //PEGAWAI_TAMPIL
    {
        var x = confirm("Agenda ini akan dihapus?");
        if (x) {
            $.ajax({
                url: '<?php echo base_url('outbox/hapus'); ?>',
                type: 'POST',
                data: {id: id},
                success: function(result) {
                    $(".isi").load('<?php echo base_url('outbox/' . $this->uri->segment(2) . '/' . $this->uri->segment(3)); ?>');
                }
            });
        }
    }


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


/////////////////////////////////////////////////////////////PTT
    $('#ptt').click(function(e) {
        $('#select').select();
    })
    $('#ptt').typeahead(null, {
        name: 'best-pictures',
        display: 'nama',
        limit: 10,
        source: nipnama,
        templates: {
            empty: [
                '<div class="empty-message">',
                'pegawai tidak ditemukan',
                '</div>'
            ].join('\n'),
            suggestion: function(data) {
                return '<p>' + data.nama + '</p>';
            }
        }
    });

    $('#tujuan').focus();
</script>