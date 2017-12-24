<?php

return [

    'audit-log'           => [
        'category'          => 'Log audit',
        'msg-index'         => 'Mengakses log audit.',
        'msg-show'          => 'Mengakses detail log audit.',
        'msg-purge'         => 'Menghapus data log audit lama.',
        'msg-replay'        => 'Melakukan replay terhadap log audit ID #:ID.',
    ],

    'status'              => [
        'purged'              => 'Log audit dibersihkan',
    ],

    'error'               => [
        'no-replay-available'   => 'Aksi replay tidak tersedia.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'Tidak ada data untuk ditampilkan.',
    ],

    'page'                => [
        'index'               => [
            'title'               => 'Admin | Audit log',
            'description'         => 'log of all actions performed by users',
            'table-title'         => 'Audit log',
        ],
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Audit log',
            'description'       => 'Audit log',
            'table-title'       => 'Audit log',
        ],
        'show'              => [
            'title'             => 'Admin | Audit log | Show',
            'description'       => 'Displaying Audit log',
            'section-title'     => 'Audit log details'
        ],
    ],

    'columns'             => [
        'username'            =>  'User name',
        'ip_address'            =>  'Alamat IP',
        'category'            =>  'Kategori',
        'message'             =>  'Pesan',
        'date'                =>  'Waktu',
        'data'                =>  'Data',
        'actions'             =>  'Actions',
    ],

    'button'              => [
        'purge'               => 'Purge entries older than :purge_retention days',
    ],

];
