<?php

/*  Surat Pemberitahuan Kenaikan Gaji Berkala (KGB)
 *  Badan Kepegawaian Negara
 *  Creator : Teddy Cahyo Munanto
 *  Email   : teddcm@gmail.com
 */
include 'bootstrap/plugins/fpdf/fpdf.php';

// Instanciation of inherited class
class PDF extends FPDF {

    function Header() {
        $this->SetFont('Times', 'B', 12);

        $this->Image('bootstrap/img/logo_sk.jpg', 20, 6, 15);
        $this->Cell(0, 0, 'AGENDA SURAT KELUAR', 0, 0, 'C');
        $this->Ln(0);
        $this->Cell(0, 10, 'KANTOR REGIONAL VIII', 0, 0, 'C');
        $this->Ln(0);
        $this->Cell(0, 20, 'BADAN KEPEGAWAIAN NEGARA', 0, 0, 'C');
        $this->CreateLine(25);
        $this->CreateLine(24);

        $this->Ln(15);
        $this->SetFont('Times', 'B', 9);
        $this->Cell(-6);
        $this->Cell(6, 8, 'No', 0, 0, 'L');
        $this->Cell(30, 8, 'No Surat', 0, 0, 'L');
        $this->Cell(15, 8, 'Tgl Masuk', 0, 0, 'L');
        $this->Cell(15, 8, 'Tgl Surat', 0, 0, 'L');
        $this->Cell(70, 8, 'Perihal', 0, 0, 'L');
        $this->Cell(60, 8, 'Tujuan', 0, 0, 'L');
        $this->Cell(45, 8, 'Penandatangan', 0, 0, 'L');
        $this->Cell(30, 8, 'Jenis', 0, 0, 'L');
        $this->Cell(0, 8, 'User', 0, 1, 'L');
        $this->CreateLine(33);
        $this->Ln(0);
    }

    function Footer() {
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($label) {
        // Arial 12
        $this->Cell(-6);
        $this->SetFont('Arial', '', 10);
        // Background color
        $this->SetFillColor(200, 220, 255);
        // Title
        $this->Cell(297 - 10, 6, $label, 0, 1, 'L', true);
        // Line break
        $this->SetFont('Times', '', 7);
    }

    function CreateLine($y) {
        $this->Line(4, $y, 297 - 6, $y); // 50mm from each edge
    }

    function CountJenis($label) {
        // Arial 12
        $this->Ln(1);
        $this->Cell(260);
        $this->SetFont('Arial', '', 8);
        // Background color
        $this->SetFillColor(200, 220, 220);
        // Title
        $this->Cell(21, 6, 'Total : ' . $label, 0, 1, 'C', true);
        // Line break
        $this->SetFont('Times', '', 7);
    }

}

$margin = 5;
$pdf = new PDF('L', 'mm', array(210, 297));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 7);


$data = $data_outbox;
$i = 0;
$nama_bagian = '';
if (!empty($data_outbox)) {
    foreach ($data_outbox as $outbox) {

        if ($nama_bagian != $outbox->nama_bagian) {
            if ($i > 0) {
                $pdf->CountJenis($i);
            }

            $i = 0;
            if ($nama_bagian != '') {
                $pdf->AddPage();
            }
            $nama_bagian = $outbox->nama_bagian;
            $pdf->ChapterTitle($nama_bagian);
        }
        if ($pdf->GetY() >= 175) {
            $pdf->AddPage();
        }
        $i++;
        $pdf->Cell(-6);
        $pdf->Cell(6, $margin, $i, 0, 0);
        $pdf->Cell(30, $margin, $outbox->no_surat, 0, 0);
        $pdf->Cell(15, $margin, date("d-m-Y", strtotime($outbox->tgl_masuk)), 0, 0);
        $pdf->Cell(15, $margin, date("d-m-Y", strtotime($outbox->tgl_surat)), 0, 0);

        $current_x1 = $pdf->GetX();
        $current_y1 = $pdf->GetY();
        $pdf->Cell(90);

        $current_x2 = $pdf->GetX();
        $current_y2 = $pdf->GetY();
        $pdf->Cell(50);

        $current_x3 = $pdf->GetX();
        $current_y3 = $pdf->GetY();
        $pdf->Cell(35);

        $pdf->Cell(30, $margin, $outbox->nama_jenis, 0, 0);
        $pdf->Cell(0, $margin, $outbox->first_name, 0, 0);

        $pdf->SetXY($current_x3, $current_y3);
        $pdf->MultiCell(35, $margin, $outbox->ptt, 0, 'L');
        $y3 = $pdf->getY();

        $pdf->SetXY($current_x2, $current_y2);
        $pdf->MultiCell(50, $margin, $outbox->tujuan, 0, 'L');
        $y2 = $pdf->getY();

        $pdf->SetXY($current_x1, $current_y1);
        $pdf->MultiCell(90, $margin, $outbox->perihal, 0, 'L');
        $y1 = $pdf->getY();

        $pdf->setY(max($y1, $y2, $y3));
        $pdf->CreateLine($pdf->GetY());
        if ($outbox == end($data_outbox)) {
            $pdf->CountJenis($i);
        }
    }
}

$nama_file = get_user_name() . '_' . date(now());
$pdf->Output($nama_file, 'I');
?>
