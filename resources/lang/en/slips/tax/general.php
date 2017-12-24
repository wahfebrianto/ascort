<?php

return [

    'audit-log'           => [
        'category'          => 'Tax slip',
        'msg-index'         => 'Access tax slip index.',
        'msg-export'        => 'Exported tax slip.',
    ],

    'status'              => [
        'created'                   => 'Tax successfully created',
        'updated'                   => 'Tax successfully updated',
        'deleted'                   => 'Tax successfully deleted',
        'global-enabled'            => 'Selected tax enabled.',
        'global-disabled'           => 'Selected tax disabled.',
        'enabled'                   => 'Tax enabled.',
        'disabled'                  => 'Tax disabled.',
        'no-tax-selected'    => 'No tax selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Slips | Tax slip',
            'description'       => 'Export tax slip in PDF',
            'info-title'       => 'Info',
            'calculate-title'  => 'Calculation',
            'export-title'     => 'Export',
            'export-description'     => 'Export all tax for all commission',
        ],
    ],

    'columns'             => [
        'date'   =>  'Period',
    ],

    'button'              => [
        'create'               => 'New tax',
    ],

];
