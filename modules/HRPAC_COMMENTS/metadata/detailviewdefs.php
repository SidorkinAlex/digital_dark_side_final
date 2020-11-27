<?php

$module_name = 'HRPAC_COMMENTS';
$viewdefs[$module_name]['DetailView'] = [
    'templateMeta' => [
        'form' => ['buttons' => ['EDIT', 'DUPLICATE', 'DELETE', ], ],
        'maxColumns' => '2',
        'widths' => [
            ['label' => '10', 'field' => '30', ],
            ['label' => '10', 'field' => '30', ],
        ],
        'useTabs' => false,
        'tabDefs' => ['DEFAULT' => ['newTab' => false, 'panelDefault' => 'expanded', ], ],
        'syncDetailEditViews' => true,
    ],

    'panels' => [
        'default' => [
            [   ['name' => 'text',                  'label' => 'LBL_TEXT',              ],  ],
            [   ['name' => 'assigned_user_name',    'label' => 'LBL_AUTHOR_ID',         ],  ],  //  ответственный
            [   ['name' => 'parent_type',           'label' => 'LBL_PARENT_TYPE',       ],  ],
            [   ['name' => 'parent_name',           'label' => 'LBL_LIST_RELATED_TO',   ],  ],
/*
            //  определиние типа родителя
            [
                ['name' => 'author_id',             'label' => 'LBL_AUTHOR_ID',         ],
            ],

            //  стандартные поля
            [
                ['name' => 'date_entered',          'label' => 'LBL_DATE_ENTERED',      ],
                ['name' => 'created_by_name',       'label' => 'LBL_CREATED',           ],      //  создано, кем
            ],
            [
                ['name' => 'date_modified',         'label' => 'LBL_DATE_MODIFIED',     ],
                ['name' => 'modified_by_name',      'label' => 'LBL_MODIFIED_NAME',     ],      //  изменено, кем
            ],
            [    'name',                '', ],      //  наименование
            [    'description',             ],      //  описание
*/
        ],
    ],
];

//                                                                                           ----
