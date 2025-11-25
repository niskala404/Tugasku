@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i> Create New To-Do List
            </h1>
            <p class="text-muted">Organize your tasks by creating a new list</p>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('lists.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i> List Name
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg" 
                               value="{{ old('name') }}"
                               placeholder="e.g., Work Tasks, Shopping List, Personal Goals"
                               required
                               autofocus>

                        @error('name')
                            <small class="text-danger d-block mt-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create List
                        </button>
                        <a href="{{ route('lists.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="card mt-4" style="border-left: 4px solid var(--primary-color);">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-lightbulb text-warning"></i> Tips
                </h5>
                <ul class="mb-0">
                    <li>Give your list a descriptive name</li>
                    <li>You can add tasks after creating the list</li>
                    <li>Organize related tasks in the same list</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection