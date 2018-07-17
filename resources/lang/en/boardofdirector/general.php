<?php

return [

    'audit-log'           => [
        'category'          => 'Master Board of Director',
        'msg-index'         => 'Akses Master Board of Director.',
        'msg-show'          => 'Detail Master Board of Director telah diakses.',
        'msg-create'        => 'Halaman Board of Director baru telah diakses.',
        'msg-store'         => 'Board of Direcotr baru telah terbuat dengan nama :name.',
        'msg-edit'         => 'Terakses halaman edit Board of Director dengan ID #:ID.',
        'msg-update'         => 'Telah terubah Board of Director dengan ID #:ID.',
        'msg-update-delay'         => 'Change for attribute :key is cannot be executed directly and delayed.',
        'msg-update-approval'         => 'Change for attribute :key is waiting for owner approval.',
        'msg-enabled-selected'  => 'Enabled multiple Branch Office.',
        'msg-disabled-selected' => 'Disabled multiple Branch Office.',
        'msg-summarypage' => 'Accessed Branch Office summary page',
    ],

    'status'              => [
        'created'                   => 'Board of Director successfully created',
        'updated'                   => 'Board Of Director successfully updated',
        'deleted'                   => 'Board Of Director successfully deleted',
        'global-enabled'            => 'Selected Board Of Directors enabled.',
        'global-disabled'           => 'Selected Board Of Directors disabled.',
        'enabled'                   => 'Board Of Director enabled.',
        'disabled'                  => 'Board Of Director disabled.',
        'no-bod-selected'          => 'No Board Of Director selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'image-invalid'         => 'Image file is invalid. Please check the file.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Board of Director | Daftar Board of Director',
            'description'       => 'Daftar Board of Director',
            'table-title'       => 'Daftar Board of Director',
        ],
        'show'              => [
            'title'             => 'Board of Director | Detail Board of Director',
            'description'       => 'Detail Board of Director (:name)',
            'section-title'     => 'Detail Board of Director',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
        'create'              => [
            'title'             => 'Board of Director | Board of Director Baru',
            'description'       => 'Board of Director Baru',
            'section-title'     => 'Board of Director Baru',
            'general-info'      => 'Basic Info',
            'idcard-info'       => 'ID Card Info',
            'bod-info'        => 'Board Of Director Detailed Info',
        ],
        'edit'              => [
            'title'             => 'Board of Director | Edit Board of Director',
            'description'       => 'Edit Board of Director (:name). Hati-hati! Setelah tersimpan, data tidak dapat dikembalikan.',
            'section-title'     => 'Edit Board of Director',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
    ],

    'columns'             => [
        'bod_name'	=>	'Nama Board of Director',
        'address'	=>	'Alamat',
        'state'	=>	'Provinsi',
        'city'	=>	'Kota',
        'zipcode' => 'Kode Pos',
        'phone_number'	=>	'Telepon',
        'email' => 'Email',
        'type' => 'Type',
        'identity_number' => 'No Identitas',
        'npwp' => 'NPWP',
        'position' => 'Jabatan',
        'bank_name' => 'Bank Name',
        'bank_branch' => 'Bank Cabang',
        'acc_number' => 'No Rekening',
        'acc_name' => 'Atas Nama'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info',
        'id_card' => 'ID Card',
        'bod_detail' => 'Board Of Director Details',
        'sales' => 'Board Of Director Sales',
        'child' => 'Child Board Of Director',
    ],

    'button'              => [
        'create'               => 'Tambah Board of Director',
    ],

];
