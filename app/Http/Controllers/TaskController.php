<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     // $tasks = Task::with(['dependencies' => function ($query) {
        //     $query->where('id', '!=', $this->parent_id);
        // }])->get();

        // $tasks = Task::with('dependencies')->get()->map(function ($task) {
        //     return $task->setRelations([
        //         'dependencies' => $task->dependencies->filter(function ($dependency) use ($task) {
        //             return $dependency->id !== $task->id;
        //         })
        //     ]);
        // });

        // $tasks = Task::with('dependencies')->get();

        // // Filter out each task's dependencies to exclude itself
        // $tasks->each(function ($task) {
        //     $task->dependencies = $task->dependencies->filter(function ($dependency) use ($task) {
        //         return $dependency->id !== $task->id;
        //     });
        // });

        // $tasks->transform(function ($task) {
        //     /** @var Task $task */
        //     $task->dependencies = $task->dependencies->filter(function ($dependency) use ($task) {
        //         return $dependency->id !== $task->id;
        //     });
        //     return $task;
        // });
    public function index()
    {
        $tasks = Task::with('dependencies')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'isCompleted' => 'required|boolean',
            'dependencies' => 'nullable|array'
        ]);

        $validatedData['id'] = Str::uuid();
        if ($request->has('dependencies')) {
            $task->dependencies()->attach($request->input('dependencies'));
        }
        $task = Task::create($validatedData);
        return response()->json(['task' => $task, 'message' => "$task->task_name created successfully"], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'isCompleted' => 'required|boolean',
            'dependencies' => 'nullable|array'
        ]);
        $task = Task::findOrFail($id);

        if ($validatedData['isCompleted'] === true) {
            $validatedData['completed_at'] = now();
        } else {
            $validatedData['completed_at'] = null;
        }

        $dependencies = $validatedData['dependencies'] ?? null;
        unset($validatedData['dependencies']);
        $task->update($validatedData);

        if ($dependencies !== null) {
            $task->dependencies()->sync($dependencies);
        }
        
        return response()->json(['task' => $task, 'message' => "$task->task_name updated successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => "Task $task->id deleted successfully"], 204);
    }
}
