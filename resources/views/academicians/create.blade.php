@extends('layouts.app')

@section('title', 'Add New Academician')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Academician</h1>
        <a href="{{ route('academicians.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('academicians.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <label for="name" class="col-sm-2 col-form-label">Name:</label>
                    <div class="col-sm-10">
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="staff_number" class="col-sm-2 col-form-label">Staff Number:</label>
                    <div class="col-sm-10">
                        <input type="text" 
                               class="form-control @error('staff_number') is-invalid @enderror" 
                               name="staff_number" 
                               value="{{ old('staff_number') }}" 
                               required>
                        @error('staff_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="college" class="col-sm-2 col-form-label">College:</label>
                    <div class="col-sm-10">
                        <input type="text" 
                               class="form-control @error('college') is-invalid @enderror" 
                               name="college" 
                               value="{{ old('college') }}" 
                               required>
                        @error('college')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="department" class="col-sm-2 col-form-label">Department:</label>
                    <div class="col-sm-10">
                        <input type="text" 
                               class="form-control @error('department') is-invalid @enderror" 
                               name="department" 
                               value="{{ old('department') }}" 
                               required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="position" class="col-sm-2 col-form-label">Position:</label>
                    <div class="col-sm-10">
                        <select name="position" 
                                class="form-select @error('position') is-invalid @enderror" 
                                required>
                            <option value="">Select Position</option>
                            <option value="Professor" {{ old('position') == 'Professor' ? 'selected' : '' }}>
                                Professor
                            </option>
                            <option value="Associate Professor" {{ old('position') == 'Associate Professor' ? 'selected' : '' }}>
                                Associate Professor
                            </option>
                            <option value="Senior Lecturer" {{ old('position') == 'Senior Lecturer' ? 'selected' : '' }}>
                                Senior Lecturer
                            </option>
                            <option value="Lecturer" {{ old('position') == 'Lecturer' ? 'selected' : '' }}>
                                Lecturer
                            </option>
                        </select>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Create Academician</button>
                        <a href="{{ route('academicians.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection







