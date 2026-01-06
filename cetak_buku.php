<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../koneksi.php';

// Load FPDF
require('fpdf184/fpdf.php');

// Create PDF
$pdf = new FPDF();
$pdf->AddPage('L'); // Landscape

// Header
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'LAPORAN DATA BUKU',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Perpustakaan Sekolah',0,1,'C');
$pdf->Cell(0,10,'Tanggal: '.date('d/m/Y'),0,1,'C');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(15,10,'No',1,0,'C',1);
$pdf->Cell(30,10,'ID Buku',1,0,'C',1);
$pdf->Cell(90,10,'Judul Buku',1,0,'C',1);
$pdf->Cell(50,10,'Penulis',1,0,'C',1);
$pdf->Cell(40,10,'Penerbit',1,0,'C',1);
$pdf->Cell(25,10,'Tahun',1,0,'C',1);
$pdf->Cell(20,10,'Stok',1,1,'C',1);

// Data
$pdf->SetFont('Arial','',10);
$query = "SELECT * FROM buku ORDER BY id_buku";
$result = mysqli_query($koneksi, $query);
$no = 1;
$total_stok = 0;

while($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(15,8,$no,1,0,'C');
    $pdf->Cell(30,8,$row['id_buku'],1,0,'C');
    $pdf->Cell(90,8,substr($row['judul'],0,50),1,0,'L');
    $pdf->Cell(50,8,substr($row['penulis'],0,30),1,0,'L');
    $pdf->Cell(40,8,substr($row['penerbit'],0,25),1,0,'L');
    $pdf->Cell(25,8,date('Y',strtotime($row['tahun_terbit'])),1,0,'C');
    $pdf->Cell(20,8,$row['stok'],1,1,'C');
    
    $total_stok += $row['stok'];
    $no++;
}

// Total
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(220,220,220);
$pdf->Cell(250,10,'TOTAL STOK:',1,0,'R',1);
$pdf->Cell(20,10,$total_stok,1,1,'C',1);

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,10,'Halaman '.$pdf->PageNo(),0,0,'C');

// Output
$pdf->Output('I','Laporan_Buku_'.date('Ymd').'.pdf');
?>