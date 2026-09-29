<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_homepage_redirects_to_the_task_dashboard(): void
    {
        $this->get('/')->assertRedirect(route('tasks.index'));
    }
}
