<?php

return [

    'audit-log'           => [
        'category'          => 'Sales master',
        'msg-index'         => 'Access sales master.',
        'msg-show'          => 'Accessed details of sale master.',
        'msg-create'        => 'Accessed new sale page.',
        'msg-store'         => 'Created new sale with number #:number.',
        'msg-edit'         => 'Accessed edit sale page with ID #:ID.',
        'msg-rollover'         => 'Accessed rollover sale page with ID #:ID.',
        'msg-store-rollover'   => 'Rollover sale with number #:number.',
        'msg-update'         => 'Edited sale with ID #:ID.',
        'msg-enabled-selected'  => 'Enabled multiple sale.',
        'msg-disabled-selected' => 'Disabled multiple sale.',
        'msg-due'       => 'Accessed sales due date notification page.',
    ],

    'status'              => [
        'created'                   => 'Sale successfully created',
        'updated'                   => 'Sale successfully updated',
        'deleted'                   => 'Sale successfully deleted',
        'global-enabled'            => 'Selected sales enabled.',
        'global-disabled'           => 'Selected sales disabled.',
        'enabled'                   => 'Sale enabled.',
        'disabled'                  => 'Sale disabled.',
        'no-sale-selected'          => 'No sale selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'not-editable'          => 'Selected sale is not editable because its commission period has ended.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Sales | Sales list',
            'description'       => 'Lists of sales',
            'table-title'       => 'Lists of sales',
        ],
        'show'              => [
            'title'             => 'Sales | Sale details',
            'description'       => 'Sale details',
            'section-title'     => 'Sale details'
        ],
        'create'              => [
            'title'             => 'Sales | New sale',
            'description'       => 'Create new sale',
            'section-title'     => 'Create new sale'
        ],
        'edit'              => [
            'title'             => 'Sales | Edit sale',
            'description'       => 'Edit existing sale (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit sale'
        ],
        'rollover'              => [
            'title'             => 'Sales | Rollover sale',
            'description'       => 'Rollover new sale',
            'section-title'     => 'Rollover new sale'
        ],
        'due'              => [
            'title'             => 'Sales | Sale Due Date',
            'description'       => 'Showing sales on due in selected interval.',
            'table-title'       => 'Due Sales',
            'info-title'        => 'Information',
            'export-description'=> 'Please select agent name and due start date and end date.'
        ],
    ],

    'columns'             => [
        'agent_id'   =>  'Agent',
        'product_id'   =>  'Product',
        'number'   =>  'Number',
        'customer_id'   =>  'Customer',
		'Tenor' => 'Tenor',
        'MGI'   =>  'MGI',
        'MGI_month'   =>  'MGI Month',
        'currency'   =>  'Currency',
        'MGI_start_date'   =>  'MGI Start Date',
        'nominal'   =>  'Nominal',
        'AgentName'   =>  'Agent',
        'ProductName'   =>  'Product',
        'CustomerName'   =>  'Customer',
        'agent_commission'   =>  'Agent Commission',
        'interest'   =>  'Interest',
        'additional'   =>  'Additional',
        'SPAJ'   =>  'SPAJ',
		'branch_office_id' => 'Branch Office',
		'bank' => 'Bank',
		'bank_branch' => 'Bank Branch',
		'account_name' => 'Account Name',
		'account_number' => 'Account Number',
		'branch_office_name' => 'Branch Office'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New sale',
    ],

];
