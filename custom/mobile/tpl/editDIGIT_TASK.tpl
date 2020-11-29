<div class="content-wrapper" id="content_edit">
    <iframe id="edit" src="{$baseUrl}/index.php?module=DIGIT_TASK&action=EditView&nonheader=1&record={$data_edit.record}" frameborder="0"></iframe>
</div>
<script>
    {literal}
    $(document).ready(function () {
        var w=$("#content_edit").width();
        $("#edit").css('width',w);
        $("#edit").css('height',2000);

        setTimeout(function () {
            $("#edit").on("load", function () {
                window.location = "{/literal}{$baseUrl}{literal}/entryPoint/mobile/"

            });
            $("#edit").on("load", function () {
                window.location = "{/literal}{$baseUrl}{literal}/entryPoint/mobile/"

            });
        },5000);


    });
    {/literal}
</script>