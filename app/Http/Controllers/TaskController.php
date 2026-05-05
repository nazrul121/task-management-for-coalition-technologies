<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{
    public function index(Request $request){
        // if ($request->ajax()) {
        if ($request->draw) {
            $query = Task::with('project');

            // Filter by Project if selected
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }

            $tasks = $query->orderBy('priority', 'asc');

            return datatables()::of($tasks)
            ->addIndexColumn()
            ->addColumn('project_name', function ($row) {
                return $row->project->name;
            })
            ->addColumn('task_display', function ($row) {
                return '<div class="font-semibold text-slate-700">'.$row->name.'</div>';
            })
            ->addColumn('modify', function ($row) {
                return '<div class="flex justify-end gap-2">
                            <button class="text-blue-600 edit" data-id="'.$row->id.'"><i class="bi bi-pencil"></i></button>
                            <button class="text-red-600 delete" data-id="'.$row->id.'"><i class="bi bi-trash"></i></button>
                        </div>';
            })
            // CRITICAL: You must list every column that contains HTML here
            ->rawColumns(['task_display', 'modify'])
            ->make(true);
        }

        $projects = Project::all();
        return view('tasks.index', compact('projects'));
    }

    public function store(Request $request){
        // dd($request->all());
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $task = Task::updateOrCreate(['id' => $request->id], [
            'project_id' => $request->project,
            'name' => $request->name,
        ]);
        return response()->json(['success' => 'Task saved successfully.']);
    }

    public function show(Task $task){
        return $task;
    }

    public function reorder(Request $request){
        // dd($request->all());
        for($i=0; $i<count($request->page_id_array); $i++){
            Task::where('id',$request->page_id_array[$i])->update([ 'priority'=>$i ]);
        }
        return response()->json(['status' => 'success']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'project' => 'required',
            'name' => 'required|string|max:255',
        ]); return $validator;
    }

    public function destroy($id)
    {
        Task::find($id)->delete();
        return response()->json(['success' => 'Task deleted.']);
    }
}
