<?php

return [

    'audit-log'           => [
        'category'          => 'Rec Fee Summary slip',
        'msg-index'         => 'Access rec Fee Summary slip index.',
        'msg-export'        => 'Exported rec Fee Summary slip.',
    ],

    'status'              => [
        'created'                   => 'Rec Fee Summary successfully created',
        'updated'                   => 'Rec Fee Summary successfully updated',
        'deleted'                   => 'Rec Fee Summary successfully deleted',
        'global-enabled'            => 'Selected Rec Fees enabled.',
        'global-disabled'           => 'Selected Rec Fees disabled.',
        'enabled'                   => 'Rec Fee Summary enabled.',
        'disabled'                  => 'Rec Fee Summary disabled.',
        'no-topoverriding-selected'    => 'No rec Fee Summary selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Rec Fee Summary Slip',
            'description'       => 'Export Rec Fee Summary slip in PDF',
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
