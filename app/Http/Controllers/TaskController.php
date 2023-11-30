<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $task = Task::create($validatedData);
        return response()->json($task, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'task_name' => 'required_without:description|string|max:255',
            'description' => 'nullable|required_without:name|string'
        ]);
        $task = Task::findOrFail($id);
        $task->update($validatedData);

        return response()->json(['task' => $task, 'message' => "$task->task_name created successfully"], 200);
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
