<?php 
App::import('Vendor','xtcpdf');  
$tcpdf = new XTCPDF(); 
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 

$tcpdf->SetAuthor(""); 
$tcpdf->SetAutoPageBreak( false ); 
$tcpdf->setHeaderFont(array($textfont,'',40)); 
$tcpdf->xheadercolor = array(150,0,0); 
$tcpdf->xheadertext = ''; 
$tcpdf->xfootertext = ''; 

// add a page (required with recent versions of tcpdf) 
$tcpdf->AddPage(); 

$tcpdf->Cell(0,14, "Doremus tu es a chier", 0,1,'L'); 
// ... 
// etc. 
// see the TCPDF examples  

echo $tcpdf->Output('filename.pdf', 'D'); 

?> 
