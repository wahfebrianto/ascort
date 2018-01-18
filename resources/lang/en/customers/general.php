<?php

return [

    'audit-log'           => [
        'category'          => 'Customers master',
        'msg-index'         => 'Access customers master.',
        'msg-show'          => 'Accessed details of customer master.',
        'msg-create'        => 'Accessed new customer page.',
        'msg-store'         => 'Created new customer with NIK #:NIK.',
        'msg-edit'         => 'Accessed edit customer page with ID #:ID.',
        'msg-update'         => 'Edited customer with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple customer.',
        'msg-disabled-selected' => 'Disabled multiple customer.',
    ],

    'status'              => [
        'created'                   => 'Customer successfully created',
        'updated'                   => 'Customer successfully updated',
        'deleted'                   => 'Customer successfully deleted',
        'global-enabled'            => 'Selected customers enabled.',
        'global-disabled'           => 'Selected customers disabled.',
        'enabled'                   => 'Customer enabled.',
        'disabled'                  => 'Customer disabled.',
        'no-customer-selected'          => 'No customer selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'image-invalid'         => 'Image file is invalid. Please check the file.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Customers | Customers list',
            'description'       => 'Lists of customers',
            'table-title'       => 'Lists of customers',
        ],
        'show'              => [
            'title'             => 'Customers | Customer details',
            'description'       => 'Customer details',
            'section-title'     => 'Customer details'
        ],
        'create'              => [
            'title'             => 'Customers | New customer',
            'description'       => 'Create new customer',
            'section-title'     => 'Create new customer',
            'general-info'      => 'Data Pelanggan',
            'correspondence-info'=> 'Data Korespondensi',
        ],
        'edit'              => [
            'title'             => 'Customers | Edit customer',
            'description'       => 'Edit existing customer (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit customer'
        ],
        'admin_edit'              => [
            'title'             => 'Customers | Edit customer',
            'description'       => 'Edit existing customer. All changes will be approved first by owner.',
            'table-title'       => 'Edit customer',
            'info-title'        => 'Information',

        ],
    ],

    'columns'             => [
        'NIK'                   =>  'NIK',
        'NPWP'                  =>  'NPWP',
        'name'                  =>  'Nama',
        'phone_number'          =>  'Telepon',
        'handphone_number'      =>  'Handphone',
        'email'                 =>  'Email',
        'zipcode'               =>  'Kode Pos',
        'cor_address'           =>  'Alamat',
        'cor_state'             =>  'Provinsi',
        'cor_city'              =>  'Kota',
        'cor_zipcode'           =>  'Kode Pos',
        'birth_place_state'     =>  'Provinsi Tempat Lahir',
        'birth_place'           =>  'Kota Kelahiran',
        'DOB'                   =>  'Date of Birth',
        'gender'                =>  'Jenis Kelamin',
        'address'               =>  'Alamat',
        'state'                 =>  'Provinsi',
        'city'                  =>  'Kota',
        'religion'              =>  'Religion',
        'marriage_status'       =>  'Marriage Status',
        'occupation'            =>  'Occupation',
        'nationality'           =>  'Nationality',
        'id_card_expiry_date'   =>  'ID Card Expiry Date',
        'id_card_image_filename' =>  'ID Card Image Filename',
        'id_card_image'         =>  'ID Card Image',
        'is_active'             =>  'Aktif',
        'created_at'            =>  'Dibuat Tanggal',
        'updated_at'            =>  'Terakhir Diubah',
        'saved_image'           => 'Saved ID Card Image',
        'last_transaction'      => 'Transaksi Terakhir',
        'correspondence_address'        => 'Alamat Korespondensi',
        'main_address'          => 'Alamat Utama',
		'branch_office_id'		=>	'Branch Office'
    ],

    'tabs' => [
        'basic' => 'Data Pelanggan',
        'details' => 'Data Korespondensi',
        'id_card' => 'ID Card',
        'sales' => 'Data Pembelian',
    ],

    'button'              => [
        'create'               => 'Pelanggan Baru',
    ],

];
