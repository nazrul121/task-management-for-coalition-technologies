@extends('layouts.app')

@section('title', 'Manage Tasks | Flow')

@section('content')

<div class="max-w-8xl mx-auto py-12 px-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="w-full md:w-auto">
            <h1 class="text-3xl font-bold tracking-tight text-slate-800">Task Management</h1>
            <p class="text-slate-500 mt-1">Organize your workflow with drag-and-drop precision.</p>
        </div>

        <!-- Actions Section: Filter & Button -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <!-- Project Filter -->
            <div class="relative min-w-[200px]">
                <select id="project-filter" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 pr-10 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all appearance-none text-sm font-medium text-slate-700 shadow-sm cursor-pointer">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Create Button -->
            <button onclick="openModal()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-200 font-medium whitespace-nowrap">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create Task
            </button>
        </div>
    </div>


    <!-- DataTable Section -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden border border-slate-200">
        <div class="p-6">
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden border border-slate-200">
                <div class="p-6">
                    <table class="w-full text-left border-collapse" id="task-table">
                        <thead>
                            <tr class="text-slate-400 text-sm uppercase tracking-tighter border-b border-slate-100">
                                <!-- 1. Add this empty header for the drag handle -->
                                <th class="pb-4 font-semibold px-2 w-10"></th>

                                <!-- 2. Existing headers -->
                                <th class="pb-4 font-semibold px-2">#</th>
                                <th class="pb-4 font-semibold">Project</th>
                                <th class="pb-4 font-semibold">Task Name</th>
                                <th class="pb-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable" class="divide-y divide-slate-50 group-row"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('tasks.modals')

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Lucide Icons
            lucide.createIcons();

            // SINGLE INITIALIZATION
            let table = $('#task-table').DataTable({
                // 'l' adds the length menu, 'f' is filter, 't' is table, 'i' is info, 'p' is pagination
                dom: '<"flex flex-wrap justify-between items-center mb-6 gap-4"lf>rt<"flex flex-wrap justify-between items-center mt-6"ip>',
                renderer: 'tailwindcss',
                processing: true, serverSide: true,
                ordering: false,

                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: '<"flex justify-between items-center p-4 border-b border-slate-100"lf>rt<"flex justify-between items-center p-4 bg-slate-50/50"ip>',

                language: {
                    processing: `
                        <div class="flex flex-col items-center justify-center">
                            <div class="loading-spinner"></div>
                            <span class="text-sm font-bold text-slate-600 tracking-tight">Updating Tasks...</span>
                        </div>
                    `,
                },
                ajax: {
                    url: "{{ route('tasks.index') }}",
                    data: function(d) {
                        d.project_id = $('#project-filter').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        render: function() {
                            return '<i class="bi bi-grip-vertical dragDrop cursor-move text-slate-400"></i>';
                        },
                        orderable: false
                    }, // New Drag Handle Column
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'project_name', name: 'project.name' },
                    { data: 'task_display', name: 'name' },
                    { data: 'modify', name: 'modify', orderable: false, searchable: false, className: 'text-right' }
                ],

                preXhr: function() {
                    $('.dataTables_wrapper').addClass('processing');
                },
                createdRow: function (row, data) {
                    // This maps the Database ID to the physical row for the AJAX call
                    $(row).attr('data-id', data.id).addClass('sortable-row');
                },
                drawCallback: function() {
                    lucide.createIcons();

                    $("#sortable").sortable({
                        handle: ".dragDrop",
                        items: "tr",
                        cursor: "move",
                        placeholder: "ui-state-highlight",
                        // This helper prevents the row from collapsing while dragging
                        helper: function(e, ui) {
                            ui.children().each(function() {
                                $(this).width($(this).width());
                            });
                            return ui;
                        },
                        update: function(event, ui) {
                            var page_id_array = [];
                            $('#sortable tr').each(function() {
                                // 3. FIX: Your rows use 'data-id', not 'id'
                                var id = $(this).attr('data-id');
                                if (id) {
                                    page_id_array.push(id);
                                }
                            });

                            console.log("New Order IDs:", page_id_array);

                            $.ajax({
                                url: "{{ route('tasks.reorder') }}",
                                method: "GET",
                                data: { page_id_array: page_id_array },
                                success: function(data) {
                                    // Success logic
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error updating order:', error);
                                }
                            });
                        }
                    }).disableSelection();
                }
            });



            // Use table ID #task-table for event delegation
            $('#task-table').on('click','.edit', function(){
                let id = $(this).data('id'); openModal();
                $.ajax({
                    url: url + "/task/show/" + id,
                    type: 'get',   dataType: 'json',
                    success: function (data) {
                        $('#task_id').val(data.id);
                        $('#name').val(data.name);
                        $('#priority').val(data.priority);
                        setTimeout(function() {
                            $("[name=project] option").each(function(){
                                if ($(this).val() == data.project_id)  $(this).attr("selected",true);
                            });
                        }, 300);
                    }
                });
            });

            // Trigger table reload when project changes
            $('#project-filter').on('change', function() {
                table.draw();
            });

            $("#taskForm").submit(function(event) {
                event.preventDefault();
                $(this).closest('.modal').scrollTop(0);

                $("[type='submit']").html(' Loading...');$('.add_result').html('');
                $("[type='submit']").prop('disabled',true);
                var form = $(this);var url = form.attr('action');
                var html = '';
                $.ajax({
                    url:url, method:"post", data: new FormData(this),
                    contentType: false,cache:false, processData: false,
                    dataType:"json",
                    success:function(data){
                        console.log(data)
                        if(data.errors) {
                            html = '<div class="flex items-center p-4 mb-6 text-red-800 rounded-2xl bg-red-50 border border-red-100 shadow-sm animate-in fade-in slide-in-from-top-2" role="alert"><strong class="text-danger">Warning!</strong> ';
                            for(var count = 0; count < data.errors.length; count++)
                            { html +=  data.errors[count]; break;}
                            html += '</div>';
                        }
                        if(data.success){
                            html = `
                            <div class="flex items-center p-4 mb-6 text-blue-800 rounded-2xl bg-blue-50 border border-blue-100 shadow-sm animate-in fade-in slide-in-from-top-2">
                                <i class="bi bi-info-circle-fill mr-3"></i>
                                <div class="text-sm font-semibold">${data.success}</div>
                            </div>`;
                            table.ajax.reload();
                            $('.add_result').html(html);
                            setTimeout(() => { closeModal(); }, 1000);
                        }
                        $("[type='submit']").text('Save Data');
                        $("[type='submit']").prop('disabled',false);
                        $('.add_result').html(html);
                    }
                });
            });

            // Drag and Drop Logic


            // Delete Logic
            $('#task-table').on('click', '.delete' ,function(){
                if(confirm('Are you sure?')){
                    let id = $(this).data('id');
                    $.ajax({
                        url: url + "/task/delete/" + id, type: "DELETE",  data: { _token: '{{ csrf_token() }}' },
                        success: function(data){ if(data.success) table.ajax.reload(); }
                    });
                }
            });

            // Fade out table when AJAX starts
            $('#task-table').on('preXhr.dt', function () {
                $(this).css('opacity', '0.2');
            });

            // Fade back in when data is loaded
            $('#task-table').on('xhr.dt', function () {
                $(this).css('opacity', '1');
            });
        });

        function openModal() { $('#taskModal').removeClass('hidden'); $('.add_result').html(''); }
        function closeModal() { $('#taskModal').addClass('hidden'); $('.add_result').html(''); $('#taskForm')[0].reset(); }
    </script>
@endpush
