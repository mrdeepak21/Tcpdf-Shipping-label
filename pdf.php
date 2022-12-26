<?php
    require_once('../wp-load.php');
if(isset($_POST) && isset($_POST['order_number']) && is_user_logged_in()) {
ob_end_clean();
$order_no = sanitize_text_field($_POST['order_number']);
if(get_post_type($order_no) != "shop_order"){
echo "<b>Invalid Order Number!</b>";
die;
exit;
}
$order = wc_get_order($order_no);

$full_name = $order->get_billing_first_name()." ".$order->get_billing_last_name();
$address = $order->get_billing_address_1()." ".$order->get_billing_address_2();
$city = $order->get_billing_city();
$state = WC()->countries->get_states('IN')[$order->get_billing_state()];
$pincode = $order->get_billing_postcode();
$phone = $order->get_billing_phone();
$payment_mode = $order->get_payment_method_title();
$payment_method = $order->get_payment_method();
$collect = $payment_method =='cod' ? "[COLLECT CASH ON DELIVERY]" : '';
$total = $order->get_total();
$collect_cash = $payment_method=='cod'?$total: '0.00';
$no_of_box = 1;
$weight = sanitize_text_field($_POST['weight']);
$awb_no = 'MH0000091792631';
$branch_code = '';

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
<td>'.printBarcode($awb_no,3,10).'<br><br></td><td rowspan="2"><img src="logo.png" width="100" height="100"></td>
</tr>
<tr>
<td> <b>AWB NO: '.$awb_no.'  <br>'.$collect.'</br></td>
</tr>
<tr>
<td>Branch Code: '.$branch_code.'</td><td><b>COLLECT CASH: '.$collect_cash.'</b></td>
</tr>
<tr>
<td rowspan="3"><b>SHIPPING ADDRESS</b><br>Name: <b>'.$full_name.'</b><br>Address: <b>'.$address.'</b><br>City: <b>'.$city.'</b><br>State: <b>'.$state.'</b><br>Pincode: <b>'.$pincode.'</b><br>Contact: <b>'.$phone.'</b><br></td><td>'.printBarcode($awb_no,106,50).'<br><br></td>
</tr>
<tr>
<td class="text-center">NO. OF BOX <br><b>'.$no_of_box.'</b></td>
</tr>
<tr>
<td class="text-center">WEIGTH kg.<br><b>['.$weight.']</b></td>
</tr>
</table>
<table border="1" cellspacing="0" cellpadding="5">
<tr>
<th>Sr No.</th><th>Order No.</th><th>Invoice No.</th><th>Amount</th><th>Mode Of Payment</th>
</tr>
<tr>
<td>1</td><td>'.$order_no.'<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td>AFL/01688/2223</td><td>'.$total.'</td><td>'.$payment_mode.'</td>
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
}
else {
    echo "<script>window.close()</script>";
}