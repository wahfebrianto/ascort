<?php

return [

    'audit-log'           => [
        'category'          => 'Sale Commissions master',
        'msg-index'         => 'Access sale commissions master.',
        'msg-show'          => 'Accessed details of sale commission master.',
        'msg-create'        => 'Accessed new sale commission page.',
        'msg-store'         => 'Created new sale commission with ID #:id.',
        'msg-edit'         => 'Accessed edit sale commission page with ID #:ID.',
        'msg-update'         => 'Edited sale commission with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple sale commission.',
        'msg-disabled-selected' => 'Disabled multiple sale commission.',
    ],

    'status'              => [
        'created'                   => 'Sale Commission successfully created',
        'updated'                   => 'Sale Commission successfully updated',
        'deleted'                   => 'Sale Commission successfully deleted',
        'global-enabled'            => 'Selected sale commissions enabled.',
        'global-disabled'           => 'Selected sale commissions disabled.',
        'enabled'                   => 'Sale Commission enabled.',
        'disabled'                  => 'Sale Commission disabled.',
        'no-commission-selected'          => 'No sale commission selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Sale Commissions | Sale Commissions list',
            'description'       => 'Lists of sale commissions',
            'table-title'       => 'Lists of sale commissions',
        ],
        'show'              => [
            'title'             => 'Sale Commissions | Sale Commission details',
            'description'       => 'Sale Commission details',
            'section-title'     => 'Sale Commission details'
        ],
        'create'              => [
            'title'             => 'Sale Commissions | New sale commission',
            'description'       => 'Create new sale commission',
            'section-title'     => 'Create new sale commission'
        ],
        'edit'              => [
            'title'             => 'Sale Commissions | Edit sale commission',
            'description'       => 'Edit existing sale commission (:id). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit sale commission'
        ],
    ],

    'columns'             => [
        'agent_position_id'   =>  'Agent Position',
        'percentage'   =>  'Commission (%)'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New sale commission',
    ],

];
