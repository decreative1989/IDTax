<?php
/**
 * Created by PhpStorm.
 * User: Ade Sanusi
 * Date: 06/02/2018
 * Time: 13.52
 */

require '../vendor/autoload.php';
use QueenApp\Tax\PPh21;

$arrEmployee = [
    1 => [
        'nik' => '00001',
        'name' => 'NAMA 01',
        'npwp' => '0123',
        'tax_status' => 'TK/0',
        'join_date' => '2016-12-01',
        'resign_date' => '',
        'gaji' => 10000000

    ],
    2 => [
        'nik' => '00002',
        'name' => 'NAMA 02',
        'npwp' => '0123',
        'tax_status' => 'TK/2',
        'join_date' => '2016-12-01',
        'resign_date' => '',
        'gaji' => 13000000
    ],
    3 => [
        'nik' => '00003',
        'name' => 'NAMA 03',
        'npwp' => '',
        'tax_status' => 'TK/2',
        'join_date' => '2016-12-01',
        'resign_date' => '',
        'gaji' => 13000000
    ]
];

$arrTax = [];

$tax = new QueenApp\Tax\PPh21\PPh21(true);

$currentYear = date('Y');
$masaPajakAwal = $currentYear . '-01-01';
$masaPajakAkhir = $currentYear . '-12-31';
foreach ($arrEmployee as $idEmployee => $detailEmployee) {
    $strNik = $detailEmployee['nik'];
    $strName = $detailEmployee['name'];
    $bolNPWP = ($detailEmployee['npwp'] === '') ? false : true;
    $strTaxStatus = $detailEmployee['tax_status'];
    $strJoinDateTax = ($detailEmployee['join_date'] === '') ? $masaPajakAwal : $detailEmployee['join_date'];
    $arrJoinDate = explode('-', $strJoinDateTax);
    $strResignDateTax = ($detailEmployee['resign_date'] === '') ? $masaPajakAkhir : $detailEmployee['resign_date'];
    $arrResignDate = explode('-', $strResignDateTax);
    $intStartTax = (strtotime($strJoinDateTax) < strtotime($masaPajakAwal)) ? 1 : (int)$arrJoinDate[1];
    $intFinishTax = (int)$arrResignDate[1];
    $fltBaseTax = $detailEmployee['gaji'];

    $tax->calculateTax($fltBaseTax, 0, 0, $bolNPWP, $strTaxStatus, $intStartTax, $intFinishTax);
    $y = $tax->getTax(true, false);
    $arrTax[$idEmployee]['nik'] = $strNik;
    $arrTax[$idEmployee]['name'] = $strName;
    $arrTax[$idEmployee]['Tax'] = $y;
}

echo '<pre>';
print_r($arrTax);

