<?php

return [

    'audit-log'           => [
        'category'          => 'Agents master',
        'msg-index'         => 'Access Agents master.',
        'msg-show'          => 'Accessed details of Agent master.',
        'msg-create'        => 'Accessed new Agent page.',
        'msg-store'         => 'Created new Agent with NIK #:NIK.',
        'msg-edit'         => 'Accessed edit Agent page with ID #:ID.',
        'msg-update'         => 'Edited Agent with ID #:ID.',
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
        'NIK'                   =>  'NIK',
        'name'                  =>  'Name',
        'birth_place'           =>  'Birth Place',
        'DOB'                   =>  'Date of Birth',
        'gender'                =>  'Gender',
        'address'               =>  'Address',
        'state'                 =>  'State',
        'city'                  =>  'City',
        'religion'              =>  'Religion',
        'marriage_status'       =>  'Marriage Status',
        'occupation'            =>  'Occupation',
        'nationality'           =>  'Nationality',
        'id_card_expiry_date'   =>  'ID Card Expiry Date',
        'id_card_image_filename' => 'ID Card Image Filename',
        'id_card_image'         =>  'ID Card Image',
        'is_active'             =>  'Enabled',
        'created_at'            =>  'Created',
        'updated_at'            =>  'Last Updated',
        'saved_image'            => 'Saved ID Card Image',
        'agent_position_id'     =>  'Agent Position',
        'dist_channel'          =>  'Dist. Channel',
        'team_name'             =>  'Team Name',
        'NPWP'                  =>  'NPWP',
        'bank'                  =>  'Bank',
        'bank_branch'           =>  'Bank Branch',
        'account_number'        =>  'Account Number',
        'parent_id'             =>  'Parent',
        'agent_code'                  =>  'Agent Code',
        'join_date'             =>  'Join Date',
        'account_name'          =>  'Nama Pemilik Rekening'
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
