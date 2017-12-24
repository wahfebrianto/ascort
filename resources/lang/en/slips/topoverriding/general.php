<?php

return [

    'audit-log'           => [
        'category'          => 'Rec Fee slip',
        'msg-index'         => 'Access rec Fee slip index.',
        'msg-export'        => 'Exported rec Fee slip.',
    ],

    'status'              => [
        'created'                   => 'Rec Fee successfully created',
        'updated'                   => 'Rec Fee successfully updated',
        'deleted'                   => 'Rec Fee successfully deleted',
        'global-enabled'            => 'Selected Rec Fees enabled.',
        'global-disabled'           => 'Selected Rec Fees disabled.',
        'enabled'                   => 'Rec Fee enabled.',
        'disabled'                  => 'Rec Fee disabled.',
        'no-topoverriding-selected'    => 'No rec Fee selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Rec Fee Slip',
            'description'       => 'Export rec fee slip in PDF',
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
        'create'               => 'New rec Fee',
    ],

];
