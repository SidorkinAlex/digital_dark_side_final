<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ion ion-clipboard mr-1"></i>
            Информационые сообщения на ознакомление.
        </h3>

        <div class="card-tools">
            {*<ul class="pagination pagination-sm">*}
                {*<li class="page-item"><a href="#" class="page-link">&laquo;</a></li>*}
                {*<li class="page-item"><a href="#" class="page-link">1</a></li>*}
                {*<li class="page-item"><a href="#" class="page-link">2</a></li>*}
                {*<li class="page-item"><a href="#" class="page-link">3</a></li>*}
                {*<li class="page-item"><a href="#" class="page-link">&raquo;</a></li>*}
            {*</ul>*}
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <ul class="todo-list" data-widget="todo-list">
            {foreach from=$data_info key=row item=data_row}
                <li>
                    <!-- drag handle -->
                    <span class="handle">
<i class="fas fa-ellipsis-v"></i>
<i class="fas fa-ellipsis-v"></i>
</span>
                    <!-- checkbox -->
                    <div class="icheck-primary d-inline ml-2">
                        <input type="checkbox" class="click_digit_assigned_user_id" name="todo1"
                               id="{$data_row.digit_assigned_user_id}" data-id="{$data_row.digit_assigned_user_id}">
                        <label for="todoCheck1"></label>
                    </div>
                    <!-- todo text -->
                    <span class="text">{$data_row.name}</span>
                    <!-- Emphasis label -->
                    {if $data_row.date_lost.value}
                        <small class="badge {$data_row.date_lost.class}"><i
                                    class="far fa-clock"></i> {$data_row.date_lost.value}</small>
                    {/if}
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <i class="fas fa-edit"></i>
                        <i class="fas fa-trash-o"></i>
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        {*<button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add item</button>*}
    </div>
</div>
<script>
    {literal}
        $(document).ready(function () {
            $('.click_digit_assigned_user_id').click(function () {
                if($(this).prop("checked")) {
                    var Idchek=$(this).attr('id');
                    var ireration = 0;
                    var loopdigit_assigned=setTimeout(function () {
                        if ($('#' + Idchek).prop("checked")) {
                            id = $('#' + Idchek).attr('data-id');
                            send_click_digit_assigned_user_id(id);
                            $('#' + Idchek).parent().parent().hide(300);
                            clearInterval(loopdigit_assigned);
                        } else {
                            clearInterval(loopdigit_assigned);
                        }
                    }, 500);
                }
            })
        });
        function send_click_digit_assigned_user_id(id) {
            console.log('send_click_digit_assigned_user_id');
            $.ajax({
                url: '/index.php?module=Home&action=saveHTMLField&current_module=DIGIT_ASSIGNED_USER&11=11&id=' + id + '&field=typical_responses&value=accepted&to_pdf=true',
                method: 'post',
                dataType: 'html',
                //data: {'typical_responses': 'accepted'},
                success: function(data){

                }
            });
        }
    {/literal}
</script>