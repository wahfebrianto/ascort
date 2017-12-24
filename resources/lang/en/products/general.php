<?php

return [

    'audit-log'           => [
        'category'          => 'Products master',
        'msg-index'         => 'Access products master.',
        'msg-show'          => 'Accessed details of product master.',
        'msg-create'        => 'Accessed new product page.',
        'msg-store'         => 'Created new product with code #:product_code.',
        'msg-edit'         => 'Accessed edit product page with ID #:ID.',
        'msg-update'         => 'Edited product with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple product.',
        'msg-disabled-selected' => 'Disabled multiple product.',
    ],

    'status'              => [
        'created'                   => 'Product successfully created',
        'updated'                   => 'Product successfully updated',
        'deleted'                   => 'Product successfully deleted',
        'global-enabled'            => 'Selected products enabled.',
        'global-disabled'           => 'Selected products disabled.',
        'enabled'                   => 'Product enabled.',
        'disabled'                  => 'Product disabled.',
        'no-product-selected'          => 'No product selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Products | Products list',
            'description'       => 'Lists of products',
            'table-title'       => 'Lists of products',
        ],
        'show'              => [
            'title'             => 'Products | Product details',
            'description'       => 'Product details',
            'section-title'     => 'Product details'
        ],
        'create'              => [
            'title'             => 'Products | New product',
            'description'       => 'Create new product',
            'section-title'     => 'Create new product'
        ],
        'edit'              => [
            'title'             => 'Products | Edit product',
            'description'       => 'Edit existing product (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit product'
        ],
    ],

    'columns'             => [
        'product_code'   =>  'Product Code',
        'product_name'   =>  'Name'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New product',
    ],

];
