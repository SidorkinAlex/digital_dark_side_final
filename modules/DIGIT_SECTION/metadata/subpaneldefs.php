<?php

$layout_defs['DIGIT_SECTION'] = array(
    // list of what Subpanels to show in the DetailView
    'subpanel_setup' => array(
        'digit_section_users' => array(
            'order' => 25,
            'module' => 'Users',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_DIGIT_SECTION_USERS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'digit_section_users',
            'top_buttons' =>
                array(
                    0 =>
                        array(
                            'widget_class' => 'SubPanelTopButtonQuickCreate',
                        ),
                    1 =>
                        array(
                            'widget_class' => 'SubPanelTopSelectButton',
                            'mode' => 'MultiSelect',
                        ),
                ),
        ),
    )
);