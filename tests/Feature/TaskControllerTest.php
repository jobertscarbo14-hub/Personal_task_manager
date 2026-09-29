<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_task_summary_and_can_filter_status(): void
    {
        $pendingTask = Task::factory()->create(['task_name' => 'Prepare project outline', 'status' => 'Pending']);
        $completedTask = Task::factory()->create(['task_name' => 'Read Laravel notes', 'status' => 'Completed']);

        $this->get(route('tasks.index'))
            ->assertSee('Prepare project outline')
            ->assertSee('Read Laravel notes')
            ->assertSee('<strong>2</strong>', false);

        $this->get(route('tasks.index', ['status' => 'Pending']))
            ->assertSee($pendingTask->task_name)
            ->assertDontSee($completedTask->task_name);

    }

    public function test_task_can_be_created_with_valid_details(): void
    {
        $this->post(route('tasks.store'), [
            'task_name' => 'Finish database assignment',
            'description' => 'Complete the task manager migration.',
            'status' => 'Pending',
            'due_date' => '2026-10-05',
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'task_name' => 'Finish database assignment',
            'status' => 'Pending',
        ]);

        $savedTask = Task::query()->where('task_name', 'Finish database assignment')->firstOrFail();

        $this->assertSame('2026-10-05', $savedTask->due_date->toDateString());
    }

    public function test_task_details_can_be_updated(): void
    {
        $task = Task::factory()->create(['task_name' => 'Old task name']);

        $this->put(route('tasks.update', $task), [
            'task_name' => 'Updated task name',
            'description' => 'Updated details',
            'status' => 'Completed',
            'due_date' => null,
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_name' => 'Updated task name',
            'description' => 'Updated details',
            'status' => 'Completed',
            'due_date' => null,
        ]);
    }

    public function test_task_can_be_deleted(): void
    {
        $task = Task::factory()->create();

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_status_can_be_updated_without_editing_other_fields(): void
    {
        $task = Task::factory()->create([
            'task_name' => 'Submit course work',
            'status' => 'Pending',
        ]);

        $this->patch(route('tasks.status', $task), ['status' => 'Completed'])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_name' => 'Submit course work',
            'status' => 'Completed',
        ]);
    }

    public function test_invalid_task_input_is_rejected(): void
    {
        $this->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'task_name' => '',
                'description' => str_repeat('a', 2001),
                'status' => 'Started',
                'due_date' => 'not-a-date',
            ])
            ->assertSessionHasErrors(['task_name', 'description', 'status', 'due_date']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_dashboard_escapes_task_content(): void
    {
        Task::factory()->create(['task_name' => '<script>alert("x")</script>']);

        $this->get(route('tasks.index'))
            ->assertSee('&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert("x")</script>', false);
    }
}
