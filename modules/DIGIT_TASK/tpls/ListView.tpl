{*{debug}*}
{include file='include/ListView/ListViewColumnsFilterDialog.tpl'}
<script type='text/javascript' src='{sugar_getjspath file='include/javascript/popup_helper.js'}'></script>


<script>
    {literal}
    $(document).ready(function () {
        $("ul.clickMenu").each(function (index, node) {
            $(node).sugarActionMenu();
        });

        $('.selectActionsDisabled').children().each(function (index) {
            $(this).attr('onclick', '').unbind('click');
        });

        var selectedTopValue = $("#selectCountTop").attr("value");
        if (typeof(selectedTopValue) != "undefined" && selectedTopValue != "0") {
            sugarListView.prototype.toggleSelected();
        }
    });
    {/literal}
</script>
{assign var="currentModule" value = $pageData.bean.moduleDir}
{assign var="singularModule" value = $moduleListSingular.$currentModule}
{assign var="moduleName" value = $moduleList.$currentModule}
{assign var="hideTable" value=false}

{if count($data) == 0}
    {assign var="hideTable" value=true}
    <div class="list view listViewEmpty">
        {if $displayEmptyDataMesssages}
            {if strlen($query) == 0}
                {capture assign="createLink"}<a
                    href="?module={$pageData.bean.moduleDir}&action=EditView&return_module={$pageData.bean.moduleDir}&return_action=DetailView">{$APP.LBL_CREATE_BUTTON_LABEL}</a>{/capture}
                {capture assign="importLink"}<a
                    href="?module=Import&action=Step1&import_module={$pageData.bean.moduleDir}&return_module={$pageData.bean.moduleDir}&return_action=index">{$APP.LBL_IMPORT}</a>{/capture}
                {capture assign="helpLink"}<a target="_blank"
                                              href='?module=Administration&action=SupportPortal&view=documentation&version={$sugar_info.sugar_version}&edition={$sugar_info.sugar_flavor}&lang=&help_module={$currentModule}&help_action=&key='>{$APP.LBL_CLICK_HERE}</a>{/capture}
                <p class="msg">
                    {$APP.MSG_EMPTY_LIST_VIEW_NO_RESULTS|replace:"<item2>":$createLink|replace:"<item3>":$importLink}
                </p>
            {elseif $query == "-advanced_search"}
                <p class="msg emptyResults">
                    {$APP.MSG_LIST_VIEW_NO_RESULTS_CHANGE_CRITERIA}
                </p>
            {else}
                <p class="msg">
                    {capture assign="quotedQuery"}"{$query}"{/capture}
                    {$APP.MSG_LIST_VIEW_NO_RESULTS|replace:"<item1>":$quotedQuery}
                </p>
                <p class="submsg">
                    <a href="?module={$pageData.bean.moduleDir}&action=EditView&return_module={$pageData.bean.moduleDir}&return_action=DetailView">
                        {$APP.MSG_LIST_VIEW_NO_RESULTS_SUBMSG|replace:"<item1>":$quotedQuery|replace:"<item2>":$singularModule}
                    </a>
                </p>
            {/if}
        {else}
            <p class="msg">
                {$APP.LBL_NO_DATA}
            </p>
        {/if}
        {if $showFilterIcon}
            {include file='include/ListView/ListViewSearchLink.tpl'}
        {/if}
    </div>
{/if}
{$multiSelectData}
{if $hideTable == false}
    <table cellpadding='0' cellspacing='0' width='100%' border='0' class='list view table'>
        <thead>
        {assign var="link_select_id" value="selectLinkTop"}
        {assign var="link_action_id" value="actionLinkTop"}
        {assign var="actionsLink" value=$actionsLinkTop}
        {assign var="selectLink" value=$selectLinkTop}
        {assign var="action_menu_location" value="top"}
        {include file='include/ListView/ListViewPagination.tpl'}
        </thead>
        {counter start=$pageData.offsets.current print=false assign="offset" name="offset"}
        <tr>
            <td>
                {$timeLine}
            </td>
        </tr>
        {assign var="link_select_id" value="selectLinkBottom"}
        {assign var="link_action_id" value="actionLinkBottom"}
        {assign var="selectLink" value=$selectLinkBottom}
        {assign var="actionsLink" value=$actionsLinkBottom}
        {assign var="action_menu_location" value="bottom"}
        {include file='include/ListView/ListViewPagination.tpl'}
    </table>
{/if}
{if $contextMenus}
    <script type="text/javascript">
        {$contextMenuScript}
        {literal}
        function lvg_nav(m, id, act, offset, t) {
            if (t.href.search(/#/) < 0) {
                return;
            }
            else {
                if (act == 'pte') {
                    act = 'ProjectTemplatesEditView';
                }
                else if (act == 'd') {
                    act = 'DetailView';
                } else if (act == 'ReportsWizard') {
                    act = 'ReportsWizard';
                } else {
                    act = 'EditView';
                }
                {/literal}
                url = 'index.php?module=' + m + '&offset=' + offset + '&stamp={$pageData.stamp}&return_module=' + m + '&action=' + act + '&record=' + id;
                t.href = url;
                {literal}
            }
        }{/literal}
        {literal}
        function lvg_dtails(id) {{/literal}
            return SUGAR.util.getAdditionalDetails('{$pageData.bean.moduleDir|default:$params.module}', id, 'adspan_' + id);{literal}}{/literal}
    </script>
    <script type="text/javascript" src="include/InlineEditing/inlineEditing.js"></script>
{/if}
