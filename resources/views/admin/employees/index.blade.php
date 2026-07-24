@extends('layouts.admin')
@section('title', 'Employees')
@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-1">Employees</h1>
</div>
<div class="admin-card">
    <div class="card-header">
        <h5 class="mb-0">Employee List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No data available</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
