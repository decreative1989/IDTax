# IDTax (Indonesian Tax Class PHP)

Note: Library PHP Class for Tax in Indonesian Regulation : [PER16-PJ-2016](http://www.pajak.go.id/sites/default/files/info-pajak/PER16-PJ-2016.pdf) or look at "regulations" directory.


* PPh21 (Pajak Penghasilan) Untuk Wajib Pajak Dalam Negeri.
* PPh26 (Pajak Penghasilan) Untuk Wajib Pajak Luar Negeri.


Created by: [Ade Sanusi](https://facebook.com/de.creative).


## Installation
#### Git ###

Download or Clone this Project in your localhost directory:

```
https://github.com/decreative1989/IDTax.git
```

#### Composer ###
Installation is easy using Composer. Include the following in your composer.json
```
{
  "autoload": {
    "classmap": [
      "src/Tax/PPh21.php",
      "src/Tax/PPh26.php",
      # other Source from src/Tax # REMOVE THIS LINE.
    ]
  }
}
```

You may also manually include the PPh21.php file
```php
require 'src/Tax/PPh21.php';;
```

Or check example.php file.

## Features

* PPh21 ( Tax Method : Gross & Gross Up , Tax Result : Regular Tax Yearly, Regular Tax Monthly & Irregular Tax)
* PPh26
* _Coming Soon :)_


## Example Usage

**Reference from example.php:**

```php
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


```

**Other Option:**

```php
$y = $tax->getTax(true, true); # getTax($a, $b) : $a => ( false = gross, true = gross up) $b => ( false = monthly tax, true = yearly).
```



## Credits

* Class IDTax is based on the concept of [Ade Sanusi][as].

[as]: http://facebook.com/de.creative



## License

(The MIT license)

Copyright (c) 2018 Ade Sanusi.
QueenApp | IDTax

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
