<?php

return [

    'audit-log'           => [
        'category'          => 'Overriding Commissions master',
        'msg-index'         => 'Access overriding commissions master.',
        'msg-show'          => 'Accessed details of overriding commission master.',
        'msg-create'        => 'Accessed new overriding commission page.',
        'msg-store'         => 'Created new overriding commission with ID #:ID.',
        'msg-edit'         => 'Accessed edit overriding commission page with ID #:ID.',
        'msg-update'         => 'Edited overriding commission with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple overriding commission.',
        'msg-disabled-selected' => 'Disabled multiple overriding commission.',
    ],

    'status'              => [
        'created'                   => 'Overriding Commission successfully created',
        'updated'                   => 'Overriding Commission successfully updated',
        'deleted'                   => 'Overriding Commission successfully deleted',
        'global-enabled'            => 'Selected overriding commissions enabled.',
        'global-disabled'           => 'Selected overriding commissions disabled.',
        'enabled'                   => 'Overriding Commission enabled.',
        'disabled'                  => 'Overriding Commission disabled.',
        'no-commission-selected'          => 'No overriding commission selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Overriding Commissions | Overriding Commissions list',
            'description'       => 'Lists of overriding commissions',
            'table-title'       => 'Lists of overriding commissions',
        ],
        'show'              => [
            'title'             => 'Overriding Commissions | Overriding Commission details',
            'description'       => 'Overriding Commission details',
            'section-title'     => 'Overriding Commission details'
        ],
        'create'              => [
            'title'             => 'Overriding Commissions | New overriding commission',
            'description'       => 'Create new overriding commission',
            'section-title'     => 'Create new overriding commission'
        ],
        'edit'              => [
            'title'             => 'Overriding Commissions | Edit overriding commission',
            'description'       => 'Edit existing overriding commission (:id). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit overriding commission'
        ],
    ],

    'columns'             => [
        'agent_position_id'   =>  'Agent Position',
        'override_from'   =>  'Override From',
        'level'   =>  'Level',
        'percentage'   =>  'Commission (%)'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New overriding commission',
    ],

];
