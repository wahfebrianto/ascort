<?php

return [

    'audit-log'           => [
        'category'          => 'Master Kantor Cabang',
        'msg-index'         => 'Akses Master Kantor Cabang.',
        'msg-show'          => 'Detail Master Kantor Cabang telah diakses.',
        'msg-create'        => 'Halaman Kantor Cabang baru telah diakses.',
        'msg-store'         => 'Kantor Cabang baru telah terbuat dengan nama :name.',
        'msg-edit'         => 'Terakses halaman edit Kantor Cabang dengan ID #:ID.',
        'msg-update'         => 'Telah terubah Kantor Cabang dengan ID #:ID.',
        'msg-update-delay'         => 'Change for attribute :key is cannot be executed directly and delayed.',
        'msg-update-approval'         => 'Change for attribute :key is waiting for owner approval.',
        'msg-enabled-selected'  => 'Enabled multiple Branch Office.',
        'msg-disabled-selected' => 'Disabled multiple Branch Office.',
        'msg-summarypage' => 'Accessed Branch Office summary page',
    ],

    'status'              => [
        'created'                   => 'Agent successfully created',
        'updated'                   => 'Agent successfully updated',
        'deleted'                   => 'Agent successfully deleted',
        'global-enabled'            => 'Selected agents enabled.',
        'global-disabled'           => 'Selected agents disabled.',
        'enabled'                   => 'Agent enabled.',
        'disabled'                  => 'Agent disabled.',
        'no-agent-selected'          => 'No agent selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'image-invalid'         => 'Image file is invalid. Please check the file.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Kantor Cabang | Daftar Kantor Cabang',
            'description'       => 'Daftar Kantor Cabang',
            'table-title'       => 'Daftar Kantor Cabang',
        ],
        'show'              => [
            'title'             => 'Kantor Cabang | Detail Kantor Cabang',
            'description'       => 'Detail Kantor Cabang (:name)',
            'section-title'     => 'Detail Kantor Cabang',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
        'create'              => [
            'title'             => 'Kantor Cabang | Kantor Cabang Baru',
            'description'       => 'Kantor Cabang Baru',
            'section-title'     => 'Kantor Cabang Baru',
            'general-info'      => 'Basic Info',
            'idcard-info'       => 'ID Card Info',
            'agent-info'        => 'Agent Detailed Info',
        ],
        'edit'              => [
            'title'             => 'Kantor Cabang | Edit Kantor Cabang',
            'description'       => 'Edit Kantor Cabang (:name). Hati-hati! Setelah tersimpan, data tidak dapat dikembalikan.',
            'section-title'     => 'Edit Kantor Cabang',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
    ],

    'columns'             => [
        'name'	=>	'Nama Kantor Cabang',
        'branch_name'	=>	'Nama Kantor Cabang',
        'address'	=>	'Alamat',
        'state'	=>	'Provinsi',
        'city'	=>	'Kota',
        'phone_number'	=>	'Telepon'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info',
        'id_card' => 'ID Card',
        'agent_detail' => 'Agent Details',
        'sales' => 'Agent Sales',
        'child' => 'Child Agent',
    ],

    'button'              => [
        'create'               => 'Tambah Kantor Cabang',
    ],

];
