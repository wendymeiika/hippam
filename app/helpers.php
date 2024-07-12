<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('uploads')) {
    function uploads($file, $path)
    {
        $fileName = time().$file->getClientOriginalName();
        Storage::disk('public')->put($path.$fileName, File::get($file));
        $filePath = 'storage/'.$path.$fileName;

        return $fileName;
    }
}

if (!function_exists('bulan_indo')) {
    function bulan_indo($bulan)
    {
        $bulan = intval($bulan);
        switch ($bulan) {
            case 1:
                $bulan = 'Januari';
                break;
            case 2:
                $bulan = 'Februari';
                break;
            case 3:
                $bulan = 'Maret';
                break;
            case 4:
                $bulan = 'April';
                break;
            case 5:
                $bulan = 'Mei';
                break;
            case 6:
                $bulan = 'Juni';
                break;
            case 7:
                $bulan = 'Juli';
                break;
            case 8:
                $bulan = 'Agustus';
                break;
            case 9:
                $bulan = 'September';
                break;
            case 10:
                $bulan = 'Oktober';
                break;
            case 11:
                $bulan = 'November';
                break;
            case 12:
                $bulan = 'Desember';
                break;
        }

        return $bulan;
    }
}

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $var = explode('-', $tanggal);

        // var 0 = tanggal
        // var 1 = bulan
        // var 2 = tahun
        return $var[2].' '.$bulan[(int) $var[1]].' '.$var[0];
    }
}
