<script>
    function ConfirmDelete(id) //PEGAWAI_TAMPIL
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
<div class="table table-responsive">
    <?php
    $template = array(
        'table_open' => '<table border="1" cellpadding="2" cellspacing="1" class="table table-small table-bordered table-hover">'
    );

    if (!empty($data)) {
        echo '<table border="1" cellpadding="2" cellspacing="1" class="table table-small table-bordered table-hover">';
        echo '<tr>';
        echo '<th width="2%">No</th>';
        echo '<th width="6%">Tgl Masuk</th>';
        echo '<th width="6%">Tgl Surat</th>';
        echo '<th width="13%">Nomor Surat</th>';
        echo '<th width="22%">Perihal</th>';
        echo '<th width="17%">Tujuan</th>';
        echo '<th width="12%">Bagian</th>';
        echo '<th width="12%">Yg Meminta</th>';
        if ($jenis_surat == 'surat-biasa') {
            echo '<th width="3%">Kirim</th>';
        }
        echo '<th width="7%">Aksi</th>';
        echo '</tr>';

        foreach ($data as $a) {
            $start++;
            echo '<tr>';
            echo '<td>' . $start . '</td>';
            echo '<td>' . ($a->deleted ? '-' : $a->tgl_masuk) . '</td>';
            echo '<td>' . ($a->deleted ? '-' : $a->tgl_surat) . '</td>';
            echo '<td>' . ($a->no_surat) . '</td>';
            echo '<td>' . ($a->deleted ? '-' : ($a->request ? "<button class='btn disabled btn-xs'>REQUEST</button>" : $a->perihal)) . '</td>';
            echo '<td>' . ($a->deleted ? '-' : $a->tujuan) . '</td>';
            echo '<td>' . ($a->deleted ? '-' : $a->nama_bagian) . '</td>';
            echo '<td>' . ($a->deleted ? '-' : $a->nama_nip) . '</td>';
            if ($jenis_surat == 'surat-biasa') {

                switch ($a->pos) {
                    case 0: $pos = '<i class="fa fa-share" title="Langsung"></i>';
                        break;
                    case 1: $pos = '<i class="fa fa-envelope" title="Pos"></i>'; 
                        break;
                    case 2: $pos = '<i class="fa fa-forward" title="Telah dikirim"></i>';
                        break;
                    case 2: $pos = 'Terkirim';
                        break;
                    default: $pos = '-';
                        break;
                }
                echo '<td>' . ($a->deleted ? '-' : $pos) . '</td>';
            }
            echo '<td>' . ($a->deleted ? "<button class='btn btn-default btn-xs disabled'>deleted</button>" : "<button class='btn btn-primary btn-xs' onclick=\"BukaPage('outbox/" . str_replace('-', '_', $jenis_surat) . "/edit/" . safe_encode($a->id_surat) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "')\"><i class='fa fa-pencil'></i></button>"
                    . " <button class='btn btn-danger btn-xs' onclick=\"ConfirmDelete('" . $this->encrypt->encode($a->id_surat) . "')\"><i class='fa fa-trash'></i></button>") . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        $kosong = array('data' => 'Tidak ditemukan data!', 'colspan' => 7);
        $this->table->add_row(array(
            $kosong,
        ));
    }
    ?>
</div>
