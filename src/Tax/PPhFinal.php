<?php
/**
 * Created by PhpStorm.
 * User: Ade Sanusi
 * Date: 07/02/2018
 * Time: 14.21
 */

namespace QueenApp\Tax\PPhFinal;

/**
 * Class PPhFinal a.k PPh Pasal 4 Ayat 2
 * @package QueenApp\Tax\PPhFinal
 *
 * No.    Objek PPh Pasal 4 Ayat 2    Tarif (%)    Peraturan yang Berlaku
 * 1.    Bunga deposito / tabungan, diskonto SBI dan jasa giro****    20    Pasal 4 (2) a UU PPh jo PP 131 Thn 2000 jo KMK 51/KOM.04/2001
 * 2.    Bunga simpanan yang dibayarkan oleh koperasi kepada anggota koperasi orang pribadi ^    10    Pasal 4 (2) a & Pasal 17 (7) jo PP No.15 Thn 2009
 * 3.    Bunga obligasi (surat utang & SUN lebih dari 12 bulan) ^^^        Pasal 4 (2) a UU PPh jo PP No. 16 Thn 2009
 * 3a.    Bunga dari obligasi dengan kupon bagi WP dalam negeri & BUT    15    idem
 * 3b.    Bunga dari obligasi dengan kupon bagi WP luar negeri non BUT seusai P3B    20    idem
 * 3c.    Diskonto dari obligasi dengan kupon bagi WP luar negeri non BUT seusai BUT*    15    idem
 * 3d.    Diskonto dari obligasi dengan kupon bagi WP luar negeri non BUT seusai P3B*    20    idem
 * 3e.    Diskonto dari obligasi tanpa bunga bagi WP dalam negeri dan BUT**    15    idem
 * 3f.    Diskonto dari obligasi tanpa bunga bagi WP luar negeri non BUT sesuai P3B**    20    idem
 * 3g.    Bunga dan/atau diskonto dari obligasi yang diterima dan/atau diperoleh WP reksadana yang terdaftar pada Badan Pengawas Pasar Modal dan Lembaga Keuangan untuk tahun 2009 - 2010.    0    idem
 * 3h.    Bunga dan/atau diskonto dari obligasi yang diterima dan/atau diperoleh WP    5    idem
 * 3i.    Bunga dan/atau diskonto dari obligasi yang diterima dan/atau diperoleh WP reksadana yang terdaftar pada Badan Pengawas Pasar Modal dan Lembaga Keuangan untuk tahun 2014, dst.    15    idem
 * 4.    Deviden yang diterima/diperoleh WP orang pribadi dalam negeri    10    Pasal 17 (2c) dan Pasal 4 (2) UU PPh
 * 5.    Hadiah undian    25    Pasal 4 (2) b UU PPh jo PP No. 132 thn 2000
 * 6.    Transaksi derivatif berupa kontrak berjangka yang diperdagangkan di bursa***    2,5    Pasal 4 (2) c UU PPh jo PP No. 17 thn 2009
 * 7a.    Transaksi penjualan saham pendiri    0,5    PP No. 14 Thn 1997 jo KMK 282/KMK.04/1997 jo SE-15/PJ.42/1997 dan SE 06/PJ.4/1997
 * 7b.    Transaksi penjualan bukan saham pendiri    0,1    idem
 * 8.    Jasa konstruksi        Pasal 4 (2) c UU PPh jo PP No. 51 Thn 2008 jo PP No. 40 thn 2009
 * 8a.    Pelaksana JK sertifikasi kecil    2    idem
 * 8b.    Pelaksana JK tanpa sertifikasi    4    idem
 * 8c.    Pelaksana Jk sertifikasi sedang dan besar    3    idem
 * 8d.    Perancang atau pengawas JK oleh penyedia JK bersertifikasi usaha    4    idem
 * 8e.    Perancang atau pengawas JK oleh penyedia JK tanpa bersertifikasi usaha    6    idem
 * 9.    Persewaan atas tanah dan/atau bangunan    10    Peraturan Pemerintah No. 29 Thn 1996 jo PP No.05 thn 2002
 * 10a    WaP yang melakukan pengalihan hak atas tanah dan/atau bangunan (termasuk usaha real estate)^*    5    Pasal 4 (2) d UU PPh jo PP no. 71 thn 2008
 * 10b    Pengalihan Rumah Sederhana & Rumah Susun Sederhana oleh WP yang usaha pokoknya melakukan Pengalihan Hak atas Tanah dan/atau Bangunan    1    idem
 * 11    Transaksi penjualan saham atau pengalihan penyertaan modal pada perusahaan pasangannya yang diterima oleh perusahaan modal ventura    0,1    PP No. 4 tahun 1995
 *
 */
class PPhFinal
{
    private $objekPajakPPhFinal = '1';
    private $tarif = 0;
    private $arrTarif = [];

    public function __construct()
    {

    }

    protected function initialArrTarif()
    {

        $this->arrTarif = [
            '1' => [
                'tarif' => 20,
                'objek_pajak' => 'Bunga deposito / tabungan, diskonto SBI dan jasa giro',
                'regulation' => 'Pasal 4 (2) a UU PPh jo PP 131 Thn 2000 jo KMK 51/KOM.04/2001'
            ],
            '2' => [
                'tarif' => 10,
                'objek_pajak' => 'Bunga simpanan yang dibayarkan oleh koperasi kepada anggota koperasi orang pribadi',
                'regulation' => 'Pasal 4 (2) a & Pasal 17 (7) jo PP No.15 Thn 2009'
            ]
        ];

        return $this->arrTarif;
    }
}