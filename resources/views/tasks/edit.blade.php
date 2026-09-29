@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div class="form-page-heading">
        <a class="back-link" href="{{ route('tasks.index') }}">Back to tasks</a>
        <p class="eyebrow">MAKE AN UPDATE</p>
        <h1>Edit task</h1>
        <p class="heading-copy">Change the details whenever your plan changes.</p>
    </div>

    @include('tasks._form')
@endsection