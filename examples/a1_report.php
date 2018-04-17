<?php
/**
 * Created by QueenApp.
 * User: Ade Sanusi
 * Date: 17/04/2018
 * Time: 14.34
 */

require '../vendor/autoload.php';

use QueenApp\Tax\A1Report;

$arrEmployee = [
    1 => [
        'id' => 12345,
        'employee_name' => 'EMPLOYEE 1',
        'position' => 'PROGRAMMER',
        'gender' => 1,
        'children' => 1,
        'tax_status' => 'K1',
        'npwp' => '12.345.678.9-123.123',
        'id_card' => '123456789',
        'address' => 'JALAN ADA AJA NOMOR 25/31 BEKASI - JAWA BARAT',
        'start_tax_period' => 1,
        'finish_tax_period' => 12,
        'spt_number' => '1.1-12.18-0000001',
        'basic_salary' => 120000000,
        'tax_allowance' => 500000,
        'other_allowance' => 5000000,
        'insurance_allowance' => 750000,
        'irregular_allowance' => 10000000,
        'biaya_jabatan' => 6000000,
        'bpjs_deduction' => 1000000,
        'ptkp' => 64000000,
        'total_annual_tax' => 500000,
        'total_monthly_tax' => 500000,
        'company_name' => 'PT MAJU MUNDUR KENA',
        'company_npwp' => '88.888.888.8-888.888',
        'sign_name' => 'ADE SANUSI',
        'sign_npwp' => '11.111.111.1-111.111',
        'previous_tax' => 0,
        'previous_base_tax' => 0
    ],
    2 => [
        'id' => 12346,
        'employee_name' => 'EMPLOYEE 2',
        'position' => 'PROGRAMMER',
        'gender' => 0,
        'children' => 0,
        'tax_status' => 'TK',
        'npwp' => '12.345.678.9-123.124',
        'id_card' => '123456789',
        'address' => 'JALAN ADA AJA NOMOR 25/31 BEKASI - JAWA BARAT',
        'start_tax_period' => 1,
        'finish_tax_period' => 12,
        'spt_number' => '1.1-12.18-0000002',
        'basic_salary' => 120000000,
        'tax_allowance' => 500000,
        'other_allowance' => 5000000,
        'insurance_allowance' => 750000,
        'irregular_allowance' => 10000000,
        'biaya_jabatan' => 6000000,
        'bpjs_deduction' => 1000000,
        'ptkp' => 64000000,
        'total_annual_tax' => 500000,
        'total_monthly_tax' => 500000,
        'company_name' => 'PT MAJU MUNDUR KENA',
        'company_npwp' => '88.888.888.8-888.888',
        'sign_name' => 'ADE SANUSI',
        'sign_npwp' => '11.111.111.1-111.111',
        'previous_tax' => 0,
        'previous_base_tax' => 0
    ]
];

$a1 = new \QueenApp\Tax\A1Report\A1Report(2018);
$a1->setMultiplePage();
$a1->setDataA1Employee($arrEmployee);
$a1->doGenerateA1Report();