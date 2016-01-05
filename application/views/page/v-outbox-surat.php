<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-<?= $jenis_panel ?>">
            <div class="panel-heading">
                Tambah Agenda (<?= ucwords(str_replace('-', ' ', $jenis_surat)) ?>)
                <div class="pull-right">
                    <button type="reset" class="btn btn-default btn-xs" onclick="BukaPage('outbox/request/<?= $kd_jenis . '/' . $jenis_surat; ?>')">REQUEST</button>
                </div>
            </div>
            <div class="panel-body">
                <form id="formku" role="form" action="#" method="post" autocomplete="off" >
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Nomor Surat</label>
                                <input type="text" class="form-control" disabled="" value="Otomatis">
                            </div>
                        </div>
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
                                <?php
                                if ($kd_jenis == 'SP' || $kd_jenis == 'SK') {
                                    ?>
                                    <input type="hidden" readonly name="kd_bagian" value="KK">
                                    <input class="form-control uppercase" disabled  value="Kepala Kantor">
                                    <?php
                                } else {
                                    ?>
                                    <select name="kd_bagian"class="form-control" required="" >
                                        <option value="">- pilih -</option>
                                        <?php
                                        if (!empty($bagian)) {
                                            foreach ($bagian as $a) {
                                                echo "<option value='" . $a->kd_bagian . "'>" . $a->order . " - " . $a->nama_bagian . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Tujuan Surat</label>
                                <input class="form-control uppercase" required="" name="tujuan">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>Penandatangan</label>
                                <input class="form-control uppercase" required="" name="ptt" id="ptt">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group form-group-sm">
                                <label>Perihal</label>
                                <input class="form-control uppercase" required="" name="perihal">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Yang Meminta</label>
                                <input class="form-control uppercase" required="" name="user" id="user">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>&nbsp;</label>
                                <input class="form-control uppercase" required="" id="nama_nip" disabled>
                            </div>
                        </div>
                        <?php if ($jenis_surat == 'surat-biasa') { ?>
                            <div class="col-lg-2  form-group form-group-sm">
                                <label>Kirim</label><br>
                                <div class="btn-group"  class="col-lg-2">
                                    <input type="radio" name="pos" id="pos" value="1" checked>Pos
                                    &nbsp;
                                    <input type="radio" name="pos" id="pos" value="0" >Langsung
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <input type="hidden" value="<?= $kd_jenis ?>" name="kd_jenis">
                    <input type="hidden" value="tambah" name="mode">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-default btn-xs btn-success">Simpan</button>
                        <button type="reset" class="btn btn-default btn-xs">Reset</button>
                    </div>
                </form>
                <div class="clearfix"></div>
                <br>
                <table class="table table-bordered table-striped table-hover" id="mytable">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="6%">Tgl Masuk</th>
                            <th width="6%">Tgl Surat</th>
                            <th width="13%">Nomor Surat</th>
                            <th width="22%">Perihal</th>
                            <th width="20%">Tujuan</th>
                            <!--th width="10%">Bagian</th-->
                            <th width="15%">TTD</th>
                            <!--th width="10%">Yang Meminta</th-->
                            <th width="5%">Admin</th>
                            <?= $surat == 'surat_biasa' ? '<th width="5%">Kirim</th>' : ''; ?>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">

    $('#tgl_surat').datetimepicker({
        format: 'dd-mm-yyyy',
        pickerPosition: 'bottom-right',
        weekStart: 1,
        autoclose: 1,
        startView: 2,
        minView: 2
    });
    $(document).ready(function() {

        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };
        var t = $('#mytable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo site_url('outbox/view_ajax/' . $surat); ?>",
            "sDom": '<ip<t>lf>',
            "columns": [
                {
                    "data": null,
                    "class": "text-center",
                    "orderable": false
                },
                {"data": "tgl_masuk"},
                {"data": "tgl_surat"},
                {"data": "no_surat"},
                {"data": "perihal"},
                {"data": "tujuan"},
                //{"data": "nama_bagian"},
                {"data": "ptt"},
                //{"data": "nama_nip"},
                {"data": "user_name"},
<?= $surat == 'surat_biasa' ? '{"data": "pos"},' : ''; ?>
                {"data": "id_surat"},
            ],
            "order": [[1, 'desc'], [3, 'desc']],
            "rowCallback": function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
                var temp = $('td:eq(-1)', row).text();
                var username = $('td:eq(7)', row).text();
                if (username == '<?= get_user_name(); ?>') {
                    var string = '<a href="#" onclick="BukaPage(\'outbox/<?= $surat; ?>/edit/' + temp + '/<?= $this->uri->segment(3); ?>/\')">edit</a> | \n\
                <a href="#" onclick="ConfirmDelete(\'' + temp + '\')">delete</a>';
                } else {
                    string = '<a href="#" onclick="BukaPage(\'outbox/<?= $surat; ?>/view/' + temp + '/<?= $this->uri->segment(3); ?>/\')">view</a>';
                }
                $('td:eq(-1)', row).html(string);
            },
        });
    });
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: 'outbox/simpan', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(responseData) {
                $('.notifications').notify({
                    message: {text: 'Data berhasil disimpan!'},
                    closable: false,
                    type: 'blackgloss'
                }).show();
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
    $('#tgl_masuk').focus();
    function ConfirmDelete(id)
    {
        var x = confirm("Apakah Anda yakin agenda ini akan dihapus? \n\nAgenda yang sudah dihapus tidak dapat \ndikembalikan maupun digunakan lagi");
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
</script>