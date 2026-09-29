@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
    <section class="page-heading">
        <div>
            <p class="eyebrow">YOUR SPACE, YOUR PACE</p>
            <h1>My Tasks</h1>
            <p class="heading-copy">A clear list for everything you want to get done.</p>
        </div>
        <a class="button button-primary heading-button" href="{{ route('tasks.create') }}">+ New Task</a>
    </section>

    <section class="summary-grid" aria-label="Task summary">
        <article class="summary-card summary-total">
            <span class="summary-label">All tasks</span>
            <strong>{{ $totalTasks }}</strong>
            <span class="summary-note">in your list</span>
        </article>
        <article class="summary-card summary-pending">
            <span class="summary-label">Pending</span>
            <strong>{{ $pendingTasks }}</strong>
            <span class="summary-note">still to do</span>
        </article>
        <article class="summary-card summary-completed">
            <span class="summary-label">Completed</span>
            <strong>{{ $completedTasks }}</strong>
            <span class="summary-note">nicely done</span>
        </article>
        <article class="summary-card summary-overdue">
            <span class="summary-label">Past due</span>
            <strong>{{ $overdueTasks }}</strong>
            <span class="summary-note">pending deadlines</span>
        </article>
    </section>

    <section class="task-section">
        <div class="section-heading">
            <div>
                <h2>Task list</h2>
                <p>Keep track of the next thing on your plate.</p>
            </div>
            <nav class="filter-tabs" aria-label="Filter tasks">
                <a class="filter-tab {{ $selectedStatus === null ? 'active' : '' }}" href="{{ route('tasks.index') }}">All</a>
                <a class="filter-tab {{ $selectedStatus === 'Pending' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'Pending']) }}">Pending</a>
                <a class="filter-tab {{ $selectedStatus === 'Completed' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'Completed']) }}">Completed</a>
            </nav>
        </div>

        @if ($tasks->isEmpty())
            <div class="empty-state">
                <span class="empty-icon" aria-hidden="true">+</span>
                <h3>{{ $selectedStatus ? 'No ' . strtolower($selectedStatus) . ' tasks yet' : 'Your list is clear' }}</h3>
                <p>{{ $selectedStatus ? 'Try another filter or add a new task.' : 'Add your first task and it will show up here.' }}</p>
                <a class="button button-outline" href="{{ route('tasks.create') }}">Create a task</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Due date</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="task-cell">
                                    <strong>{{ $task->task_name }}</strong>
                                    <span>{{ $task->description ?: 'No description added' }}</span>
                                </td>
                                <td>
                                    <form class="status-form" action="{{ route('tasks.status', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <label class="sr-only" for="status-{{ $task->id }}">Status for {{ $task->task_name }}</label>
                                        <select class="status-select status-{{ strtolower($task->status) }}" id="status-{{ $task->id }}" name="status" onchange="this.form.submit()">
                                            <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if ($task->due_date)
                                        <span class="due-date {{ $task->status === 'Pending' && $task->due_date->isBefore(today()) ? 'due-overdue' : '' }}">
                                            {{ $task->due_date->format('M j, Y') }}
                                        </span>
                                    @else
                                        <span class="muted">No date</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="icon-link" href="{{ route('tasks.edit', $task) }}" aria-label="Edit {{ $task->task_name }}" title="Edit task">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-link delete-link" type="submit" aria-label="Delete {{ $task->task_name }}" title="Delete task">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection