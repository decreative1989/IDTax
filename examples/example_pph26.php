<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 08/02/2018
 * Time: 18.01
 */

require '../vendor/autoload.php';
use QueenApp\Tax\PPh26;

# Example Gross Up.

$fltGaji = 50000000;

$taxGrossUp = new PPh26\PPh26(true);
$taxGrossUp->calculateTax($fltGaji);
$tax = $taxGrossUp->getTax();
$taxAllowance = $taxGrossUp->getTaxAllowance();

echo 'Base Tax :' . number_format($taxGrossUp->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo 'Tunjangan Pajak:' . number_format($taxAllowance, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';

$taxGross = new PPh26\PPh26(false);
$taxGross->calculateTax($fltGaji);
$tax = $taxGross->getTax();

echo 'Base Tax :' . number_format($taxGross->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';
