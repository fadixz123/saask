<?php

/*  Surat Pemberitahuan Kenaikan Gaji Berkala (KGB)
 *  Badan Kepegawaian Negara
 *  Creator : Teddy Cahyo Munanto
 *  Email   : teddcm@gmail.com
 */
include 'plugins/fpdf/fpdf.php';

// Instanciation of inherited class
class PDF extends FPDF {

    function Header() {
        // Logo
        /*
          $this->Image('bootstrap/img/logo_sk.jpg', 35, 8, 20);
          $this->SetFont('Times', '', 10);
          $this->Cell(70, 48, 'BADAN KEPEGAWAIAN NEGARA', 0, 0, 'C');
          $this->Ln(0);
          $this->Cell(70, 58, 'KANTOR REGIONAL VIII', 0, 0, 'C');
          $this->Ln(0);
          $this->Cell(70, 68, 'BANJARMASIN', 0, 0, 'C'); */
    }

    function Footer() {
        
    }

}

$pdf = new PDF('P', 'mm', array(210, 330));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(0);

$pdf->SetFont('Times', '', 10);

// VARIABEL
$sifat = 'Penting';
$lampiran = '-';
$perihal = 'Kenaikan Gaji Berkala';
$kota = 'Banjarbaru';

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 0, 'BIODATA PEGAWAI', 0, 0, 'C');
$pdf->Ln(0);
$pdf->Cell(0, 10, 'KANTOR REGIONAL VIII', 0, 0, 'C');
$pdf->Ln(0);
$pdf->Cell(0, 20, 'BADAN KEPEGAWAIAN NEGARA', 0, 0, 'C');
$pdf->Line(15, 25, 210 - 15, 25); // 50mm from each edge
$pdf->Line(15, 24, 210 - 15, 24); // 50mm from each edge
$pdf->Image('downloads/foto/' . ($is_file_exist ? $file : 'nofoto.jpg'), 165, 27, 30);


$data = $data_pegawai;
$pdf->SetFont('Times', '', 10);
$pdf->Ln(10);

$pdf->Cell(5);
$pdf->Cell(30, 20, 'Nama', 0, 0, 'L');
$pdf->Cell(1, 20, ': ' . $data->nama, 0, 0, 'L');
$pdf->Ln(0);

$pdf->Cell(5);
$pdf->Cell(30, 30, 'NIP', 0, 0, 'L');
$pdf->Cell(1, 30, ': ' . $data->nip, 0, 0, 'L');
$pdf->Ln(0);

$pdf->Cell(5);
$pdf->Cell(30, 40, 'Pangkat/Golongan', 0, 0, 'L');
$pdf->Cell(1, 40, ': ' . convert_pangkat($data->gol_akhir) . ', ' . convert_golongan($data->gol_akhir), 0, 0, 'L');
$pdf->Ln(0);

$pdf->Cell(5);
$pdf->Cell(30, 50, 'Jabatan', 0, 0, 'L');
$pdf->Cell(1, 50, ': ' . ucwords_strtolower($data->nama_jab), 0, 0, 'L');
$pdf->Ln(0);

$pdf->Cell(5);
$pdf->Cell(30, 60, 'Unit Kerja', 0, 0, 'L');
$pdf->Cell(1, 60, ': ' . $data->nama_bag, 0, 0, 'L');
$pdf->Ln(0);


$nama_file = $data->nip . '_' . $data->nama  . '.pdf';
$pdf->Output($nama_file, 'I');
?>
