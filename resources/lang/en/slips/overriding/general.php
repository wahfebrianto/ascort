<?php

return [

    'audit-log'           => [
        'category'          => 'Overriding slip',
        'msg-index'         => 'Access overriding slip index.',
        'msg-export'        => 'Exported overriding slip.',
    ],

    'status'              => [
        'created'                   => 'Overriding successfully created',
        'updated'                   => 'Overriding successfully updated',
        'deleted'                   => 'Overriding successfully deleted',
        'global-enabled'            => 'Selected overridings enabled.',
        'global-disabled'           => 'Selected overridings disabled.',
        'enabled'                   => 'Overriding enabled.',
        'disabled'                  => 'Overriding disabled.',
        'no-overriding-selected'    => 'No overriding selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Overriding slip',
            'description'       => 'Export overriding slip in PDF',
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
