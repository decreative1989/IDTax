<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 08/02/2018
 * Time: 17.34
 * Version: 0.1
 */

namespace QueenApp\Tax\PPh26;

/**
 * Class PPh26
 * @package QueenApp\Tax\PPh26
 */

class PPh26
{
    private $bolGrossUp = false;
    private $baseTax = 0.0;
    private $tarif = 20;
    private $fltTax = 0;
    private $fltTaxAllowance = 0;

    public function __construct($bolGrossUp = false)
    {
        $this->bolGrossUp = $bolGrossUp;
    }

    /**
     * @param int $baseTax
     */
    public function calculateTax($baseTax = 0)
    {
        $this->baseTax = $baseTax;
        if ($this->bolGrossUp === true) {
            $this->calculateTaxGross();
        } else {
            $this->calculateTaxGrossUp();
        }
    }

    /**
     * @return int
     */
    protected function calculateTaxGross()
    {
        $this->fltTax = $this->baseTax * ($this->tarif / 100);

        return $this->fltTax;
    }

    /**
     * @return int
     */
    protected function calculateTaxGrossUp()
    {
        $fltDelta = 0.01;
        $fltTax = 0;
        $fltTaxAllowance = 0;
        $bolIteration = true;
        $baseTax = $this->baseTax;

        while ($bolIteration === true) {

            $fltNet = $baseTax + $fltTaxAllowance;

            $fltTax = $fltNet * ($this->tarif / 100);

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
     * @param int $fltTarif
     */
    public function setTarif($fltTarif = 0)
    {
        $this->tarif = $fltTarif;
    }

    /**
     * @return int
     */
    public function getTax()
    {
        return $this->fltTaxAllowance;
    }

    /**
     * @return int
     */
    public function getTaxAllowance()
    {
        return $this->fltTaxAllowance;
    }

    public function getBaseTax()
    {
        return $this->baseTax;
    }
}