<?php

return [

    'audit-log'           => [
        'category'          => 'MGIs master',
        'msg-index'         => 'Access MGIs master.',
        'msg-create'        => 'Accessed new MGI page.',
        'msg-store'         => 'Created new MGI with Code #:code.',
        'msg-edit'         => 'Accessed edit MGI page with Code #:code.',
        'msg-update'         => 'Edited MGI with Code #:code.',
        'msg-delete'         => 'Deleted MGI with Code #:code.',
    ],

    'status'              => [
        'created'                   => 'MGI successfully created',
        'updated'                   => 'MGI successfully updated',
        'deleted'                   => 'MGI successfully deleted',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'MGIs | MGIs list',
            'description'       => 'Lists of MGIs',
            'table-title'       => 'Lists of MGIs',
        ],
        'show'              => [
            'title'             => 'MGIs | MGI details',
            'description'       => 'MGI details',
            'section-title'     => 'MGI details'
        ],
        'create'              => [
            'title'             => 'MGIs | New MGI',
            'description'       => 'Create new MGI',
            'section-title'     => 'Create new MGI'
        ],
        'edit'              => [
            'title'             => 'MGIs | Edit MGI',
            'description'       => 'Edit existing MGI (:code). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit MGI'
        ],
    ],

    'columns'             => [
        'code'   =>  'MGI Code',
        'name'   =>  'MGI Name',
        'month'   =>  'Duration (month)'
    ],

    'tabs' => [
        'basic' => 'Basic Info'
    ],

    'button'              => [
        'create'               => 'New MGI',
    ],

];
