<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 09/02/2018
 * Time: 11.20
 */


require '../vendor/autoload.php';
use QueenApp\Tax\PPh21Final;

#==========================================
# Example Final Pesangon:                 #
#==========================================


# Karyawan Mendapatkan Uang Pensiun Sebanyak 240 jt maka pajak atas penghasilan pensiunnya adalah

# #Gross : Pajak ditanggung karyawan.
$taxPension = new PPh21Final\PPh21Final(false);
$taxPension->calculateTaxFinal('pesangon', 240000000);
$tax = $taxPension->getFinalTax();

echo '=== TAK METHODE GROSS ===';
echo 'Base Tax :' . number_format($taxPension->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';

$taxPension = new PPh21Final\PPh21Final(true);
$taxPension->calculateTaxFinal('pensiun', 240000000);
$tax = $taxPension->getFinalTax();
$taxAllowance = $taxPension->getFinalTaxAllowance();
echo '=== TAK METHODE GROSS UP ===';
echo 'Base Tax :' . number_format($taxPension->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo 'Tunjangan Pajak:' . number_format($taxAllowance, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';


#==========================================
# Example Final Pensiun:                 #
#==========================================


# Karyawan Mendapatkan Pensiun dan Mendapatkan Uang Pensiun Sebanyak 240 jt maka pajak atas penghasilan pensiunnya adalah

# #Gross : Pajak ditanggung karyawan.
$taxPension = new PPh21Final\PPh21Final(false);
$taxPension->calculateTaxFinal('pensiun', 240000000);
$tax = $taxPension->getFinalTax();

echo '=== TAK METHODE GROSS ===';
echo 'Base Tax :' . number_format($taxPension->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';

$taxPension = new PPh21Final\PPh21Final(true);
$taxPension->calculateTaxFinal('pensiun', 240000000);
$tax = $taxPension->getFinalTax();
$taxAllowance = $taxPension->getFinalTaxAllowance();
echo '=== TAK METHODE GROSS UP ===';
echo 'Base Tax :' . number_format($taxPension->getBaseTax(), 0, ',', '.');
echo '<br/>';
echo 'Pajak Yang Harus Dibayar :' . number_format($tax, 0, ',', '.');
echo '<br/>';
echo 'Tunjangan Pajak:' . number_format($taxAllowance, 0, ',', '.');
echo '<br/>';
echo '<br/>';
echo '<br/>';

