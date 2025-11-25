@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h1 class="page-title">
                <i class="fas fa-edit"></i> Edit To-Do List
            </h1>
            <p class="text-muted">Update your list name</p>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('lists.update', $list->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i> List Name
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg" 
                               value="{{ old('name', $list->name) }}"
                               required
                               autofocus>

                        @error('name')
                            <small class="text-danger d-block mt-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update List
                        </button>
                        <a href="{{ route('lists.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- List Statistics -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar"></i> List Statistics
                </h5>
                <div class="row text-center">
                    <div class="col-4">
                        <h3 class="text-primary">{{ $list->tasks->count() }}</h3>
                        <small class="text-muted">Total Tasks</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-success">{{ $list->tasks->where('is_done', true)->count() }}</h3>
                        <small class="text-muted">Completed</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-warning">{{ $list->tasks->where('is_done', false)->count() }}</h3>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection