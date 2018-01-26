<?php

return [

    'audit-log'           => [
        'category'          => 'Sales master',
        'msg-index'         => 'Access sales master.',
        'msg-show'          => 'Accessed details of sale master.',
        'msg-create'        => 'Accessed new sale page.',
        'msg-store'         => 'Created new sale with number #:number.',
        'msg-edit'         => 'Accessed edit sale page with ID #:ID.',
        'msg-rollover'         => 'Accessed rollover sale page with ID #:ID.',
        'msg-store-rollover'   => 'Rollover sale with number #:number.',
        'msg-update'         => 'Edited sale with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple sale.',
        'msg-disabled-selected' => 'Disabled multiple sale.',
        'msg-due'       => 'Accessed sales due date notification page.',
    ],

    'status'              => [
        'created'                   => 'Sale successfully created',
        'updated'                   => 'Sale successfully updated',
        'deleted'                   => 'Sale successfully deleted',
        'global-enabled'            => 'Selected sales enabled.',
        'global-disabled'           => 'Selected sales disabled.',
        'enabled'                   => 'Sale enabled.',
        'disabled'                  => 'Sale disabled.',
        'no-sale-selected'          => 'No sale selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'not-editable'          => 'Selected sale is not editable because its commission period has ended.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Sales | Sales list',
            'description'       => 'Lists of sales',
            'table-title'       => 'Lists of sales',
        ],
        'show'              => [
            'title'             => 'Sales | Sale details',
            'description'       => 'Sale details',
            'section-title'     => 'Sale details'
        ],
        'create'              => [
            'title'             => 'Sales | New sale',
            'description'       => 'Create new sale',
            'section-title'     => 'Create new sale'
        ],
        'edit'              => [
            'title'             => 'Sales | Edit sale',
            'description'       => 'Edit existing sale (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit sale'
        ],
        'rollover'              => [
            'title'             => 'Sales | Rollover sale',
            'description'       => 'Rollover new sale',
            'section-title'     => 'Rollover new sale'
        ],
        'due'              => [
            'title'             => 'Sales | Sale Due Date',
            'description'       => 'Showing sales on due in selected interval.',
            'table-title'       => 'Due Sales',
            'info-title'        => 'Information',
            'export-description'=> 'Please select agent name and due start date and end date.'
        ],
    ],

    'columns'             => [
        'agent_id'   =>  'Nama Agen',
        'product_id'   =>  'Nama Produk',
        'number'   =>  'Nomor KTP',
        'customer_id'   =>  'Nama Investor',
        'MGI'   =>  'Tenor',
        'MGI_month'   =>  'MGI Month',
        'currency'   =>  'Currency',
        'MGI_start_date'   =>  'Tanggal Mulai Investasi',
        'nominal'   =>  'Nominal',
        'AgentName'   =>  'Nama Agen',
        'ProductName'   =>  'Nama Produk',
        'CustomerName'   =>  'Nama Investor',
        'agent_commission'   =>  'Agent Commission',
        'interest'   =>  'Imbal Hasil',
        'additional'   =>  'Keterangan Lain',
        'SPAJ'   =>  'SPAJ',
		'branch_office_id' => 'Kantor Cabang',
		'bank' => 'Nama Bank',
		'bank_branch' => 'Cabang Bank',
		'account_name' => 'Nama Akun',
		'account_number' => 'Nomor Rekening',
		'branch_office_name' => 'Kantor Cabang',
		'customer_name' => 'Nama Investor',
		'insurance_start_date' => 'Insurance'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New sale',
    ],

];
