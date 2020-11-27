{assign var=arr_items value={{sugarvar key='value' string=true}}}
{foreach from=$arr_items key=key item=row }
        <div class="item"> {$row.value} </div>
{/foreach}