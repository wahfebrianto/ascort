<?php

return [

    'audit-log'           => [
        'category'          => 'Overriding summaryslip',
        'msg-index'         => 'Access overriding summary slip index.',
        'msg-export'        => 'Exported overriding summary slip.',
    ],

    'status'              => [
        'created'                   => 'Overriding summary successfully created',
        'updated'                   => 'Overriding summary successfully updated',
        'deleted'                   => 'Overriding summary successfully deleted',
        'global-enabled'            => 'Selected overridings enabled.',
        'global-disabled'           => 'Selected overridings disabled.',
        'enabled'                   => 'Overriding summary enabled.',
        'disabled'                  => 'Overriding summary disabled.',
        'no-overriding-selected'    => 'No overriding summary selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Overriding Summary Slip',
            'description'       => 'Export overriding summary slip in PDF',
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
        'create'               => 'New overriding',
    ],

];
