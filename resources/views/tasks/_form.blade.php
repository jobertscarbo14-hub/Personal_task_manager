<form class="task-form" action="{{ $task->exists ? route('tasks.update', $task) : route('tasks.store') }}" method="POST">
    @csrf
    @if ($task->exists)
        @method('PUT')
    @endif

    <div class="form-field">
        <label for="task_name">Task name <span class="required-mark">*</span></label>
        <input id="task_name" name="task_name" type="text" maxlength="255" value="{{ old('task_name', $task->task_name) }}" placeholder="For example, finish project proposal" required>
        @error('task_name') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5" maxlength="2000" placeholder="Add a few details (optional)">{{ old('description', $task->description) }}</textarea>
        @error('description') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Pending" {{ old('status', $task->status ?? 'Pending') === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Completed" {{ old('status', $task->status ?? 'Pending') === 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div class="form-field">
            <label for="due_date">Due date</label>
            <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
            @error('due_date') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="form-actions">
        <a class="button button-quiet" href="{{ route('tasks.index') }}">Cancel</a>
        <button class="button button-primary" type="submit">{{ $task->exists ? 'Save changes' : 'Add task' }}</button>
    </div>
</form>