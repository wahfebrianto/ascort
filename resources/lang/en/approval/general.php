<?php

return [

    'audit-log'           => [
        'category'          => 'Approvals',
        'msg-index'         => 'Access approvals.',
        'msg-show'          => 'Accessed details of approval.',
        'msg-approved'          => 'Approved approval with ID #:ID.',
        'msg-disabled'          => 'Disabled approval with ID #:ID.',
        'msg-disabled-selected' => 'Disabled multiple approval.'
    ],

    'status'              => [
        'deleted'                   => 'Approval successfully deleted',
        'global-deleted'            => 'Selected approvals deleted.',
        'approved'                  => 'Approval approved.',
        'deleted'                   => 'Approval deleted.',
        'no-approval-selected'      => 'No approval selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Approvals | Approvals list',
            'description'       => 'Lists of approvals',
            'table-title'       => 'Lists of approvals',
        ],
        'show'              => [
            'title'             => 'Approvals | Approval details',
            'description'       => 'Approval details',
            'section-title'     => 'Approval details'
        ],
    ],

    'columns'             => [
        'user'   =>  'From',
        'description'   =>  'Description',
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

];
