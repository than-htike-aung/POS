<?php 
//call hte FPDF library
require('fpdf/fpdf.php');

require_once('connectdb.php');


$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id= $id");
$select->execute();

$row = $select->fetch(PDO::FETCH_OBJ);


// A4 width : 219mm
// default margin : 10mm each side
//writable horizontal : 219-(10*2)=199mm

//create pdf object
$pdf = new FPDF('P' , 'mm' , array(80,200));

$pdf->AddPage();


//set font to airal, bold, 16pt
$pdf->SetFont('Arial','B','16');

//Cell(width, height, text, border, end line, [align])
$pdf->Cell(60,8,'Coder Crazy',1,1,'C');

$pdf->SetFont('Arial','B','8');

$pdf->Cell(60,5,'Address:Progress way, Yankin',0,1,'C');
$pdf->Cell(60,5,'Phone Number:09780560467',0,1,'C');
$pdf->Cell(60,5,'Email : th@gmail.com',0,1,'C');
$pdf->Cell(60,5,'Website:www.coder.com',0,1,'C');

//Line(x1,y1,x2,y2);
$pdf->Line(7,38,72,38);


$pdf->Ln(1); // line break


$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20,4,'Bill To : ', 0,0,'');

$pdf->SetFont('Courier', 'BI',8);
$pdf->Cell(40,4,$row->customer_name,0,1,'');

$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20,4,'Invoice no : ', 0,0,'');

$pdf->SetFont('Courier', 'BI',8);
$pdf->Cell(40,4,$row->invoice_id,0,1,'');

$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20,4,'Date : ', 0,0,'');

$pdf->SetFont('Courier', 'BI',8);
$pdf->Cell(40,4,$row->order_date,0,1,'');

////////
$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);

$pdf->Cell(34,5,'PRODUCT',1,0,'C'); // 190
$pdf->Cell(11,5,'QTY',1,0,'C');
$pdf->Cell(8,5,'PRC',1,0,'C');
$pdf->Cell(12,5,'TOTAL',1,1,'C');

$select = $pdo->prepare("SELECT * FROM tbl_invoice_details WHERE invoice_id= $id");
$select->execute();



while($items = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->Cell(34,5,$items->product_name,1,0,'L'); // 190
    $pdf->Cell(11,5,$items->qty,1,0,'C');
    $pdf->Cell(8,5,$items->price,1,0,'C');
    $pdf->Cell(12,5,$items->price*$items->qty,1,1,'C');
}

//Line(x1,y1,x2,y2);
$pdf->Line(7,38,72,38);


$pdf->Ln(1); // line break

/// Product Table Code
$pdf->SetFont('Arial', 'B', 8);

$pdf->SetX(7);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'SUBTOTAL',1,0,'C');
$pdf->Cell(20,5,$row->subtotal,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'Tax(5%)',1,0,'C');
$pdf->Cell(20,5,$row->tax,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'Discount',1,0,'C');
$pdf->Cell(20,5,$row->discount,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'Total',1,0,'C');
$pdf->Cell(20,5,$row->total,1,1,'C');




$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'PAID',1,0,'C');
$pdf->Cell(20,5,$row->paid,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'DUE',1,0,'C');
$pdf->Cell(20,5,$row->due,1,1,'C');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(20,5,'',0,0,'L'); 
$pdf->Cell(25,5,'PAYMENT TYPE',1,0,'C');
$pdf->Cell(20,5,$row->payment_type,1,1,'C');


$pdf->Cell(20,5,'',0,1,'');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B',8);
$pdf->Cell(25,5,'Important Notice :',0,1,'');

$pdf->SetX(7);
$pdf->SetFont('Courier', '',5);
$pdf->Cell(75,5,'No item will be replaced or refunded if you don\'t have the invoice with you.',0,2,'');

$pdf->SetX(7);
$pdf->SetFont('Courier', '',5);
$pdf->Cell(75,5,'You can refund within 2days of purchase',0,1,'');



$pdf->Output();
?>