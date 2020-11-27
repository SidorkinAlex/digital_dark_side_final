<?php

$module_name = 'HRPAC_COMMENTS';
$viewdefs[$module_name]['QuickCreate'] = [
    'templateMeta' => [
        'form' => [
            'buttons' => [
                'SUBPANELSAVE', 'SUBPANELCANCEL',
//              'SUBPANELFULLFORM', 'SAVE', 'CANCEL',
            ],
            'hidden' => [
                '<input type="hidden" name="parent_type"        id="parent_type"        value="{$fields.parent_type.value}" />',
                '<input type="hidden" name="parent_id"          id="parent_id"          value="{$fields.parent_id.value}" />',
                '<input type="hidden" name="assigned_user_id"   id="assigned_user_id"   value="{$fields.assigned_user_id.value}" />',
            ],
        ],
        'maxColumns' => '2',
        'widths' => [
            ['label' => '10', 'field' => '30', ],
            ['label' => '10', 'field' => '30', ],
        ],
        'useTabs' => false,
        'tabDefs' => [
            'DEFAULT' => ['newTab' => false, 'panelDefault' => 'expanded', ],
        ],
    ],

    'panels' => [
        'default' => [
            [['name' => 'to_recruits',  'label' => 'LBL_TO_RECRUITS',   ],  ],
            [['name' => 'text',         'label' => 'LBL_TEXT',          ],  ],
//          [ 'name', 'assigned_user_name', ],
        ],
    ],
];

//                                                               ----
