{*{debug}*}
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>Вакансии</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Вакансии</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">
                <div class="row d-flex align-items-stretch">
                    {foreach from=$data item=row key=id}
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch" id="{$id}">
                        <div class="card bg-light">
                            <div class="card-header text-muted border-bottom-0">
                                {$row.project_link_id}
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="lead"><b>{$row.name}</b></h2>
                                        <p class="text-muted text-sm"><b>Ключевые навыки: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p>
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>{$row.location_id}</li>
                                        </ul>
                                    </div>
                                    <div class="col-5 text-center">
                                        <div>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar bg-primary"
                                                     style="width: {$row.progresbar}%"></div>
                                            </div>
                                            {$row.progresbar}
                                        </div>
                                        <div>
                                            <p class="text-muted text-sm"><b>Рекрутер: </b> {$row.assigned_user_name}</p>
                                            <p class="text-muted text-sm"><b>Директор БЮ: </b> {$row.supervisor_id}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                {*<div class="text-right">*}
                                    {*<a href="#" class="btn btn-sm bg-teal">*}
                                        {*<i class="fas fa-comments"></i>*}
                                    {*</a>*}
                                    {*<a href="#" class="btn btn-sm btn-primary">*}
                                        {*<i class="fas fa-user"></i> View Profile*}
                                    {*</a>*}
                                {*</div>*}
                            </div>
                        </div>
                    </div>
                    {/foreach}
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <nav aria-label="Contacts Page Navigation">
                    <ul class="pagination justify-content-center m-0">
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                        <li class="page-item"><a class="page-link" href="#">7</a></li>
                        <li class="page-item"><a class="page-link" href="#">8</a></li>
                    </ul>
                </nav>
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{*<div class="content-wrapper">*}
{*<!-- Main content -->*}
{*<section class="content">*}
    {*<div class="container-fluid">*}
        {*<div class="row">*}
            {*<div class="col-md-12">*}
                {*<div class="card">*}
                    {*<div class="card-header">*}
                        {*<h3 class="card-title">Bordered Table</h3>*}
                    {*</div>*}
                    {*<!-- /.card-header -->*}
                    {*<div class="card-body">*}
                        {*<table class="table table-bordered">*}
                            {*<thead>*}
                            {*<tr>*}
                                {*{foreach from=$config.metaData item=columName}*}
                                {*<th style="width: 10px">{$MOD[$columName.label]}</th>*}
                                {*{/foreach}*}
                                {*{foreach from=$sugartabs item=tab}*}
                                    {*<li id="{$tab.label}_sp_tab">*}
                                        {*<a class="{$tab.type}"*}
                                           {*href="javascript:SUGAR.subpanelUtils.loadSubpanelGroup('{$tab.label}');">{$tab.label}</a>*}
                                    {*</li>*}
                                {*{/foreach}*}
                            {*</tr>*}
                            {*</thead>*}
                            {*<tbody>*}
                            {*{foreach from=$data item=row}*}
                            {*<tr>*}
                                {*{foreach from=$config.metaData key=columName item=columArrData}*}
                                    {*{if $columName == 'progresbar' }*}
                                        {*<td>*}
                                            {*<div class="progress progress-xs progress-striped active">*}
                                                {*<div class="progress-bar bg-primary" style="width: {$row[$columName]}%"></div>*}
                                            {*</div>*}
                                            {*{$row[$columName]}*}
                                        {*</td>*}
                                    {*{else}*}
                                        {*<td>{$row[$columName]}</td>*}
                                    {*{/if}*}
                                {*{/foreach}*}
                            {*</tr>*}
                            {*{/foreach}*}
                            {*</tbody>*}
                        {*</table>*}
                    {*</div>*}
                    {*<!-- /.card-body -->*}
                    {*<div class="card-footer clearfix">*}
                        {*<ul class="pagination pagination-sm m-0 float-right">*}
                            {*<li class="page-item"><a class="page-link" href="#">&laquo;</a></li>*}
                            {*<li class="page-item"><a class="page-link" href="#">1</a></li>*}
                            {*<li class="page-item"><a class="page-link" href="#">2</a></li>*}
                            {*<li class="page-item"><a class="page-link" href="#">3</a></li>*}
                            {*<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>*}
                        {*</ul>*}
                    {*</div>*}
                {*</div>*}
                {*<!-- /.card -->*}

            {*</div>*}
            {*<!-- /.col -->*}
            {*<!-- /.col -->*}
        {*</div>*}
        {*<!-- /.row -->*}
    {*</div><!-- /.container-fluid -->*}
{*</section>*}
{*<!-- /.content -->*}
{*</div>*}