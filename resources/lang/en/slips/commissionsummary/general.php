<?php

return [

    'audit-log'           => [
        'category'          => 'Commission Summary slip',
        'msg-index'         => 'Access commission summary slip index.',
        'msg-export'        => 'Exported commission summary slip.',
    ],

    'status'              => [
        'created'                   => 'Commission summary successfully created',
        'updated'                   => 'Commission summary successfully updated',
        'deleted'                   => 'Commission summary successfully deleted',
        'global-enabled'            => 'Selected commission summary summaries enabled.',
        'global-disabled'           => 'Selected commission summary summaries disabled.',
        'enabled'                   => 'Commission summary enabled.',
        'disabled'                  => 'Commission summary disabled.',
        'no-commission summary-selected'    => 'No commission summary selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Commission Summary Slip',
            'description'       => 'Export commission summary slip in PDF',
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
        'create'               => 'New commission summary',
    ],

];
