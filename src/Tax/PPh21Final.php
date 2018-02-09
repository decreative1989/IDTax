<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 09/02/2018
 * Time: 10.17
 */

namespace QueenApp\Tax\PPh21Final;


use QueenApp\Tax\PPh21\PPh21;

/**
 * Class PPh21Final
 * @package QueenApp\Tax\PPh21Final
 */
class PPh21Final extends PPh21
{
    /**
     * @var float
     */
    private $baseTax = 0.0;

    /**
     * @var string
     *
     * Example : "pesangon", "pensiun"
     */
    private $taxType = '';

    /**
     * @var bool
     */
    private $bolGrossUp = false;

    /**
     * @var float
     */
    private $fltTax = 0.0;

    /**
     * @var float
     */
    private $fltTaxAllowance = 0.0;

    /**
     * @var int
     * BATAS TARIF DIKENAKAN PAJAK  #50.000.000,-
     */
    protected $fltBatasMinTarif = 50000000;

    /**
     * PPh21Final constructor.
     * @param bool $bolGrossUp
     */
    public function __construct($bolGrossUp = false)
    {
        parent::__construct($bolGrossUp);
    }

    /**
     * @param string $taxType
     */
    public function calculateTaxFinal($taxType = '')
    {
        $this->taxType = $taxType;
        if ($this->checkValidTaxType() === true) {
            if ($this->bolGrossUp === true) {
                $this->calculateTaxFinalGrossUp();
            } else {
                $this->calculateTaxFinalGross();
            }
        }
    }

    /**
     * @return bool
     */
    private function checkValidTaxType()
    {
        $arrType = ['pesangon', 'pensiun'];
        $bolValid = (in_array($this->taxType, $arrType)) ? true : false;

        return $bolValid;
    }

    /**
     * @return float
     */
    public function getFinalTax()
    {
        return $this->fltTax;
    }


    private function calculateTaxFinalGross()
    {
        $this->calculateLayerTaxFinal();
        return $this->getFinalTax();
    }

    private function calculateTaxFinalGrossUp()
    {
        $fltDelta = 0.01;
        $fltTax = 0;
        $fltTaxAllowance = 0;
        $bolIteration = true;
        $baseTax = $this->baseTax;

        while ($bolIteration === true) {

            $fltNet = $baseTax + $fltTaxAllowance;

            $fltTax = $this->calculateLayerTaxFinal();

            if ((abs($fltTax - $fltTaxAllowance) >= $fltDelta)) {
                $fltTaxAllowance = ($fltTax + $fltTaxAllowance) / 2;
            } else {
                $bolIteration = false;
            }
        }

        $this->fltTaxAllowance = $fltTax;
        $this->fltTax = $fltTax;

        $this->baseTax = $this->baseTax + $this->fltTaxAllowance;

        return $this->fltTax;
    }

    /**
     * @return bool|float|int
     */
    private function calculateLayerTaxFinal()
    {
        $fltTax = 0;
        $tarifLayer1 = 0;
        $tarifLayer2 = 0.05; # 5%
        $tarifLayer3 = 0.15; # 15%
        $tmpBaseTax = 0;

        if ($this->taxType === 'pesangon') {

            # 50 jt pertama x 0%
            if ($this->baseTax > 0) {
                $fltTax = ($this->baseTax > $this->fltBatasMinTarif) ? $this->fltBatasMinTarif * $tarifLayer1 : $this->baseTax * $tarifLayer1;
                $tmpBaseTax = $this->baseTax - $this->fltBatasMinTarif;
            }

            # 50 jt kedua x 5%
            if ($tmpBaseTax > 0) {
                $fltTax += ($tmpBaseTax > $this->fltBatasMinTarif) ? $this->fltBatasMinTarif * $tarifLayer2 : $tmpBaseTax * $tarifLayer2;
                $tmpBaseTax = $tmpBaseTax - $this->fltBatasMinTarif;
            }

            # Sisa x 15 #
            if ($tmpBaseTax > 0) {
                $fltTax += $tmpBaseTax * $tarifLayer3;
            }

        } elseif ($this->taxType === 'pensiun') {
            if ($this->baseTax > $this->fltBatasMinTarif) {
                $fltTax = $this->fltBatasMinTarif * 0.05; # 5 %
            }
        } else {
            return false;
        }

        $this->fltTax = $fltTax;

        return $this->fltTax;
    }
}