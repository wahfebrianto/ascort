<?php

return [

    'audit-log'           => [
        'category'          => 'Master Agen',
        'msg-index'         => 'Akses Master Agen.',
        'msg-show'          => 'Detail Master Agen telah diakses.',
        'msg-create'        => 'Halaman Agen baru telah diakses.',
        'msg-store'         => 'Agen baru telah terbuat dengan NIK #:NIK.',
        'msg-edit'         => 'Terakses halaman edit agen dengan ID #:ID.',
        'msg-update'         => 'Telah terubah agen dengan ID #:ID.',
        'msg-update-delay'         => 'Change for attribute :key is cannot be executed directly and delayed.',
        'msg-update-approval'         => 'Change for attribute :key is waiting for owner approval.',
        'msg-enabled-selected'  => 'Enabled multiple agent.',
        'msg-disabled-selected' => 'Disabled multiple agent.',
        'msg-summarypage' => 'Accessed Agent summary page',
    ],

    'status'              => [
        'created'                   => 'Agent successfully created',
        'updated'                   => 'Agent successfully updated',
        'deleted'                   => 'Agent successfully deleted',
        'global-enabled'            => 'Selected agents enabled.',
        'global-disabled'           => 'Selected agents disabled.',
        'enabled'                   => 'Agent enabled.',
        'disabled'                  => 'Agent disabled.',
        'no-agent-selected'          => 'No agent selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.',
        'image-invalid'         => 'Image file is invalid. Please check the file.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Agents | Agents list',
            'description'       => 'Lists of Agents',
            'table-title'       => 'Lists of Agents',
        ],
        'show'              => [
            'title'             => 'Agents | Agent details',
            'description'       => 'Agent details (:name)',
            'section-title'     => 'Agent details',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
        'create'              => [
            'title'             => 'Agents | New Agent',
            'description'       => 'Create new Agent',
            'section-title'     => 'Create new Agent',
            'general-info'      => 'Basic Info',
            'idcard-info'       => 'ID Card Info',
            'agent-info'        => 'Agent Detailed Info',
        ],
        'edit'              => [
            'title'             => 'Agents | Edit Agent',
            'description'       => 'Edit existing Agent (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit Agent',
            'pic-enlarge'       => 'Click the image to enlarge'
        ],
        'summary'              => [
            'title'             => 'Agents | Summary and Hierarchical View',
            'description'       => 'View all agents in summary view. Filter applied to PARENT agent only!',
            'hierarchy-title'   => 'Hierarchical Table',
            'exportdetail'      => 'Filter parent agents to show all child agents.',
        ],
        'history'              => [
            'title'             => 'Agents | Agents Positions History',
            'description'       => 'Lists of Agent Position History',
            'table-title'       => 'Lists of Agent Position History',
        ],
    ],

    'columns'             => [
        'name'	=>	'Nama Kantor Cabang'
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info',
        'id_card' => 'ID Card',
        'agent_detail' => 'Agent Details',
        'sales' => 'Agent Sales',
        'child' => 'Child Agent',
    ],

    'button'              => [
        'create'               => 'New Agent',
    ],

];
