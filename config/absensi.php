<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Absensi
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk fitur absensi termasuk geofencing dan jam operasional.
    |
    */

    // Koordinat Lokasi Sekolah untuk Geofencing
    'school' => [
        'latitude' => env('SCHOOL_LATITUDE', -7.068262005100685),
        'longitude' => env('SCHOOL_LONGITUDE', 107.77621534998222),
        'radius' => env('SCHOOL_RADIUS', 200), // dalam meter
    ],

    // Jam Operasional Absensi
    'jam_operasional' => [
        'buka' => env('ABSENSI_JAM_BUKA', '06:00'),
        'tutup' => env('ABSENSI_JAM_TUTUP', '17:00'),
    ],

    // Aktifkan/Nonaktifkan validasi
    'validasi' => [
        'geofencing' => env('ABSENSI_GEOFENCING_ENABLED', true),
        'jam_operasional' => env('ABSENSI_JAM_ENABLED', true),
        'hari_libur' => env('ABSENSI_LIBUR_ENABLED', true),
    ],

];
