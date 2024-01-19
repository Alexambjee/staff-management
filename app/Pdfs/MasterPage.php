<?php

namespace App\Pdfs;

use Codedge\Fpdf\Fpdf\Fpdf;

class MasterPage extends Fpdf
{

  protected $visiblity;
  protected $fontFamily;
  protected $fontSize;

  public function __construct($visiblity, $fontFamily, $fontSize)
  {
    $this->visiblity = $visiblity;
    $this->fontFamily = $fontFamily;
    $this->fontSize = $fontSize;

    parent::__construct();
  }

  function Header()
  {

    $pageHeight = $this->GetPageHeight();
    $this->Image(public_path() . '/images/kra.logo.png', 55, 5, 100, 0, 'PNG');
    $this->Image(public_path() . '/images/pdf-strip.png', 5, 0, 10, $pageHeight, 'PNG');
    $this->SetFont('Times', 'B', 12, 'R');
    // $this->Text(165, 15, $this->visiblity);
    $this->Text(165, 15, 'CONFIDENTIAL');
    $this->Ln(20);
  }

  function Footer()
  {
    $this->SetY(-20);


    $this->SetFont('Arial', 'BI', 12);
    $this->SetTextColor(255, 0, 0);
    $this->Cell(0, 10,  'Tulipe Ushuru Tujitegemee!', 0, 1, 'C');

    $this->SetFont('Arial', 'I', 10);
    $this->SetTextColor(0, 0, 0);
    $this->Cell(0, 10,  'This is a system generated document and does not require a signature', 0, 1, 'C');
  }
}
