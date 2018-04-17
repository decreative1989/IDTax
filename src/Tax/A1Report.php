<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 17/04/2018
 * Time: 14.22
 */

namespace QueenApp\Tax\A1Report;

use TCPDF;


class A1Report
{
    private $arrDataA1Employee = [];
    private $strYear;
    private $strYearSign; # Tanggal SPT di Cetak & Ditandatangani, Default akhir tahun masa berjalan
    private $strMonthSign = '12';
    private $strDaySign = '01';
    private $strChecklist = 'X'; # Tanda untuk Box Ceklis di A1
    private $strLogoPajak = '../assets/img/logo_pajak.jpg';
    private $strPDFCreator = 'QueenApp - IDTax';
    private $strPDFAuthor = 'ADE SANUSI';
    private $strPDFTitle = 'A1 REPORT TAX';
    private $strPDFSubject = 'A1 REPORT TAX';
    private $strPDFKeywords = 'Report, A1, Pph 21';
    private $bolPassword = false;
    private $strPassword = '1QaZxSw23EdC';
    private $bolDownloadPDF = true;
    private $strPDFFileName = 'A1_Report';
    private $multiplePage = false; # Default False saja karena belum ditambahkan checkbox untuk multi selectnya di grid.

    public function __construct($strYear)
    {
        $this->strYear = $strYear;
        $this->strYearSign = $strYear;
    }

    public function setYearSign($strYear = '')
    {
        if (!empty($strYear)) {
            $this->strYearSign = $strYear;
        }
    }

    public function setMonthSign($strMonth = '')
    {
        if (!empty($strMonth)) {
            $this->strMonthSign = $strMonth;
        }
    }

    public function setDaySign($strDay = '')
    {
        if (!empty($strDay)) {
            $this->strDaySign = $strDay;
        }
    }


    public function doGenerateA1Report()
    {
        $this->generateA1Report();
    }

    public function SetPassword($bolEnable = false, $strPassword = '')
    {
        if (!empty($strPassword)) {
            $this->bolPassword = $bolEnable;
            $this->strPassword = $strPassword;
        }
    }

    public function SetLogoPajak($strLogo)
    {
        $this->strLogoPajak = $strLogo;
    }

    public function SetDownloadPDF($bolDownload = false)
    {
        if ($bolDownload === true) {
            $this->bolPassword = true;
        }
    }

    public function setDataA1Employee($arrData = [])
    {
        if (!empty($arrData)) {
            $this->arrDataA1Employee = $arrData;
        }
    }

    public function getDataA1Employee()
    {
        return $this->arrDataA1Employee;
    }

    public function setMultiplePage($bolMultipage = true)
    {
        $this->multiplePage = $bolMultipage;
    }

    /**
     * @param $value
     * @param int $downto
     * @return float
     */
    private function roundDown($value, $downto = 0)
    {
        $parameter = pow(10, $downto);
        return floor($value / $parameter) * $parameter;
    }

    private function generateA1Report()
    {
        if (is_array($this->arrDataA1Employee) and !empty($this->arrDataA1Employee)) {

            # CREATE HEADER & KEYWORD
            ob_start();
            #create new PDF document
            $pdf = new \TCPDF("P", PDF_UNIT, "F4", true, "UTF-8", false);

            #set document information
            $pdf->SetCreator($this->strPDFCreator);
            $pdf->SetAuthor($this->strPDFAuthor);
            $pdf->SetTitle($this->strPDFTitle);
            $pdf->SetSubject($this->strPDFSubject);
            $pdf->SetKeywords($this->strPDFKeywords);

            #remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            #set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            #set margins
            $pdf->SetMargins(5.32, 6, 5.32);

            #set auto page breaks
            $pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

            #set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            if ($this->bolPassword === true) {
                #set password pdf
                $pdf->SetProtection(["copy"], $this->strPassword, null, 0, null);
            }

            #$this->pdf->AddFont('rotisserifi56', '', 'rotisserifi56.php');
            #$this->pdf->SetFont('rotisserifi56');
            # CUSTOM FONT KE ARIAL TCPDF DEFAULTNYA TIDAK ADA ARIAL
            $pdf->AddFont("Arial", "", "../assets/fonts/arial.php");
            $pdf->AddFont("Arial", 'B', "../assets/fonts/arialbd.php");



            # END CREATE HEADER & KEYWORD
            foreach ($this->arrDataA1Employee as $idEmployee => $arrDetail) {

                # GENERATE A1 REPORT SESUAI ARRAY TOTAL EMPLOYEE YANG TERPILIH
                // Add a page
                $pdf->AddPage();

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont("Arial", "B", 9.7);
                $pdf->Cell(0, 0, ' a r e a   s t a p l e s', 0, 0, 'L', 0, '', 0);
                $pdf->Ln(3);

                $style = array('width' => 0.25, 'cap' => 'round', 'join' => 'round', 'dash' => 3, 'color' => array(0, 0, 0));
                $pdf->Line(5.4, 11, 204, 11, $style);


                # RECTANGLE DI SETIAP POJOK KERTAS
                $pdf->Rect(8.205, 11.24, 6.1, 2.7, 'DF', '', array(0, 0, 0)); # Kiri Atas
                $pdf->Rect(195.667, 11.24, 6.1, 2.7, 'DF', '', array(0, 0, 0)); # Kanan Atas
                $pdf->Rect(8.205, 320.569, 6.1, 2.7, 'DF', '', array(0, 0, 0)); # Kiri Bawah
                $pdf->Rect(195.667, 320.569, 6.1, 2.7, 'DF', '', array(0, 0, 0)); # Kanan Bawah
                # END RECTANGLE DI SETIAP POJOK KERTAS

                # GENERATE LINE DENGAN KETEBALAN 0.6 mm
                $style = array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                $pdf->Line(64.296, 13.856, 64.296, 49, $style);
                $pdf->Line(143.544, 13.949, 143.544, 38.333, $style);
                $pdf->Line(64.289, 38.333, 161.835, 38.333, $style);
                $pdf->Line(161.835, 38.333, 161.835, 49, $style);

                $pdf->Rect(7.908, 49, 193.463, 20.743, 'D', array('all' => $style)); # Box Company Pemotong.
                $pdf->Rect(7.908, 77.576, 193.463, 46.736, 'D', array('all' => $style)); # Box A. Identitas Penerima Penghasilan.
                $pdf->Rect(7.908, 293.476, 193.463, 21.929, 'D', array('all' => $style)); # Box C. Identitas Pemotong.

                $style = array('width' => 0.29, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                #$hRows = 6; #5.757
                $wRows = 51.181;
                $xRows = 150.200;
                #$yRows = 139.241;
                $wRows2 = 142.250;
                $xRows2 = 7.908;

                $boxWidth = 193.463;

                $pdf->Rect(162.002, 102.976, 6.1, 5.08, 'D', array('all' => $style)); # Box Karyawan Asing
                $pdf->Rect(48.250, 116.184, 6.1, 5.08, 'D', array('all' => $style)); # Box Laki-laki
                $pdf->Rect(79.706, 116.184, 6.1, 5.08, 'D', array('all' => $style)); # Box Perempuan
                $pdf->Rect(43.13, 138.663, 6.1, 5.08, 'D', array('all' => $style)); # Box 21-100-01
                $pdf->Rect(67.514, 138.663, 6.1, 5.08, 'D', array('all' => $style)); # Box 21-100-02

                $pdf->Rect($xRows2, 132.059, 193.463, 153.247, 'D', array('all' => $style)); # Box No Background
                $pdf->Rect(158.954, 296.651, 38.269, 15.621, 'D', array('all' => $style)); # Box No Background
                $pdf->Rect($xRows2, 137.774, $wRows2, 7.197, 'D', array('all' => $style), array(217, 217, 217)); # Box Background
                $pdf->Rect($xRows, 137.774, $wRows, 7.197, 'DF', array('all' => $style), array(217, 217, 217)); # Box Background
                $pdf->Rect($xRows, 144.801, $wRows, 6.054, 'DF', array('all' => $style), array(217, 217, 217)); # Box Background
                $pdf->Rect($xRows, 200.258, $wRows, 6.054, 'DF', array('all' => $style), array(217, 217, 217)); # Box Background
                $pdf->Rect($xRows, 224.473, $wRows, 6.054, 'DF', array('all' => $style), array(217, 217, 217)); # Box Background

                #Vertikal Line
                $pdf->Line(14.174, 151.151, 14.174, 200.258, $style);
                $pdf->Line(14.174, 206.608, 14.174, 224.473, $style);
                $pdf->Line(14.174, 230.823, 14.174, 285.009, $style);
                $pdf->Line($xRows, 149.81, $xRows, 285.009, $style);

                #Horizontal Line
                $pdf->Line($xRows2, 137.774, $xRows2 + $boxWidth, 137.774, $style);
                $pdf->Line($xRows2, 144.801, $xRows2 + $boxWidth, 144.801, $style);
                $pdf->Line($xRows2, 150.855, $xRows2 + $boxWidth, 150.855, $style);
                $pdf->Line($xRows2, 156.909, $xRows2 + $boxWidth, 156.909, $style);
                $pdf->Line($xRows2, 162.962, $xRows2 + $boxWidth, 162.962, $style);
                $pdf->Line($xRows2, 169.016, $xRows2 + $boxWidth, 169.016, $style);
                $pdf->Line($xRows2, 175.07, $xRows2 + $boxWidth, 175.07, $style);
                $pdf->Line($xRows2, 181.123, $xRows2 + $boxWidth, 181.123, $style);
                $pdf->Line($xRows2, 188.151, $xRows2 + $boxWidth, 188.151, $style);
                $pdf->Line($xRows2, 194.204, $xRows2 + $boxWidth, 194.204, $style);
                $pdf->Line($xRows2, 200.258, $xRows2 + $boxWidth, 200.258, $style);
                $pdf->Line($xRows2, 206.312, $xRows2 + $boxWidth, 206.312, $style);
                $pdf->Line($xRows2, 212.365, $xRows2 + $boxWidth, 212.365, $style);
                $pdf->Line($xRows2, 218.419, $xRows2 + $boxWidth, 218.419, $style);
                $pdf->Line($xRows2, 224.473, $xRows2 + $boxWidth, 224.473, $style);
                $pdf->Line($xRows2, 230.526, $xRows2 + $boxWidth, 230.526, $style);
                $pdf->Line($xRows2, 236.58, $xRows2 + $boxWidth, 236.58, $style);
                $pdf->Line($xRows2, 242.638, $xRows2 + $boxWidth, 242.638, $style);
                $pdf->Line($xRows2, 248.687, $xRows2 + $boxWidth, 248.687, $style);
                $pdf->Line($xRows2, 254.741, $xRows2 + $boxWidth, 254.741, $style);
                $pdf->Line($xRows2, 260.795, $xRows2 + $boxWidth, 260.795, $style);
                $pdf->Line($xRows2, 266.848, $xRows2 + $boxWidth, 266.848, $style);
                $pdf->Line($xRows2, 272.902, $xRows2 + $boxWidth, 272.902, $style);
                $pdf->Line($xRows2, 278.956, $xRows2 + $boxWidth, 278.956, $style);


                $pdf->Line(37.161, 85.238, 79.875, 85.238, $style);
                $pdf->Line(82.881, 85.238, 95.115, 85.238, $style);
                $pdf->Line(98.121, 85.238, 110.355, 85.238, $style);

                $pdf->Line(37.161, 94.552, 110.355, 94.552, $style);
                $pdf->Line(124.029, 94.552, 134.739, 94.552, $style);
                $pdf->Line(149.937, 94.552, 159.123, 94.552, $style);
                $pdf->Line(174.321, 94.552, 184.481, 94.552, $style);

                $pdf->Line(37.161, 101.156, 110.355, 101.156, $style);
                $pdf->Line(149.937, 101.156, 197.096, 101.156, $style);

                $pdf->Line(37.161, 107.76, 110.355, 107.76, $style);

                $pdf->Line(37.161, 114.364, 110.355, 114.364, $style);
                $pdf->Line(162.002, 114.364, 171.315, 114.364, $style);

                $pdf->Line(37.161, 300.757, 76.827, 300.757, $style);
                $pdf->Line(79.833, 300.757, 89.019, 300.757, $style);
                $pdf->Line(92.025, 300.757, 101.211, 300.757, $style);

                $pdf->Line(37.161, 307.869, 101.211, 307.869, $style);
                $pdf->Line(107.265, 307.869, 119.499, 307.869, $style);
                $pdf->Line(122.505, 307.869, 134.739, 307.869, $style);
                $pdf->Line(137.745, 307.869, 156.075, 307.869, $style);
                # END GENERATE LINE DENGAN KETEBALAN 0.6 mm


                # FONT BOLD DAN UKURAN 8.8
                $pdf->SetFont("Arial", "B", 8.8);
                $pdf->Text(69.95, 14.864, 'BUKTI PEMOTONGAN PAJAK PENGHASILAN');
                $pdf->Text(74.494, 18.801, 'PASAL 21 BAGI PEGAWAI TETAP ATAU ');
                $pdf->Text(69.816, 22.885, 'PENERIMA PENSIUN ATAU TUNJANGAN HARI');
                $pdf->Text(77.868, 26.907, 'TUA/JAMINAN HARI TUA BERKALA');

                $pdf->Text(12.932, 39.883, 'KEMENTERIAN KEUANGAN RI');
                $pdf->Text(11.421, 44.122, 'DIREKTORAT JENDERAL PAJAK');

                $pdf->Text(8.84, 73.347, 'A. IDENTITAS PENERIMA PENGHASILAN YANG DIPOTONG');
                $pdf->Text(8.84, 128.126, 'B. RINCIAN  PENGHASILAN DAN PENGHITUNGAN PPh PASAL 21');
                $pdf->Text(8.84, 289.247, 'C. IDENTITAS PEMOTONG');
                # END FONT BOLD DAN UKURAN 8.8

                # FONT BOLD DAN UKURAN 7.815
                $pdf->SetFont("Arial", "B", 7.815);
                $pdf->Text(73.988, 133.043, 'URAIAN');
                $pdf->Text(167.548, 133.043, 'JUMLAH (Rp)');
                $pdf->Text(10.313, 139.388, 'KODE PAJAK            :');
                $pdf->Text(44.500, 139.388, $this->strChecklist);
                $pdf->Text(51.348, 139.388, '21-100-01');
                $pdf->Text(75.074, 139.388, '21-100-02');

                $pdf->Text(10.313, 146.876, 'PENGHASILAN BRUTO :');
                $pdf->Text(10.313, 202.333, 'PENGURANGAN :');
                $pdf->Text(10.313, 226.548, 'PENGHITUNGAN PPh PASAL 21 :');
                $pdf->Text(10.313, 297.564, '1.  NPWP   :');
                $pdf->Text(10.313, 304.676, '2.  NAMA   :');
                $pdf->Text(107.909, 297.752, '3. TANGGAL & TANDA TANGAN');
                $pdf->Text(111.846, 309, '[dd-mm-yyyy]');

                $strNPWPEmployee = $this->arrDataA1Employee[$idEmployee]['npwp']; #Harus FormatNPWP jika tidak ada NPWP dibuat 00.000.000.0-000.000
                $pdf->Text(51.327, 81, substr($strNPWPEmployee, 0, 12));
                $pdf->Text(80, 81, '-');
                $pdf->Text(87, 81, substr($strNPWPEmployee, 13, 3));
                $pdf->Text(96.15, 81, '.');
                $pdf->Text(102, 81, substr($strNPWPEmployee, -3));

                $strIDCardEmployee = $this->arrDataA1Employee[$idEmployee]['id_card'];
                $pdf->Text(37, 91, $strIDCardEmployee);

                $strEmployeeName = $this->arrDataA1Employee[$idEmployee]['employee_name'];
                $pdf->Text(37, 97, $strEmployeeName);

                $strEmployeeJobTitle = $this->arrDataA1Employee[$idEmployee]['position'];
                $pdf->Text(150, 97, substr($strEmployeeJobTitle, 0, 25));

                $strEmployeeAddress = $this->arrDataA1Employee[$idEmployee]['address'];
                $pdf->Text(37, 104, substr($strEmployeeAddress, 0, 40));
                $pdf->Text(37, 111, substr($strEmployeeAddress, 41, strlen($strEmployeeAddress) - 40));

                $strGender = $this->arrDataA1Employee[$idEmployee]['gender']; #0 = Perempuan
                $strChildren = $this->arrDataA1Employee[$idEmployee]['children'];
                $strTaxStatus = $this->arrDataA1Employee[$idEmployee]['tax_status']; # S, TK, M, K
                $strTaxStatus = substr(strtoupper($strTaxStatus), 0, 1);

                if ($strGender === 0) {
                    $pdf->Text(80.35, 117, 'X'); # Box Perempuan
                    $pdf->Text(152.5, 91, $strChildren); # Jika Perempuan selalu di TK
                } else {
                    $pdf->Text(48.85, 117, 'X'); # Box Laki-laki
                    if ($strTaxStatus === 'K' || $strTaxStatus == 'M') {
                        $pdf->Text(128, 91, $strChildren); # Lokasi Chirdren di K (A.07)
                    } else {
                        $pdf->Text(152.5, 91, $strChildren); # Lokasi Chirdren di TK (A.08)
                    }
                }


                $strSignNPWP = $this->arrDataA1Employee[$idEmployee]['sign_npwp']; #Harus FormatNPWP jika tidak ada NPWP dibuat 00.000.000.0-000.000
                $pdf->Text(50, 297.25, substr($strSignNPWP, 0, 12));
                $pdf->Text(77, 297.25, '-');
                $pdf->Text(80.5, 297.25, substr($strSignNPWP, 13, 3));
                $pdf->Text(89.5, 297.25, '.');
                $pdf->Text(93, 297.25, substr($strSignNPWP, -3));

                $strSignName = $this->arrDataA1Employee[$idEmployee]['sign_name'];;
                $pdf->Text(38, 303.8, $strSignName);

# TANGGAL SIGN LAPORAN A1
                $pdf->Text(110, 303.8, $this->strDaySign);
                $pdf->Text(125, 303.8, $this->strMonthSign);
                $pdf->Text(143, 303.8, $this->strYearSign);

                # END FONT BOLD DAN UKURAN 7.815

                # FONT NORMAL UKURAN 7.41
                $pdf->SetFont("Arial", '', 7.41);
                $pdf->Text(145.251, 26.25, 'Lembar ke-1 : untuk Penerima Penghasilan');
                $pdf->Text(145.251, 29.933, 'Lembar ke-2 : untuk Pemotong');
                # END FONT NORMAL UKURAN 7.41

                # FONT BOLD UKURAN 11.59
                $pdf->SetFont("Arial", '', 11.59);
                $pdf->Text(160.354, 20.979, 'FORMULIR 1721 - A1');
                # END FONT BOLD UKURAN 11.59

                # Create Line & Rec tebal 0.29 mm
                $style = array('width' => 0.29, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

                $pdf->Line(101.169, 46.461, 110.355, 46.461, $style); # Line Akhir Masa NOMOR SPT
                #$pdf->Line(93.015, 44.97, 93.532, 44.97, $style); # Titik
                #$pdf->Line(99.313, 44.459, 100.158, 44.459, $style); # Strip
                #$pdf->Line(111.173, 44.97, 111.603, 44.97, $style); # Titik
                #$pdf->Line(123.697, 44.459, 124.542, 44.459, $style); # Strip
                $pdf->Line(113.361, 46.461, 122.547, 46.461, $style); # Line 2 Digit Tahun
                $pdf->Line(125.553, 46.461, 159.123, 46.461, $style); # Line Nomor Urut SPT
                $pdf->Line(171.273, 46.461, 180.459, 46.461, $style); # Line Masa Awal
                $pdf->Line(184.438, 46.461, 195.572, 46.461, $style); # Line Masa Akhir

                $pdf->Line(46.305, 57.764, 92.067, 57.764, $style); # NPWP PEMOTONG
                $pdf->Line(95.073, 57.764, 107.307, 57.764, $style); # NPWP PEMOTONG
                $pdf->Line(110.313, 57.764, 122.547, 57.764, $style); # NPWP PEMOTONG
                $pdf->Line(46.305, 66.612, 197.096, 66.612, $style); # NAMA COMPANY

                $pdf->Rect(180.586, 16.531, 4, 3, 'D', array('all' => $style)); # Box No Background
                $pdf->Rect(185.962, 16.531, 4, 3, 'DF', array('all' => $style)); # Box Background Blanck.
                $pdf->Rect(191.677, 16.531, 4, 3, 'D', array('all' => $style)); # Box No Background
                $pdf->Rect(197.054, 16.531, 4, 3, 'DF', array('all' => $style)); # Box Background Blanck.
                # END Create Line & Rec tebal 0.29 mm


                $pdf->Image($this->strLogoPajak, 23.3, 16, 25, 23);
                $pdf->Ln(8);

                # FONT BOLD UKURAN 11.59 NOMOR SPT DAN MASA PAJAK
                $strSPTNumber = $this->arrDataA1Employee[$idEmployee]['spt_number'];
                $pdf->SetFont("Arial", '', 8.25);
                $pdf->Text(66.222, 43, 'NOMOR :');
                $pdf->Text(168.183, 35.413, 'MASA PEROLEHAN');
                $pdf->Text(163.556, 38.392, 'PENGHASILAN [mm - mm]');
                $pdf->Text(89, 43, '1');
                $pdf->Text(92.015, 43, '.');
                $pdf->Text(95, 43, '1');
                $pdf->Text(97.313, 43, '-');
                $pdf->Text(103, 43, '12');
                $pdf->Text(109.173, 43, '.');
                $pdf->Text(115, 43, substr($strSPTNumber, 7, 2));
                $pdf->Text(120.697, 43, '-');
                $pdf->Text(136, 43, substr($strSPTNumber, -7));

                $strSPTMasaAwal = $this->arrDataA1Employee[$idEmployee]['start_tax_period'];
                $strSPTMasaAwal = str_pad($strSPTMasaAwal, 2, '0', STR_PAD_LEFT);
                $strSPTMasaAkhir = $this->arrDataA1Employee[$idEmployee]['finish_tax_period'];
                $strSPTMasaAkhir = str_pad($strSPTMasaAkhir, 2, '0', STR_PAD_LEFT);

                $pdf->SetFont("Arial", '', 10.263);
                $pdf->Text(172, 42.3, $strSPTMasaAwal);
                $pdf->Text(186, 42.3, $strSPTMasaAkhir);

                $strNPWPCompany = $this->arrDataA1Employee[$idEmployee]['company_npwp']; #Harus FormatNPWP jika tidak ada NPWP dibuat 00.000.000.0-000.000
                $pdf->Text(60.115, 52.125, substr($strNPWPCompany, 0, 12));
                $pdf->Text(92.168, 52.125, '-');
                $pdf->Text(97.141, 52.125, substr($strNPWPCompany, 13, 3));
                $pdf->Text(107.551, 52.125, '.');
                $pdf->Text(112.439, 52.125, substr($strNPWPCompany, -3));

                $strCompanyName = $this->arrDataA1Employee[$idEmployee]['company_name'];
                $pdf->Text(47.037, 60.125, $strCompanyName);

                # END FONT BOLD UKURAN 11.59 NOMOR SPT DAN MASA PAJAK

                # FONT NORMAL 8.25
                $pdf->SetFont("Arial", '', 8.25);
                $pdf->Text(12.022, 51.589, 'NPWP');
                $pdf->Text(12.022, 55.139, 'PEMOTONG');
                $pdf->Text(34.692, 55.361, ':');
                $pdf->Text(12.022, 60.289, 'NAMA');
                $pdf->Text(12.022, 63.839, 'PEMOTONG');
                $pdf->Text(64.209, 55.361, ':');
                # END FONT NORMAL 8.25


                # FONT NORMAL 8.25
                $pdf->SetFont("Arial", '', 7.16);

                $pdf->Text(10.641, 81.835, '1.');
                $pdf->Text(15.092, 81.835, 'NPWP');
                $pdf->Text(28.814, 81.835, ':');

                $pdf->Text(10.641, 89.284, '2.');
                $pdf->Text(15.092, 88.752, 'NIK/NO.');
                $pdf->Text(15.092, 91.969, 'PASPOR');
                $pdf->Text(28.814, 91.969, ':');

                $pdf->Text(10.641, 98.597, '3.');
                $pdf->Text(15.092, 98.597, 'NAMA');
                $pdf->Text(28.814, 98.597, ':');

                $pdf->Text(10.641, 103.854, '4.');
                $pdf->Text(15.092, 103.854, 'ALAMAT');
                $pdf->Text(28.814, 103.854, ':');

                $pdf->Text(10.641, 118.417, '5.');
                $pdf->Text(15.092, 118.417, 'JENIS KELAMIN');
                $pdf->Text(34.391, 118.417, ':');
                $pdf->Text(55.535, 117.782, 'LAKI-LAKI');
                $pdf->Text(88.196, 117.782, 'PEREMPUAN');

                $pdf->Text(116.249, 81.895, '6.');
                $pdf->Text(120.134, 81.895, 'STATUS/JUMLAH TANGGUNGAN KELUARGA UNTUK PTKP');

                $pdf->Text(120.134, 88.629, 'K /');
                $pdf->Text(144.483, 88.629, 'TK /');
                $pdf->Text(169.019, 88.629, 'HB /');

                $pdf->Text(116.249, 97.823, '7.');
                $pdf->Text(120.134, 97.823, 'NAMA JABATAN');

                $pdf->Text(116.249, 104.416, '8.');
                $pdf->Text(120.134, 104.416, 'KARYAWAN ASING');
                $pdf->Text(170.507, 104.416, 'YA');

                $pdf->Text(116.249, 111.146, '9.');
                $pdf->Text(120.134, 111.146, 'KODE NEGARA DOMISILI');

                # CONTENT OF VALUE A1 REPORT FROM B1 TO B 20
                # ========================================================================================================

                $strBasicSalary = $this->arrDataA1Employee[$idEmployee]['basic_salary']; # Basic Salary Actual yang diterima setiap bulan
                $strBasicSalary = number_format($strBasicSalary, 2, ',', '.');

                $strTaxAllowance = $this->arrDataA1Employee[$idEmployee]['tax_allowance']; # Tunjangan Pajak
                $strTaxAllowance = number_format($strTaxAllowance, 2, ',', '.');

                $strOtherAllowance = $this->arrDataA1Employee[$idEmployee]['other_allowance']; # Overtime dan Tunjangan Reguler selain Basic Salary dan Asuransi
                $strOtherAllowance = number_format($strOtherAllowance, 2, ',', '.');

                $strHonoriumAllowance = 0; # Honorium
                $strHonoriumAllowance = number_format($strHonoriumAllowance, 2, ',', '.');

                $strInsuranceAllowance = $this->arrDataA1Employee[$idEmployee]['insurance_allowance']; #JKK, JKM, BPJS KES, JSHK & other
                $strInsuranceAllowance = number_format($strInsuranceAllowance, 2, ',', '.');

                $strNaturaAllowance = 0; #Natura
                $strNaturaAllowance = number_format($strNaturaAllowance, 2, ',', '.');

                $strIrregularAllowance = $this->arrDataA1Employee[$idEmployee]['irregular_allowance']; #THR, Bonus dll
                $strIrregularAllowance = number_format($strIrregularAllowance, 2, ',', '.');

                # JUMLAH BRUTO
                $strTotalBruto1 = $this->arrDataA1Employee[$idEmployee]['basic_salary'] + $this->arrDataA1Employee[$idEmployee]['tax_allowance'] + $this->arrDataA1Employee[$idEmployee]['other_allowance'] + $this->arrDataA1Employee[$idEmployee]['insurance_allowance'] + $this->arrDataA1Employee[$idEmployee]['irregular_allowance']; # Bruto
                $strTotalBruto = number_format($strTotalBruto1, 2, ',', '.');

                $strBiayaJabatan1 = $this->arrDataA1Employee[$idEmployee]['biaya_jabatan']; # Biaya Jabatan / Biaya Pensiun
                $strBiayaJabatan = number_format($strBiayaJabatan1, 2, ',', '.');

                $strIuranJHTJPN1 = $this->arrDataA1Employee[$idEmployee]['bpjs_deduction']; #JHT, JPN
                $strIuranJHTJPN = number_format($strIuranJHTJPN1, 2, ',', '.');

                $strTotalPengurangan1 = $strBiayaJabatan1 + $strIuranJHTJPN1;
                $strTotalPengurangan = number_format($strTotalPengurangan1, 2, ',', '.');

                $strTotalNetto1 = $strTotalBruto1 - $strTotalPengurangan1;
                $strTotalNetto = number_format($strTotalNetto1, 2, ',', '.');

                $strTotalNettoSebelumnnya1 = $this->arrDataA1Employee[$idEmployee]['previous_base_tax']; # Netto Masa Sebelumnya Khusus yang pindah branch
                $strTotalNettoSebelumnnya = number_format($strTotalNettoSebelumnnya1, 2, ',', '.');

                $strTotalBrutoPKP1 = $strTotalNetto1 + $strTotalNettoSebelumnnya1;
                $strTotalBrutoPKP = number_format($strTotalBrutoPKP1, 2, ',', '.');

                $strPTKP1 = $this->arrDataA1Employee[$idEmployee]['ptkp'];
                $strPTKP = number_format($strPTKP1, 2, ',', '.');

                if (($strTotalBrutoPKP1 - $strPTKP1) > 0) {
                    $strTotalNettoPKP1 = $this->roundDown(($strTotalBrutoPKP1 - $strPTKP1), 3);
                } else {
                    $strTotalNettoPKP1 = 0;
                }
                $strTotalNettoPKP = number_format($strTotalNettoPKP1, 2, ',', '.');

                $strTaxSetahun1 = ($strTotalNettoPKP1 > 0) ? $this->arrDataA1Employee[$idEmployee]['total_annual_tax'] : 0;
                $strTaxSetahun = number_format($strTaxSetahun1, 2, ',', '.');

                $strTaxMasaSebelumnya1 = $this->arrDataA1Employee[$idEmployee]['previous_tax'];
                $strTaxMasaSebelumnya = number_format($strTaxMasaSebelumnya1, 2, ',', '.');

                $strTaxTerhutang = $strTaxSetahun1 - $strTaxMasaSebelumnya1;
                $strTaxTerhutang = number_format($strTaxTerhutang, 2, ',', '.');

                $strTaxAkhir = $this->arrDataA1Employee[$idEmployee]['total_monthly_tax'];
                $strTaxAkhir = number_format($strTaxAkhir, 2, ',', '.');


                $pdf->Text(8, 152.93, '1.');
                $pdf->Text(15.79, 152.93, 'GAJI/PENSIUN ATAU THT/JHT');
                $pdf->SetXY(150, 152);
                $pdf->Cell(50, 5, $strBasicSalary, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 158.98, '2.');
                $pdf->Text(15.79, 158.98, 'TUNJANGAN PPh');
                $pdf->SetXY(150, 158);
                $pdf->Cell(50, 5, $strTaxAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');


                $pdf->Text(8, 165.07, '3.');
                $pdf->Text(15.79, 165.07, 'TUNJANGAN LAINNYA, UANG LEMBUR DAN SEBAGAINYA');

                $pdf->SetXY(150, 164);
                $pdf->Cell(50, 5, $strOtherAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 171.09, '4.');
                $pdf->Text(15.79, 171.09, 'HONORARIUM DAN IMBALAN LAIN SEJENISNYA');

                $pdf->SetXY(150, 170);
                $pdf->Cell(50, 5, $strHonoriumAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 177.15, '5.');
                $pdf->Text(15.79, 177.15, 'PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA');

                $pdf->SetXY(150, 176);
                $pdf->Cell(50, 5, $strInsuranceAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 183.73, '6.');
                $pdf->Text(15.79, 181.097, 'PENERIMAAN DALAM BENTUK NATURA DAN KENIKMATAN LAINNYA YANG DIKENAKAN PEMOTONGAN PPh');
                $pdf->Text(15.79, 184.315, 'PASAL 21');

                $pdf->SetXY(150, 182);
                $pdf->Cell(50, 5, $strNaturaAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 190.225, '7.');
                $pdf->Text(15.79, 190.225, 'TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR');

                $pdf->SetXY(150, 189);
                $pdf->Cell(50, 5, $strIrregularAllowance, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 196.278, '8.');
                $pdf->Text(15.79, 196.278, 'JUMLAH PENGHASILAN BRUTO (1 S.D 7)');

                $pdf->SetXY(150, 195);
                $pdf->Cell(50, 5, $strTotalBruto, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 208.387, '9.');
                $pdf->Text(15.79, 208.387, 'BIAYA JABATAN/ BIAYA PENSIUN');

                $pdf->SetXY(150, 207);
                $pdf->Cell(50, 5, $strBiayaJabatan, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 214.44, '10.');
                $pdf->Text(15.79, 214.44, 'IURAN PENSIUN ATAU IURAN THT/JHT');

                $pdf->SetXY(150, 213);
                $pdf->Cell(50, 5, $strIuranJHTJPN, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 220.49, '11.');
                $pdf->Text(15.79, 220.49, 'JUMLAH PENGURANGAN (9 S.D 10)');

                $pdf->SetXY(150, 219);
                $pdf->Cell(50, 5, $strTotalPengurangan, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 232.6, '12.');
                $pdf->Text(15.79, 232.6, 'JUMLAH PENGHASILAN NETO (8-11)');

                $pdf->SetXY(150, 231);
                $pdf->Cell(50, 5, $strTotalNetto, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 238.6, '13.');
                $pdf->Text(15.79, 238.6, 'PENGHASILAN NETO MASA SEBELUMNYA');

                $pdf->SetXY(150, 237);
                $pdf->Cell(50, 5, $strTotalNettoSebelumnnya, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 244.7, '14.');
                $pdf->Text(15.79, 244.7, 'JUMLAH PENGHASILAN NETO UNTUK PENGHITUNGAN PPh PASAL 21 (SETAHUN/DISETAHUNKAN)');

                $pdf->SetXY(150, 243);
                $pdf->Cell(50, 5, $strTotalBrutoPKP, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 250.7, '15.');
                $pdf->Text(15.79, 250.7, 'PENGHASILAN TIDAK KENA PAJAK (PTKP)');

                $pdf->SetXY(150, 249);
                $pdf->Cell(50, 5, $strPTKP, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 256.7, '16.');
                $pdf->Text(15.79, 256.7, 'PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN (14 - 15)');

                $pdf->SetXY(150, 255);
                $pdf->Cell(50, 5, $strTotalNettoPKP, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 262.8, '17.');
                $pdf->Text(15.79, 262.8, 'PPh PASAL 21 ATAS PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN');

                $pdf->SetXY(150, 261);
                $pdf->Cell(50, 5, $strTaxSetahun, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 268.9, '18.');
                $pdf->Text(15.79, 268.9, 'PPh PASAL 21 YANG TELAH DIPOTONG MASA SEBELUMNYA');

                $pdf->SetXY(150, 267);
                $pdf->Cell(50, 5, $strTaxMasaSebelumnya, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 274.9, '19.');
                $pdf->Text(15.79, 274.9, 'PPh PASAL 21 TERUTANG');

                $pdf->SetXY(150, 273);
                $pdf->Cell(50, 5, $strTaxTerhutang, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                $pdf->Text(8, 281, '20.');
                $pdf->Text(15.79, 281, 'PPh PASAL 21 DAN PPh PASAL 26 YANG TELAH DIPOTONG DAN DILUNASI');

                $pdf->SetXY(150, 279);
                $pdf->Cell(50, 5, $strTaxAkhir, 0, false, 'R', 0, '', 0, false, 'T', 'M');

                # ========================================================================================================
                # END CONTENT OF VALUE A1 REPORT FROM B1 TO B 20

                # END FONT NORMAL 8.25


                # Font Bold 6.25
                $pdf->SetTextColor(128, 128, 128);
                $pdf->SetFont("Arial", '', 6.25);
                $pdf->Text(81.518, 43.644, 'H.01');
                $pdf->Text(163.181, 43.644, 'H.02');
                $pdf->Text(37.845, 55.419, 'H.03');
                $pdf->Text(37.845, 64.266, 'H.04');

                $pdf->Text(30, 82.893, 'A.01');
                $pdf->Text(30, 92.206, 'A.02');
                $pdf->Text(30, 98.81, 'A.03');
                $pdf->Text(30, 105.414, 'A.04');
                $pdf->Text(41.772, 118.622, 'A.05');
                $pdf->Text(72.718, 118.622, 'A.06');
                $pdf->Text(135.205, 92.206, 'A.07');
                $pdf->Text(159.589, 92.206, 'A.08');
                $pdf->Text(184.946, 92.206, 'A.09');
                $pdf->Text(143, 98.81, 'A.10');
                $pdf->Text(155.31, 105.414, 'A.11');
                $pdf->Text(155.31, 111.299, 'A.12');

                $pdf->Text(28.634, 298.392, 'C.01');
                $pdf->Text(28.634, 305.504, 'C.02');
                $pdf->Text(101, 305.504, 'C.03');

                # END GENERATE A1
            }
            # FI untuk langsung ditampilkan di browser, D jika langsung download ke file

            #Jika Single Page Tambahkan Nama Karyawan pada Output FIle PDF
            $tmpFileName = ($this->multiplePage === false) ? '_' . $this->arrDataA1Employee[$idEmployee]['employeeName'] : '';

            if ($this->bolDownloadPDF === true) {
                $pdf->Output($this->strPDFFileName . '_' . $this->strYear . $tmpFileName . '.pdf', "D");
            } else {
                $pdf->Output($this->strPDFFileName . '_' . $this->strYear . $tmpFileName . '.pdf', "FI");
            }

            #ob_clean();
            ob_end_flush();
        }

    }
}

# End Of A1Report.php