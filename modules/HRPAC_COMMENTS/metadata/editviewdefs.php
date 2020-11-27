<?php

$module_name = 'HRPAC_COMMENTS';
$viewdefs[$module_name]['EditView'] = [
    'templateMeta' => [
        'form' => [
//          'buttons' => ['SAVE', 'CANCEL', 'SUBPANELSAVE', 'SUBPANELCANCEL', 'SUBPANELFULLFORM', ],
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
        'syncDetailEditViews' => true,
    ],

    'panels' => [
        'default' => [
            [   ['name' => 'text',              'label' => 'LBL_TEXT',              ],  ],

            //  определиние типа родителя
//          [
//              ['name' => 'parent_name',       'label' => 'LBL_LIST_RELATED_TO',   ],
//          ],

            //  стандартные поля
//          [
//              ['name' => 'author_id',         'label' => 'LBL_AUTHOR_ID',         ],
//          ],
//          [
//              ['name' => 'date_entered',      'label' => 'LBL_DATE_ENTERED',      ],
//              ['name' => 'created_by_name',   'label' => 'LBL_CREATED',           ],       //  создано, кем
//          ],
//          [
//              ['name' => 'date_modified',     'label' => 'LBL_DATE_MODIFIED',     ],
//              ['name' => 'modified_by_name',  'label' => 'LBL_MODIFIED_NAME',     ],       //  изменено, кем
//          ],
//          [    'name',                '', ],  //  наименование
//          [    'assigned_user_name',  '', ],  //  назначено
//          [    'description',             ],  //  описание
        ],
    ],
];

//                                       ----
