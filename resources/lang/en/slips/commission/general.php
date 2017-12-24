<?php

return [

    'audit-log'           => [
        'category'          => 'Commission slip',
        'msg-index'         => 'Access commission slip index.',
        'msg-export'        => 'Exported commission slip.',
    ],

    'status'              => [
        'created'                   => 'Commission successfully created',
        'updated'                   => 'Commission successfully updated',
        'deleted'                   => 'Commission successfully deleted',
        'global-enabled'            => 'Selected commissions enabled.',
        'global-disabled'           => 'Selected commissions disabled.',
        'enabled'                   => 'Commission enabled.',
        'disabled'                  => 'Commission disabled.',
        'no-commission-selected'    => 'No commission selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Commission Slip',
            'description'       => 'Export commission slip in PDF',
            'info-title'       => 'Info',
            'calculate-title'  => 'Calculation',
            'export-title'     => 'Export',
            'export-description'     => 'Description',
        ],
        'minus' => [
            'input-title' => 'Add agents having minus correction',
            'export-title' => 'Export'
        ]
    ],

    'columns'             => [
        'date'   =>  'Period',
    ],

    'button'              => [
        'create'               => 'New commission',
    ],

];
