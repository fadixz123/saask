<?php

/*  Surat Pemberitahuan Kenaikan Gaji Berkala (KGB)
 *  Badan Kepegawaian Negara
 *  Creator : Teddy Cahyo Munanto
 *  Email   : teddcm@gmail.com
 */
include 'bootstrap/plugins/fpdf/fpdf.php';


foreach ($data_kgb as $kgb) {

// Instanciation of inherited class
    class PDF extends FPDF {

        function Header() {
            // Logo
            $this->Image('bootstrap/img/logo_sk.jpg', 35, 8, 20);
            $this->SetFont('Times', '', 10);
            $this->Cell(70, 48, 'BADAN KEPEGAWAIAN NEGARA', 0, 0, 'C');
            $this->Ln(0);
            $this->Cell(70, 58, 'KANTOR REGIONAL VIII', 0, 0, 'C');
            $this->Ln(0);
            $this->Cell(70, 68, 'BANJARMASIN', 0, 0, 'C');
        }

        function Footer() {
            $this->SetFont('ArialNarrow', '', 9);
            $this->Ln(10);
            $this->Cell(15);
            $this->Cell(0, 70, 'Tembusan :', 0, 0, 'L');
            $this->Ln(0);
            $this->Cell(15);
            $this->Cell(5, 80, '1.', 0, 0, 'L');
            $this->Cell(0, 80, 'Kepala Perwakilan Badan Pemeriksa Keuangan di Banjarmasin;', 0, 0, 'L');
            $this->Ln(0);
            $this->Cell(15);
            $this->Cell(5, 90, '2.', 0, 0, 'L');
            $this->Cell(0, 90, 'Kepala Biro Kepegawaian BKN di Jakarta;', 0, 0, 'L');
            $this->Ln(0);
            $this->Cell(15);
            $this->Cell(5, 100, '3.', 0, 0, 'L');
            $this->Cell(0, 100, 'Pegawai yang bersangkutan;', 0, 0, 'L');
            $this->Ln(0);
            $this->Cell(15);
            $this->Cell(5, 110, '4.', 0, 0, 'L');
            $this->Cell(0, 110, 'Pertinggal', 0, 0, 'L');
        }

    }

    $pdf = new PDF('P', 'mm', array(210, 330));
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(0);
    $pdf->AddFont('ArialNarrow', '', 'arialn.php');
    $pdf->AddFont('ArialNarrow', 'B', 'arialnb.php');
    $pdf->AddFont('ArialNarrow', 'BI', 'arialnbi.php');
    $pdf->AddFont('ArialNarrow', 'I', 'arialni.php');

    $pdf->SetFont('ArialNarrow', '', 10);

// VARIABEL
    $sifat = 'Penting';
    $lampiran = '-';
    $perihal = 'Kenaikan Gaji Berkala';
    $kota = 'Banjarbaru';

    $nama = $kgb->nama;
    $nip = split_nip($kgb->nip); //ok
    $unit_kerja = $kgb->unit_kerja; //ok

    $tgl_sk_lama = convert_tanggal('j M Y', $kgb->tgl_sk_lama);
    $no_sk_lama = $kgb->no_sk_lama;
    $tgl_mulai_sk_lama = convert_tanggal('j M Y', $kgb->tgl_mulai_sk_lama);
    $gapok_lama = $kgb->gapok_lama;
    $mkg_sk_lama = substr($kgb->mkg_sk_lama, 0, 2) . " Tahun " . substr($kgb->mkg_sk_lama, 2, 2) . " Bulan";
    $golru_lama = $kgb->golru_lama;
    $jabatan_baru = $kgb->jabatan_baru;
    $jabatan_pejabat_lama = $kgb->jabatan_pejabat_lama;


    $tgl_sk_baru = convert_tanggal('j M Y', $kgb->tgl_sk_baru);
    $no_sk_baru = $kgb->no_sk_baru;
    $tgl_mulai_sk_baru = convert_tanggal('j M Y', $kgb->tgl_mulai_sk_baru);
    $gapok_baru = $kgb->gapok_baru;
    $mkg_sk_baru = substr($kgb->mkg_sk_baru, 0, 2) . " Tahun " . substr($kgb->mkg_sk_baru, 2, 2) . " Bulan";
    $golru_baru = $kgb->golru_baru;
    $pejabat_baru = $kgb->pejabat_baru;
    $nip_pejabat_baru = split_nip($kgb->nip_pejabat_baru);


    $dasar_hukum = $kgb->dasar_hukum;


    $pdf->Ln(40);
    $pdf->Cell(5);
    $pdf->Cell(15, 10, 'Nomor', 0, 0, 'L');
    $pdf->Cell(1, 10, ': ' . $no_sk_baru, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(5);
    $pdf->Cell(15, 20, 'Sifat', 0, 0, 'L');
    $pdf->Cell(1, 20, ': ' . $sifat, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(5);
    $pdf->Cell(15, 30, 'Lampiran', 0, 0, 'L');
    $pdf->Cell(1, 30, ': ' . $lampiran, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(5);
    $pdf->Cell(15, 40, 'Perihal', 0, 0, 'L');
    $pdf->Cell(1, 40, ': ' . $perihal, 0, 0, 'L');

    $pdf->Ln(0);
    $pdf->Cell(140);
    $pdf->Cell(1, 10, $kota . ', ' . $tgl_sk_baru, 0, 0, 'L');

    $pdf->Ln(0);
    $pdf->Cell(110);
    $pdf->Cell(15, 40, 'Kepada:', 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(102);
    $pdf->Cell(15, 50, 'Yth.', 0, 0, 'L');

    $kepada = explode(PHP_EOL, $kgb->kepada);
    $n = 50;
    foreach ($kepada as $line) {
        $pdf->Ln(0);
        $pdf->Cell(110);
        $pdf->Cell(15, $n, $line, 0, 0, 'L');
        $n = $n + 10;
    }


    $pdf->Ln(45);
    $pdf->Cell(30);
    $pdf->MultiCell(150, 5, '           Dengan ini diberitahukan bahwa berhubung dengan telah dipenuhinya masa kerja dan syarat-syarat lainnya kepada : ');
    $pdf->Ln(5);
    $pdf->Cell(30);
    $pdf->Cell(5, 0, '1.', 0, 0, 'L');
    $pdf->Cell(50, 0, 'Nama', 0, 0, 'L');
    $pdf->Cell(0, 0, ': ' . $nama, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 10, '2.', 0, 0, 'L');
    $pdf->Cell(50, 10, 'NIP', 0, 0, 'L');
    $pdf->Cell(0, 10, ': ' . $nip, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 20, '3.', 0, 0, 'L');
    $pdf->Cell(50, 20, 'Pangkat / golongan / jabatan', 0, 0, 'L');
    $pdf->Cell(2, 20, ': ', 0, 0, 'L');

    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    $pdf->SetXY($current_x, $current_y + 8);
    $pdf->MultiCell(90, 5, convert_pangkat($golru_lama) . ', ' . convert_golongan($golru_lama) . ($jabatan_baru != '' ? ', ' . ucwords_strtolower($jabatan_baru) : ''), 0, 'L');

    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 5, '4.', 0, 0, 'L');
    $pdf->Cell(50, 5, 'Unit kerja', 0, 0, 'L');
    $pdf->Cell(0, 5, ': ' . $unit_kerja, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 15, '5.', 0, 0, 'L');
    $pdf->Cell(50, 15, 'Gaji pokok lama', 0, 0, 'L');
    $pdf->Cell(0, 15, ': ' . angka_rupiah($gapok_lama), 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(87);
    $pdf->SetFont('ArialNarrow', 'I', 10);
    $pdf->MultiCell(90, 5, '(' . terbilang_rupiah($gapok_lama) . ')');
    $pdf->SetFont('ArialNarrow', '', 10);

    $pdf->Ln(2);
    $pdf->Cell(35);
    $pdf->MultiCell(165, 5, '(atas dasar surat keputusan terakhir tentang gaji/pangkat yang ditetapkan)');
    $pdf->Ln(5);
    $pdf->Cell(40);
    $pdf->Cell(5, 0, 'a.', 0, 0, 'L');
    $pdf->Cell(40, 0, 'oleh pejabat', 0, 0, 'L');
    $pdf->Cell(2, 0, ': ', 0, 0, 'L');

    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    $pdf->SetXY($current_x, $current_y - 2.5);

    $pdf->MultiCell(70, 5, $jabatan_pejabat_lama, 0, 'L');
    $pdf->Cell(40);
    $pdf->Cell(5, 5, 'b.', 0, 0, 'L');
    $pdf->Cell(40, 5, 'tanggal', 0, 0, 'L');
    $pdf->Cell(0, 5, ': ' . $tgl_sk_lama, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(45);
    $pdf->Cell(40, 15, 'nomor', 0, 0, 'L');
    $pdf->Cell(0, 15, ': ' . $no_sk_lama, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(40);
    $pdf->Cell(5, 25, 'c.', 0, 0, 'L');
    $pdf->Cell(0, 25, 'tanggal mulai', 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(45);
    $pdf->Cell(40, 35, 'berlakunya gaji tersebut', 0, 0, 'L');
    $pdf->Cell(0, 35, ': ' . $tgl_mulai_sk_lama, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(40);
    $pdf->Cell(5, 45, 'd.', 0, 0, 'L');
    $pdf->Cell(0, 45, 'masa kerja golongan', 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(45);
    $pdf->Cell(40, 55, 'pada tanggal tersebut', 0, 0, 'L');
    $pdf->Cell(0, 55, ': ' . $mkg_sk_lama, 0, 0, 'L');


    $pdf->Ln(33);
    $pdf->Cell(30);
    $pdf->SetFont('ArialNarrow', 'BU', 10);
    $pdf->MultiCell(165, 5, 'diberikan kenaikan gaji berkala hingga memperoleh :');
    $pdf->SetFont('ArialNarrow', '', 10);
    $pdf->Ln(5);
    $pdf->Cell(30);
    $pdf->Cell(5, 0, '6.', 0, 0, 'L');
    $pdf->Cell(50, 0, 'Gaji pokok baru', 0, 0, 'L');
    $pdf->Cell(0, 0, ': ' . angka_rupiah($gapok_baru) . '', 0, 0, 'L');

    $pdf->Ln(2);
    $pdf->Cell(87);
    $pdf->SetFont('ArialNarrow', 'I', 10);
    $pdf->MultiCell(90, 5, '(' . terbilang_rupiah($gapok_baru) . ')');
    $pdf->SetFont('ArialNarrow', '', 10);
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 5, '7.', 0, 0, 'L');
    $pdf->Cell(50, 5, 'Berdasarkan masa kerja', 0, 0, 'L');
    $pdf->Cell(0, 5, ': ' . $mkg_sk_baru, 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 15, '8.', 0, 0, 'L');
    $pdf->Cell(50, 15, 'Dalam golongan ruang', 0, 0, 'L');
    $pdf->Cell(0, 15, ': ' . convert_golongan($golru_baru), 0, 0, 'L');
    $pdf->Ln(0);
    $pdf->Cell(30);
    $pdf->Cell(5, 25, '9.', 0, 0, 'L');
    $pdf->Cell(50, 25, 'Mulai tanggal', 0, 0, 'L');
    $pdf->Cell(0, 25, ': ' . $tgl_mulai_sk_baru, 0, 0, 'L');


    $pdf->Ln(17);
    $pdf->Cell(30);
    $pdf->MultiCell(160, 5, '           Diharap agar sesuai dengan ' . $dasar_hukum . ', kepada pegawai tersebut dapat dibayarkan penghasilannya berdasarkan gaji pokoknya yang baru. ');
    $pdf->Ln(5);

    $pdf->SetFont('ArialNarrow', '', 10);

    $kepada = explode(PHP_EOL, $kgb->jabatan_pejabat_baru);
    $n = 0;
    foreach ($kepada as $line) {
        $pdf->Cell(120);
        $pdf->Cell(0, $n, $line, 0, 0, 'C');
        $pdf->Ln(0);
        $n = $n + 10;
    }
    $pdf->Cell(120);
    $pdf->Cell(0, 60, $pejabat_baru, 0, 0, 'C');
    $pdf->Ln(0);
    $pdf->Cell(120);
    $pdf->Cell(0, 70, 'NIP ' . $nip_pejabat_baru, 0, 0, 'C');

    $nama_file = $nip . '_' . $kgb->tgl_mulai_sk_baru . '_' . $nama . '.pdf';
    $pdf->Output($nama_file, 'I');
}
?>
