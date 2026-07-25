@extends('layouts.admin')
@section('title', 'Edit Role')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h-4 mb-0">Edit Role: {{ ucfirst($role->name) }}</h1>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                       value="{{ old('name', $role->name) }}" placeholder="e.g., content_manager" required>
                <small class="text-muted">Use lowercase letters and underscores only</small>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <h5 class="mb-3">Permissions</h5>
            <p class="text-muted mb-3">
                Select the permissions for this role. Changing permissions takes effect immediately for all users with this role.
            </p>

            @php
                $groupedPermissions = $permissions;
            @endphp

            @foreach($groupedPermissions as $group => $perms)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $group }}</h6>
                        <div class="form-check">
                            <input class="form-check-input select-all" type="checkbox" data-group="{{ $group }}">
                            <label class="form-check-label">Select All</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($perms as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox" type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}"
                                               id="perm_{{ $permission->id }}"
                                               {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Select All" for each permission group
    document.querySelectorAll('.select-all').forEach(function(selectAll) {
        selectAll.addEventListener('change', function() {
            const card = this.closest('.card');
            const checkboxes = card.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
        });
    });

    // Update "Select All" state when individual checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.card');
            const selectAll = card.querySelector('.select-all');
            const checkboxes = card.querySelectorAll('.permission-checkbox');
            const checkedCount = card.querySelectorAll('.permission-checkbox:checked').length;
            selectAll.checked = checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });

        // Initialize
        const card = checkbox.closest('.card');
        const checkedCount = card.querySelectorAll('.permission-checkbox:checked').length;
        const totalCount = card.querySelectorAll('.permission-checkbox').length;
        if (checkedCount === totalCount) {
            card.querySelector('.select-all').checked = true;
        }
    });
});
</script>
@endsection
