<?php

return [

    'audit-log'           => [
        'category'          => 'Final Summary slip',
        'msg-index'         => 'Access final summary slip index.',
        'msg-export'        => 'Exported final summary slip.',
    ],

    'status'              => [
        'created'                   => 'Final Summary successfully created',
        'updated'                   => 'Final Summary successfully updated',
        'deleted'                   => 'Final Summary successfully deleted',
        'global-enabled'            => 'Selected final summaries enabled.',
        'global-disabled'           => 'Selected final summaries disabled.',
        'enabled'                   => 'Final Summary enabled.',
        'disabled'                  => 'Final Summary disabled.',
        'no-finalsummary-summary-selected'    => 'No final summary selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Final Summary Slip',
            'description'       => 'Export final summary slip in PDF',
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
        'create'               => 'New final summary',
    ],

];
