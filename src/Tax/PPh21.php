<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 05/02/2018
 * Time: 13.33
 * Version : 1.0
 */

namespace QueenApp\Tax\PPh21;

/**
 * PPH 21 SESUAI PERATURAN PERPAJAKAN NOMOR:
 * UNTUK PEGAWAI DALAM NEGERI.
 *
 * Class PPh21
 * @package QueenApp\Tax\PPh21
 */
class PPh21
{
    /** @var bool : true = gross up, false = gross */
    private $grossUp = false;
    /** @var float */
    private $regularTax = 0.0;
    /** @var float */
    private $irregularTax = 0.0;
    /** @var float */
    private $fltPTKP = 0.0;
    /** @var float */
    private $fltBaseRegular = 0.0;
    /** @var float */
    private $fltBaseIrregular = 0.0;
    /** @var float */
    private $fltJHTJPN = 0.0;
    /** @var int */
    private $intStartTax = 1;
    /** @var int */
    private $intFinishTax = 12;
    /** @var int  : Masa perolehan pajak karyawan selama setahun, tergantung join & resign. */
    private $intMasaPajak = 12;
    /** @var bool */
    private $bolNPWP = true;
    /** @var float */
    protected $fltBiayaJabatanMonthly = 500000;
    /** @var float */
    protected $fltBiayaJabatanYearly = 6000000;
    /** @var array */
    private $arrPTKP = [];

    /**
     * PPh21 constructor.
     * @param bool $bolGrossUp
     */
    public function __construct($bolGrossUp = false)
    {
        $this->grossUp = $bolGrossUp;
        $this->definePTKP();
    }

    /**
     * Default dari construct tapi untuk antisipasi jika per employee beda methode.
     * Jadi di looping dari array employee.
     * @param bool $bolGrossUp
     */
    public function setTaxMethod($bolGrossUp = false)
    {
        $this->grossUp = $bolGrossUp;
        $this->definePTKP();
    }

    /**
     * @param int $fltBaseRegular
     * @param int $fltBaseIrregular
     * @param int $fltJHTJPN
     * @param $bolNPWP
     * @param $strFamilyStatus
     * @param int $intStartTax
     * @param int $intFinishTax
     */
    public function calculateTax($fltBaseRegular = 0, $fltBaseIrregular = 0, $fltJHTJPN = 0, $bolNPWP, $strFamilyStatus, $intStartTax = 1, $intFinishTax = 12)
    {
        $this->fltBaseRegular = $fltBaseRegular;
        $this->fltBaseIrregular = $fltBaseIrregular;
        $this->fltJHTJPN = $fltJHTJPN;
        $this->bolNPWP = $bolNPWP;
        $this->fltPTKP = $this->getPTKP($strFamilyStatus);
        $this->intStartTax = $intStartTax;
        $this->intFinishTax = $intFinishTax;
        $this->intMasaPajak = $this->intFinishTax - $this->intStartTax + 1;


        if ($this->grossUp === true) {
            $this->calculateTaxGrossUp();
        } else {
            $this->calculateTaxGross();
        }
    }

    /**
     * UNGSI UNTUK HITUNG PAJAK DENGAN METODE GROSS
     * SENGAJA DI PISAH FUNGSI GROSS DAN GROSS UP AGAR MUDAH MANAGENYA.
     */

    private function calculateTaxGrossUp()
    {

        $fltDelta = 0.01;
        $fltRegularTax = 0;
        $fltIrregularTax = 0;
        $fltRegularTaxAllowance = 0;
        $fltIrregularTaxAllowance = 0;
        $masaPajak = $this->intMasaPajak;
        $baseTax = $this->fltBaseRegular;
        $baseIrrTax = $this->fltBaseIrregular;
        $fltJHTJPN = $this->fltJHTJPN;
        $fltJHTJPNYearly = $fltJHTJPN * $masaPajak;
        $fltPTKP = $this->fltPTKP;
        $bolIteration = true;

        while ($bolIteration === true) {
            $fltNetIncomeYearly = ($baseTax * $masaPajak) + $fltRegularTaxAllowance;

            $fltBiayaJabatanRegular = $this->calculateBiayaJabatan($fltNetIncomeYearly, 0, $masaPajak, $this->fltBiayaJabatanYearly);
            $fltPKPRegular = $fltNetIncomeYearly - $fltBiayaJabatanRegular - $fltJHTJPNYearly - $fltPTKP;
            $fltPKPRegular = $this->roundDown($fltPKPRegular, 3);

            # Pajak Regular
            if ($fltPKPRegular > 0) {
                $fltRegularTax = $this->calculateLayerTax($fltPKPRegular, $this->bolNPWP);
            } else {
                $fltRegularTax = 0;
            }

            if ($baseIrrTax > 0) {
                $fltNetIncomeYearlyIrregular = $fltNetIncomeYearly + $baseIrrTax + $fltIrregularTaxAllowance;
                $fltBiayaJabatanIrregular = $this->calculateBiayaJabatan($fltNetIncomeYearly, $baseIrrTax + $fltIrregularTaxAllowance, $masaPajak, $this->fltBiayaJabatanYearly);
                $fltPKPIrregular = $fltNetIncomeYearlyIrregular - $fltBiayaJabatanIrregular - $fltJHTJPNYearly - $fltPTKP;
                $fltPKPIrregular = $this->roundDown($fltPKPIrregular, 3);

                if ($fltPKPIrregular > 0) {
                    $fltIrregularTax = $this->calculateLayerTax($fltPKPIrregular, $this->bolNPWP);
                    # Pajak Irregular = Pajak Penghasilan include Irregular dikurani pajak penghasilan disetahunkan tanpa irregular
                    $fltIrregularTax = $fltIrregularTax - $fltRegularTax;
                } else {
                    $fltIrregularTax = 0;
                }

            }

            if ((abs($fltRegularTax - $fltRegularTaxAllowance) >= $fltDelta)) {
                $fltRegularTaxAllowance = ($fltRegularTaxAllowance + $fltRegularTax) / 2;
                $fltIrregularTaxAllowance = ($fltIrregularTaxAllowance + $fltIrregularTax) / 2;
            } else {
                //$baseTaxYearly = $fltNetIncomeYearly;
                $bolIteration = false;
            }

            # Jika ada penghasilan Irregular

        }

        $this->regularTax = $fltRegularTax;
        $this->irregularTax = $fltIrregularTax;
    }

    /**
     * FUNGSI UNTUK HITUNG PAJAK DENGAN METODE GROSS
     * SENGAJA DI PISAH FUNGSI GROSS DAN GROSS UP AGAR MUDAH MANAGENYA.
     */
    private function calculateTaxGross()
    {
        $fltRegularTax = 0;
        $fltIrregularTax = 0;
        $masaPajak = $this->intMasaPajak;
        $baseTax = $this->fltBaseRegular;
        $baseTaxYearly = $baseTax * $masaPajak;
        $baseIrrTax = $this->fltBaseIrregular;
        $fltJHTJPN = $this->fltJHTJPN;
        $fltJHTJPNYearly = $fltJHTJPN * $masaPajak;
        $fltPTKP = $this->fltPTKP;
        if ($baseTax > 0) {

            $fltBiayaJabatanRegular = $this->calculateBiayaJabatan($baseTaxYearly, 0, $masaPajak, $this->fltBiayaJabatanYearly);
            $fltPKPRegular = $baseTaxYearly - $fltBiayaJabatanRegular - $fltJHTJPNYearly - $fltPTKP;
            $fltPKPRegular = $this->roundDown($fltPKPRegular, 3);

            # Pajak Regular
            if ($fltPKPRegular > 0) {
                $fltRegularTax = $this->calculateLayerTax($fltPKPRegular, $this->bolNPWP);
            } else {
                $fltRegularTax = 0;
            }

            # Jika ada penghasilan Irregular
            if ($baseIrrTax > 0) {

                $fltBiayaJabatanIncludeIrregular = $this->calculateBiayaJabatan($baseTaxYearly, $baseIrrTax, $masaPajak, $this->fltBiayaJabatanYearly);
                $fltPKPIrregular = ($baseTaxYearly + $baseIrrTax) - $fltBiayaJabatanIncludeIrregular - $fltJHTJPNYearly - $fltPTKP;
                $fltPKPIrregular = $this->roundDown($fltPKPIrregular, 3);

                if ($fltPKPIrregular > 0) {
                    $fltIrregularTax = $this->calculateLayerTax($fltPKPIrregular, $this->bolNPWP);
                    # Pajak Irregular = Pajak Penghasilan include Irregular dikurani pajak penghasilan disetahunkan tanpa irregular
                    $fltIrregularTax = $fltIrregularTax - $fltRegularTax;
                } else {
                    $fltIrregularTax = 0;
                }

            }

        }

        $this->regularTax = $fltRegularTax;
        $this->irregularTax = $fltIrregularTax;

    }

    /**
     * UNTUK MEMBACA NILAI PAJAK REGULER MAUPUN IRREGULER DARI HASIL KALKULASI.
     *
     * @param bool $bolRegular
     * @param bool $bolYearly
     * @return float
     */
    public function getTax($bolRegular = true, $bolYearly = true)
    {
        $fltRegularTax = $this->regularTax;
        $fltIrregularTax = $this->irregularTax;
        $intMasaPajak = $this->intMasaPajak;
        # Bulanan.
        if ($bolYearly === false) {
            $fltRegularTax = $fltRegularTax / $intMasaPajak;
            $fltIrregularTax = $fltIrregularTax / $intMasaPajak;
        }
        if ($bolRegular === true) {
            return $fltRegularTax;
        } else {
            return $fltIrregularTax;
        }
    }

    /**
     * UNTUK MEMBACA ANGKA PTKP DARI FAMILY STATUS TAX.
     * @param string $strPTKP
     * @return float|mixed
     */
    public function getPTKP($strPTKP = '')
    {
        if (is_null($strPTKP) or empty($strPTKP)) {
            echo 'PTKP Salah, Harap Periksa Kembali';
            exit;
        } else {
            $this->fltPTKP = $this->arrPTKP[$strPTKP];
            return $this->fltPTKP;
        }


    }

    /**
     * MENGHITUNG BIAYA JABATAN SECARA PROPORSIONAL BERDASARKAN MASA PAJAK.
     * @param int $fltBaseTax
     * @param int $fltBaseTaxIrregular
     * @param $intMasaPajak
     * @param int $maxBijabSetahun
     * @param float $tarifBijab
     * @return float|int
     */
    private function calculateBiayaJabatan(
        $fltBaseTax = 0,
        $fltBaseTaxIrregular = 0,
        $intMasaPajak,
        $maxBijabSetahun = 6000000,
        $tarifBijab = 0.05
    )
    {
        $intMaxBiayaJabatan = ($maxBijabSetahun / 12) * $intMasaPajak;

        if ((($fltBaseTax + $fltBaseTaxIrregular) * $tarifBijab) > $intMaxBiayaJabatan) {
            if (($fltBaseTax * $tarifBijab) > $intMaxBiayaJabatan) {
                $intBiayaJabatan = $intMaxBiayaJabatan;
                $intBiayaJabatanIrregular = 0;
            } else {
                $intBiayaJabatan = round($fltBaseTax * $tarifBijab);
                $intBiayaJabatanIrregular = $intMaxBiayaJabatan - $intBiayaJabatan;
            }
        } else {
            $intBiayaJabatan = round($fltBaseTax * $tarifBijab);
            $intBiayaJabatanIrregular = round($fltBaseTaxIrregular * $tarifBijab);
        }
        return $intBiayaJabatan + $intBiayaJabatanIrregular;
    }

    /**
     * FUNGSI UNTUK MENGHITUNG PAJAK DARI NILAI PKP (PENDAPATAK KENA PAJAK).
     * @param int $fltPKP
     * @param bool $bolNPWP
     * @return float|int
     */
    private function calculateLayerTax($fltPKP = 0, $bolNPWP = true)
    {
        $fltTax = 0;
        $fltTmp = 0;
        $tarifLayer1 = 0.05;
        $tarifLayer2 = 0.15;
        $tarifLayer3 = 0.25;
        $tarifLayer4 = 0.30;
        $maxLayer1 = 50000000;
        $maxLayer2 = 200000000;
        $maxLayer3 = 250000000;

        # Lapis 1
        if ($fltPKP > 0) {
            $fltTax = ($fltPKP > $maxLayer1) ? $maxLayer1 * $tarifLayer1 : $fltPKP * $tarifLayer1;
            $fltTmp = $fltPKP - $maxLayer1;
        }
        # Lapis 2
        if ($fltTmp > 0) {
            $fltTax += ($fltTmp > $maxLayer2) ? $maxLayer2 * $tarifLayer2 : $fltTmp * $tarifLayer2;
            $fltTmp = $fltTmp - $maxLayer2;
        }
        # Lapis 3
        if ($fltTmp > 0) {
            $fltTax += ($fltTmp > $maxLayer3) ? $maxLayer3 * $tarifLayer3 : $fltTmp * $tarifLayer3;
            $fltTmp = $fltTmp - $maxLayer3;
        }
        # Lapis 4
        if ($fltTmp > 0) {
            $fltTax += $fltTmp * $tarifLayer4;
        }

        # Tarif Non NPWP 20% lebih besar dari pajak PPh21 Normal.
        if ($bolNPWP === false) {
            $fltTax = 1.2 * $fltTax;
        }

        return $fltTax;

    }

    /**
     * MENAMPILKAN DATA PTKP (PENDAPATAN TIDAK KENA PAJAK) BERDASARKAN FAMILY STATUS TAX.
     * NEXT TODO : 1. KONEKSIKAN KE MASTER DI DATABASE
     */
    protected function definePTKP()
    {
        # TODO 1 :
        # ================
        # $arrPTKP FROM DATABASE.
        # PATTERN KEY (FAMILY STATUS TAX) => PTKP ( NILAI PTKP SESUAI PERATUAN PAJAK )
        # ================
        $arrPTKP = [
            "TK/0" => 54000000,
            "TK/1" => 58500000,
            "TK/2" => 63000000,
            "TK/3" => 67500000,
            "K/0" => 58500000,
            "K/1" => 63000000,
            "K/2" => 67500000,
            "K/3" => 72000000
        ];

        if (empty($arrPTKP)) {
            echo 'Silahkan Isi Master data Family Status terlebih dahulu';
            exit;
        }

        $this->arrPTKP = $arrPTKP;
    }

    /**
     * @param $value
     * @param int $downTo
     * @return float
     */
    private function roundDown($value, $downTo = 0)
    {
        $parameter = pow(10, $downTo);
        return floor($value / $parameter) * $parameter;
    }
}

# END OF FILE CLASS PPh21.

