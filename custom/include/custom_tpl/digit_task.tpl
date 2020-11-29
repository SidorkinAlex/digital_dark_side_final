<div class="card">
    <div class="card-header border-0">
        <h3 class="card-title">Мои задачи</h3>
        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-download"></i>
            </a>
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
            <thead>
            <tr>
                <th>Задача</th>
                <th>Приоритет</th>
                <th>Планируемая дата завершения</th>
                <th>More</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$digit_task_list key=row item=data_row}
            <tr>
                <td>
                    <a href="entryPoint/mobile/?module=DIGIT_TASK&action=DetailView&record={$data_row.id}">{$data_row.name}</a>
                </td>
                <td>{$data_row.priority}</td>
                <td>
                    {$data_row.date_plan}
                </td>
                <td>
                    <a href="#" class="text-muted">
                        <i class="fas fa-search"></i>
                    </a>
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>