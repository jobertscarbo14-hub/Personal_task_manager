<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->validate([
            'status' => ['nullable', 'in:Pending,Completed'],
        ])['status'] ?? null;

        $tasks = Task::query()
            ->when($status, fn (Builder $query, string $selectedStatus): Builder => $query->where('status', $selectedStatus))
            ->latest()
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'selectedStatus' => $status,
            'totalTasks' => Task::count(),
            'pendingTasks' => Task::where('status', 'Pending')->count(),
            'completedTasks' => Task::where('status', 'Completed')->count(),
            'overdueTasks' => Task::where('status', 'Pending')->whereDate('due_date', '<', today())->count(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create', ['task' => new Task]);
    }

    public function store(Request $request): RedirectResponse
    {
        Task::create($request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:Pending,Completed'],
            'due_date' => ['nullable', 'date'],
        ]));

        return redirect()->route('tasks.index')->with('success', 'Task added successfully.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:Pending,Completed'],
            'due_date' => ['nullable', 'date'],
        ]));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $task->update($request->validate([
            'status' => ['required', 'in:Pending,Completed'],
        ]));

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
