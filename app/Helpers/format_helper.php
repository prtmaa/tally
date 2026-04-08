<?php

use Carbon\Carbon;

if (! function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp. ' . number_format($angka, 0, ',', '.');
    }
}

if (! function_exists('formatTanggalIndo')) {
    function formatTanggalIndo($tanggal)
    {
        if (!$tanggal) return null;
        Carbon::setLocale('id');
        return Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}

function usiaSejak($tanggal)
{
    if (!$tanggal) return '-';

    $diff = Carbon::parse($tanggal)->diff(now());

    $result = [];

    if ($diff->y > 0) {
        $result[] = $diff->y . ' tahun';
    }

    if ($diff->m > 0) {
        $result[] = $diff->m . ' bulan';
    }

    // hari tetap ditampilkan walaupun sudah ada tahun / bulan
    if ($diff->d > 0 || empty($result)) {
        $result[] = $diff->d . ' hari';
    }

    return implode(' ', $result);
}
