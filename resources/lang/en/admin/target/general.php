<?php

return [

    'audit-log'           => [
        'category'          => 'Sales Targets master',
        'msg-index'         => 'Access sales targets master.',
        'msg-show'          => 'Accessed details of sales target master.',
        'msg-create'        => 'Accessed new sales target page.',
        'msg-store'         => 'Created new sales target with ID :id.',
        'msg-edit'         => 'Accessed edit sales target page with ID :id.',
        'msg-update'         => 'Edited sales target with ID :id.',
        'msg-enabled-selected'  => 'Enabled multiple sales target.',
        'msg-disabled-selected' => 'Disabled multiple sales target.',
    ],

    'status'              => [
        'created'                   => 'Sales Target successfully created',
        'updated'                   => 'Sales Target successfully updated',
        'deleted'                   => 'Sales Target successfully deleted',
        'global-enabled'            => 'Selected sales targets enabled.',
        'global-disabled'           => 'Selected sales targets disabled.',
        'enabled'                   => 'Sales Target enabled.',
        'disabled'                  => 'Sales Target disabled.',
        'no-sales-target-selected'          => 'No sales target selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Sales Targets | Sales Targets list',
            'description'       => 'Lists of sales targets',
            'table-title'       => 'Lists of sales targets',
            'hierarchy'         => 'Hierarchy Visualizer',
        ],
        'show'              => [
            'title'             => 'Sales Targets | Sales Target details',
            'description'       => 'Sales Target details',
            'section-title'     => 'Sales Target details'
        ],
        'create'              => [
            'title'             => 'Sales Targets | New sales target',
            'description'       => 'Create new sales target',
            'section-title'     => 'Create new sales target'
        ],
        'edit'              => [
            'title'             => 'Sales Targets | Edit sales target',
            'description'       => 'Edit existing sales target (:id). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit sales target'
        ],
    ],

    'columns'             => [
        'agent_position_id'   =>  'Agent Position',
        'target_amount'       =>  'Target Amount'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New sales target',
    ],

];
