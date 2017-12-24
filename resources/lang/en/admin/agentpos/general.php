<?php

return [

    'audit-log'           => [
        'category'          => 'Agent Positions master',
        'msg-index'         => 'Access agent positions master.',
        'msg-show'          => 'Accessed details of agent position master.',
        'msg-create'        => 'Accessed new agent position page.',
        'msg-store'         => 'Created new agent position with name :name.',
        'msg-edit'         => 'Accessed edit agent position page with name :name.',
        'msg-update'         => 'Edited agent position with name :name.',
        'msg-enabled-selected'  => 'Enabled multiple agent position.',
        'msg-disabled-selected' => 'Disabled multiple agent position.',
    ],

    'status'              => [
        'created'                   => 'Agent Position successfully created',
        'updated'                   => 'Agent Position successfully updated',
        'deleted'                   => 'Agent Position successfully deleted',
        'global-enabled'            => 'Selected agent positions enabled.',
        'global-disabled'           => 'Selected agent positions disabled.',
        'enabled'                   => 'Agent Position enabled.',
        'disabled'                  => 'Agent Position disabled.',
        'no-agent-position-selected'          => 'No agent position selected.',
    ],

    'error'               => [
        'no-replay-available'   => 'No replay action available.',
        'no-data-viewer'        => 'No data viewer defined.',
        'no-data'               => 'No data to show.'
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Agent Positions | Agent Positions list',
            'description'       => 'Lists of agent positions',
            'table-title'       => 'Lists of agent positions',
            'hierarchy'         => 'Hierarchy Visualizer',
        ],
        'show'              => [
            'title'             => 'Agent Positions | Agent Position details',
            'description'       => 'Agent Position details',
            'section-title'     => 'Agent Position details'
        ],
        'create'              => [
            'title'             => 'Agent Positions | New agent position',
            'description'       => 'Create new agent position',
            'section-title'     => 'Create new agent position'
        ],
        'edit'              => [
            'title'             => 'Agent Positions | Edit agent position',
            'description'       => 'Edit existing agent position (:name). Beware! After saving, any update is not reversible.',
            'section-title'     => 'Edit agent position'
        ],
    ],

    'columns'             => [
        'parent_id'   =>  'Parent Position',
        'name'   =>  'Name',
        'acronym'   =>  'Acronym',
        'level'   =>  'Level',
    ],

    'tabs' => [
        'basic' => 'Basic Info',
        'details' => 'Detailed Info'
    ],

    'button'              => [
        'create'               => 'New agent position',
    ],

];
