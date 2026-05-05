<div id="taskModal" class="hidden modal fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 transform transition-all">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Task Details</h3>
        <form id="taskForm" method="post" enctype="multipart/form-data" action="{{ route('tasks.store') }}"> @csrf
            <input type="hidden" name="id" id="task_id">
            <div class="space-y-4">
                <div class="add_result"></div>
                <div class="relative group">
                    <select id="project-filter" name="project"
                        class="appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 pl-10 pr-8 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all cursor-pointer shadow-sm">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute left-3 top-3 text-slate-400">
                        <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Task Name</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all" placeholder="e.g. Finish ERP Module">
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors">Save Task</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-slate-100 text-slate-600 py-2 rounded-lg font-bold hover:bg-slate-200">Cancel</button>
            </div>
        </form>
    </div>
</div>
