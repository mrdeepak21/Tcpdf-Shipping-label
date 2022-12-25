<?php

ob_end_clean();
$order_no = 'Order-0125';

// Include the main TCPDF library (search for installation path).
require_once('vendor/tcpdf.php');


 // Page footer
 class MYPDF extends TCPDF {
    public function Footer() {
        $y=288;$x=275;
       // Page number
       $this->setFont('','',10);
       $this->setY(-22);
       $this->Line(0, $x, 250, $x);
       $this->Cell(0, 0, 'Shipped By (If undelivered , return to)', 0, 1, 'L', 0, '', 0, false, 'T', 'M');
       $this->Cell(0, 0, 'BAPS Swaminarayan Mandir, 2nd Floor, Pramukhswami Maharaj Marg, Datta Mandir Rd, next to Sharda high school, Malad East,', 0, 1, 'L', 0, '', 0, false, 'T', 'M');
       $this->Cell(0, 0, 'Mumbai, Maharashtra - 400097 Customer Care: 18008900990', 0, 1, 'L', 0, '', 0, false, 'T', 'M');
       $this->Line(0, $y, 250, $y);
       $this->setFont('','',8);
       $this->Cell(0, 0, 'THIS IS AN AUTO-GENERATED LABEL AND DOES NOT NEED SIGNATURE', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
       $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
   }
    }
   
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('NativFarms');
$pdf->SetAuthor('NativFarms');
$pdf->SetTitle($order_no.'-Shipping-Label');
$pdf->SetSubject('Shipping Label');

// remove default header
$pdf->setPrintHeader(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set margins
$pdf->SetMargins(2, 2, 2);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 8);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

function printBarcode($code,$x,$y)
{
    global $pdf;
    $style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => false,
    );
$barcode = $pdf->write1DBarcode($code, 'C39+', $x, $y, 100, 14, 0.4, $style, 'C');
}

// Set some content to print
$html = '
<style>.text-center{text-align:center}</style>
<table border="1" cellspacing="0" cellpadding="5">
<tr class="text-center">
<td>VICHARE</td><td>NATIVFARMS.COM</td>
</tr>
<tr class="text-center">
<td>'.printBarcode('MH1236589',5,10).'<br><br></td><td rowspan="2"><img src="logo.png" width="100" height="100"></td>
</tr>
<tr>
<td> <b>AWB NO: MH0000091792631  <br>[COLLECT CASH ON DELIVARY]</br></td>
</tr>
<tr>
<td>Branch Code: </td><td><b>COLLECT CASH: 0.00</b></td>
</tr>
<tr>
<td rowspan="3"><b>SHIPPING ADDRESS</b><br>Name: <b>BHAVANA THAKKAR</b><br>Address: <b>B-201 Salpadevi Sadan CHS P K Road St Merry School Mulund West</b><br>City: <b>Mulund West</b><br>State: <b>Maharashtra</b><br>Pincode: <b>400080</b><br>Contact: <b>8779197200</b><br></td><td>'.printBarcode('MH1236589',110,50).'<br><br></td>
</tr>
<tr>
<td class="text-center">NO. OF BOX <br><b>1</b></td>
</tr>
<tr>
<td class="text-center">WEIGTH kg.<br><b>[1]</b></td>
</tr>
</table>
<table border="1" cellspacing="0" cellpadding="5">
<tr>
<th>Sr No.</th><th>Order No.</th><th>Invoice No.</th><th>Amount</th><th>Mode Of Payment</th>
</tr>
<tr>
<td>1</td><td>17475</td><td>AFL/01688/2223</td><td>2400</td><td>COD</td>
</tr>
</table>';

// echo $html;
// exit;

// Print text using writeHTMLCell()
// $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->writeHTML($html,true,false,false,true,'L');


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($order_no.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+