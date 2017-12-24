<?php

return [

    'audit-log'           => [
        'category'          => 'Holidays master',
        'msg-index'         => 'Access holidays master.',
        'msg-show'          => 'Accessed details of holiday master.',
        'msg-create'        => 'Accessed new holiday page.',
        'msg-store'         => 'Created new holiday with ID #:ID.',
        'msg-edit'         => 'Accessed edit holiday page with ID #:ID.',
        'msg-update'         => 'Edited holiday with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple holiday.',
        'msg-disabled-selected' => 'Disabled multiple holiday.',
        'msg-loaded' => 'Loaded holidays from Google Calendar API.',
    ],

    'status'              => [
        'created'                   => 'Holiday successfully created',
        'updated'                   => 'Holiday successfully updated',
        'deleted'                   => 'Holiday successfully deleted',
        'global-enabled'            => 'Selected holidays enabled.',
        'global-disabled'           => 'Selected holidays disabled.',
        'enabled'                   => 'Holiday enabled.',
        'disabled'                  => 'Holiday disabled.',
        'no-commission-selected'          => 'No holiday selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Holidays | Holidays list',
            'description'       => 'Lists of holidays',
            'table-title'       => 'Lists of holidays',
        ],
        'show'              => [
            'title'             => 'Holidays | Holiday details',
            'description'       => 'Holiday details',
            'section-title'     => 'Holiday details'
        ],
        'create'              => [
            'title'             => 'Holidays | New holiday',
            'description'       => 'Create new holiday',
            'section-title'     => 'Create new holiday'
        ],
        'edit'              => [
            'title'             => 'Holidays | Edit holiday',
            'description'       => 'Edit existing holiday (:id). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit holiday'
        ],
    ],

    'columns'             => [
        'date'   =>  'Date',
        'description'   =>  'Description',
        'year'   =>  'Year',
        'type'   =>  'Type'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New holiday',
    ],

];
