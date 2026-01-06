<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../koneksi.php';

$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

// Filter
$filter = "";
$periode = "Semua Periode";
if(!empty($dari) && !empty($sampai)) {
    $filter = "WHERE p.tanggal_pinjam BETWEEN '$dari' AND '$sampai'";
    $periode = date('d/m/Y', strtotime($dari)) . " - " . date('d/m/Y', strtotime($sampai));
}

require('fpdf184/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage('L');

// Header
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'LAPORAN PEMINJAMAN BUKU',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Perpustakaan Sekolah',0,1,'C');
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,'Periode: '.$periode,0,1,'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(15,10,'No',1,0,'C',1);
$pdf->Cell(25,10,'ID Pinjam',1,0,'C',1);
$pdf->Cell(30,10,'Tanggal',1,0,'C',1);
$pdf->Cell(60,10,'Anggota',1,0,'C',1);
$pdf->Cell(80,10,'Buku',1,0,'C',1);
$pdf->Cell(30,10,'Status',1,0,'C',1);
$pdf->Cell(40,10,'Denda (Rp)',1,1,'C',1);

// Data
$pdf->SetFont('Arial','',9);
$query = "SELECT p.*, a.nama as nama_anggota, b.judul, peng.denda
          FROM peminjaman p
          JOIN anggota a ON p.id_anggota = a.id_anggota
          JOIN buku b ON p.id_buku = b.id_buku
          LEFT JOIN pengembalian peng ON p.id_pinjam = peng.id_pinjam
          $filter
          ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
$no = 1;
$total_denda = 0;

while($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(15,8,$no,1,0,'C');
    $pdf->Cell(25,8,$row['id_pinjam'],1,0,'C');
    $pdf->Cell(30,8,date('d/m/Y',strtotime($row['tanggal_pinjam'])),1,0,'C');
    $pdf->Cell(60,8,substr($row['nama_anggota'],0,30),1,0,'L');
    $pdf->Cell(80,8,substr($row['judul'],0,45),1,0,'L');
    $pdf->Cell(30,8,ucfirst($row['status']),1,0,'C');
    $pdf->Cell(40,8,number_format($row['denda'],0,',','.'),1,1,'R');
    
    $total_denda += $row['denda'];
    $no++;
}

// Total
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(220,220,220);
$pdf->Cell(240,10,'TOTAL DENDA:',1,0,'R',1);
$pdf->Cell(40,10,'Rp '.number_format($total_denda,0,',','.'),1,1,'R',1);

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Dicetak: '.date('d/m/Y H:i:s').' oleh '.$_SESSION['username'],0,0,'C');

$pdf->Output('I','Laporan_Peminjaman_'.date('Ymd').'.pdf');
?>