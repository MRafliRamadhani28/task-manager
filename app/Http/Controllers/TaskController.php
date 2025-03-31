<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::with('employees')->get());
    }

    public function store(TaskRequest $request)
    {
        $data           = $request->validated();
        $data['date']   = now();

        $task = Task::create($data);

        foreach ($request->employees as $employee) {
            $task->employees()->attach($employee['id'], ['hours_spent' => $employee['hours_spent']]);
        }

        return new TaskResource($task->load('employees'));
    }

    public function show(Task $task)
    {
        return new TaskResource($task->load('employees'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        $task->employees()->detach();
        foreach ($request->employees as $employee) {
            $task->employees()->attach($employee['id'], ['hours_spent' => $employee['hours_spent']]);
        }

        return new TaskResource($task->load('employees'));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
