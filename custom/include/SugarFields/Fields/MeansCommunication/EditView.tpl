
    <style>
        .el-menu {ldelim}
            user-select: none;
        {rdelim}
        .popover-content,
        .popover {ldelim}
            padding: 0 !important;
        {rdelim}
        .el-form-item__content {ldelim}
            display: inline-block;
        {rdelim}

        .el-form-item__content a {ldelim}
            width: 32px;
            float: right;
        {rdelim}

        .el-submenu .el-menu--popup {ldelim}
            display: none;
            position: absolute;
            top: 0;
            right: -200px;
        {rdelim}

        .el-submenu .el-menu--popup.is-opened {ldelim}
            display: block;
        {rdelim}
        .primary-input {ldelim}
            width: 60% !important;
        {rdelim}
    </style>
    {assign var=vardef_value value={{sugarvar key='value' string=true}}}
    {assign var=vardef_name value={{sugarvar key='name' string=true}}}
    {assign var=vardef_options_list value={{sugarvar key='options_list' string=true}}}
    <!--    {$vardef_name} = название поля -->
    <div id="{$vardef_name}"><a href=""></a>
        <button type="button" id="{$vardef_name}-button" class="el-button el-button--default el-button--mini is-circle" data-html="true" data-toggle="popover" data-content="
        <ul class=&quot;el-menu el-menu--popup el-menu--popup-bottom-start&quot;>
            <div class=&quot;el-menu-list&quot;>

                <!-- в цикле вывести элемент, если не содержит поле submenu -->
                {{foreach from=$vardef.options_list key=key item=value_option }}
                <el-menu-item-group>
                    <li class=&quot;el-menu-item&quot; onclick=&quot;add_row_Means_of_Communication('#{$vardef_name}','{{$key}}');&quot;> {{$value_option}} </li>
                </el-menu-item-group>
                {{/foreach}}
                <!-- конец -->

                <!-- вывести этот элемент если содержит submenu -->
                <el-menu-item-group>
                    <li class=&quot;el-submenu&quot; onclick=&quot;dropMenu(event)&quot;>
                        <div class=&quot;el-submenu__title&quot; >
                        Социальные сети
                            <i class=&quot;el-submenu__icon-arrow el-icon-arrow-right&quot;></i>
                            <ul class=&quot;el-menu el-menu--popup el-menu--popup-right-start&quot;>
                                <li class=&quot;el-menu-item&quot; onclick=&quot;add_row_Means_of_Communication('#{$vardef_name}');&quot;> Telegram </li>
                            </ul>
                        </div>
                    </li>
                </el-menu-item-group>
                <!-- конец -->

            </div>
        </ul>
" onclick="$(this).popover();">
            <i class="el-icon-plus"></i>
        </button>
        <div id="{$vardef_name}-rows">
            {foreach from=$vardef_value key=key item=row }
                    <div id="{$vardef_name}{$row.sort}" class="items">
                        <input type="hidden" name="{$vardef_name}[{$row.sort}][sort]" class="sort" value="{$row.sort}">
                        <input type="hidden" class="id" name="{$vardef_name}[{$row.sort}][id]" value="{$row.id}">
                        <input type="hidden" class="value_type" name="{$vardef_name}[{$row.sort}][value_type]" value="{$row.value_type}">
                        <div class="el-form-item__content">
                            <input type="text" class="primary-input" name="{$vardef_name}[{$row.sort}][value]" id="{$vardef_name}[{$row.sort}][{$row.sort}]" value="{$row.value}">
                            <button class="btn btn-danger" onclick="delRow('#{$vardef_name}{$row.sort}');return false;">
                                <i class="el-icon-close"></i>
                            </button>
                            <a href="skype:ertry?chat" draggable="false">
                                <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="skype" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-skype fa-w-14 fa-2x"><path fill="currentColor" d="M424.7 299.8c2.9-14 4.7-28.9 4.7-43.8 0-113.5-91.9-205.3-205.3-205.3-14.9 0-29.7 1.7-43.8 4.7C161.3 40.7 137.7 32 112 32 50.2 32 0 82.2 0 144c0 25.7 8.7 49.3 23.3 68.2-2.9 14-4.7 28.9-4.7 43.8 0 113.5 91.9 205.3 205.3 205.3 14.9 0 29.7-1.7 43.8-4.7 19 14.6 42.6 23.3 68.2 23.3 61.8 0 112-50.2 112-112 .1-25.6-8.6-49.2-23.2-68.1zm-194.6 91.5c-65.6 0-120.5-29.2-120.5-65 0-16 9-30.6 29.5-30.6 31.2 0 34.1 44.9 88.1 44.9 25.7 0 42.3-11.4 42.3-26.3 0-18.7-16-21.6-42-28-62.5-15.4-117.8-22-117.8-87.2 0-59.2 58.6-81.1 109.1-81.1 55.1 0 110.8 21.9 110.8 55.4 0 16.9-11.4 31.8-30.3 31.8-28.3 0-29.2-33.5-75-33.5-25.7 0-42 7-42 22.5 0 19.8 20.8 21.8 69.1 33 41.4 9.3 90.7 26.8 90.7 77.6 0 59.1-57.1 86.5-112 86.5z" class=""></path>
                                </svg>
                            </a>
                        </div>
                    </div>
            {/foreach}
        </div>
        <script>
            $('#{$vardef_name}-button').popover();
            function dropMenu(e) {ldelim}
                var className = e.target.className;
                $(`.${ldelim}className}>ul`).toggleClass('is-opened');
            {rdelim}
            function add_row_Means_of_Communication(element,type_el) {ldelim}
                var fieldid = $(element).attr('id');
                var count = $(element+'-rows').children('.items').length;
                var el = `<div id="${ldelim}fieldid + count}" class="items">
                <input type="hidden" name="{$vardef_name}[${ldelim}count}][sort]" class="sort" value="${ldelim}count}">
                <input type="hidden" class="id" name="{$vardef_name}[${ldelim}count}][id]">
                <input type="hidden" class="value_type" name="{$vardef_name}[${ldelim}count}][value_type]" value="${ldelim}type_el}">
                <div class="el-form-item__content">
                    <input type="text" class="primary-input" name="{$vardef_name}[${ldelim}count}][value]" id="{$vardef_name}[${ldelim}count}][value]" value="">
                    <button class="btn btn-danger" onclick="delRow(&quot;${ldelim}element + count}&quot;);return false;">
                        <i class="el-icon-close"></i>
                    </button>
                    <a href="skype:ertry?chat" draggable="false">
                        <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="skype" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-skype fa-w-14 fa-2x"><path fill="currentColor" d="M424.7 299.8c2.9-14 4.7-28.9 4.7-43.8 0-113.5-91.9-205.3-205.3-205.3-14.9 0-29.7 1.7-43.8 4.7C161.3 40.7 137.7 32 112 32 50.2 32 0 82.2 0 144c0 25.7 8.7 49.3 23.3 68.2-2.9 14-4.7 28.9-4.7 43.8 0 113.5 91.9 205.3 205.3 205.3 14.9 0 29.7-1.7 43.8-4.7 19 14.6 42.6 23.3 68.2 23.3 61.8 0 112-50.2 112-112 .1-25.6-8.6-49.2-23.2-68.1zm-194.6 91.5c-65.6 0-120.5-29.2-120.5-65 0-16 9-30.6 29.5-30.6 31.2 0 34.1 44.9 88.1 44.9 25.7 0 42.3-11.4 42.3-26.3 0-18.7-16-21.6-42-28-62.5-15.4-117.8-22-117.8-87.2 0-59.2 58.6-81.1 109.1-81.1 55.1 0 110.8 21.9 110.8 55.4 0 16.9-11.4 31.8-30.3 31.8-28.3 0-29.2-33.5-75-33.5-25.7 0-42 7-42 22.5 0 19.8 20.8 21.8 69.1 33 41.4 9.3 90.7 26.8 90.7 77.6 0 59.1-57.1 86.5-112 86.5z" class=""></path>
                        </svg>
                    </a>
                </div>
            </div>`;
                $(element+'-rows').append(el);
                $('#{$vardef_name}-button').popover('hide')
                console.log(fieldid);
            {rdelim}
            function delRow(el) {ldelim}
                console.log(el);
                $(el).remove();
                sort_el();
            {rdelim}
            function sort_el() {ldelim}
                var sortedItems = $('#{$vardef_name}-rows').sortable("toArray");
                $("#{$vardef_name}-rows .items").each(function () {ldelim}
                    var item = $("#{$vardef_name}-rows .items").index($(this));
                    $(this).children('.sort').val(item);
                {rdelim})
            {rdelim}
            $( function() {ldelim}
                $( "#{$vardef_name}-rows" ).sortable({ldelim}
                    axis: "y",
                    containment: "parent",
                    update: function() {ldelim}
                        sort_el();
                    {rdelim}
                {rdelim});
                //$( "#{$vardef_name}-rows" ).disableSelection();
            {rdelim} );

        </script>
    </div>