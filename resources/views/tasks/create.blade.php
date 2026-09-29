@extends('layouts.app')

@section('title', 'Add Task')

@section('content')
    <div class="form-page-heading">
        <a class="back-link" href="{{ route('tasks.index') }}">Back to tasks</a>
        <p class="eyebrow">MAKE A LITTLE PLAN</p>
        <h1>Add a task</h1>
        <p class="heading-copy">Write down what needs to get done.</p>
    </div>

    @include('tasks._form')
@endsection