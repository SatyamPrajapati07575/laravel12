@extends('layouts.dashboard')

@section('title', 'Bulk User Uploads')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <a href="{{ route('admin.bulk.uploads') }}"><i class="fas fa-file-upload"></i> Bulk Uploads</a>
    <a href="#"><i class="fas fa-users"></i> Manage Users</a>
    <a href="#"><i class="fas fa-box"></i> Manage Products</a>
    <a href="#"><i class="fas fa-cogs"></i> Settings</a>
@endsection

@section('page_title', 'Bulk User Uploads')

@section('content')
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Upload CSV File</h5>
                    <a href="{{ route('admin.bulk.uploads.sample') }}" class="btn btn-success mb-3">
                        <i class="fas fa-download"></i> Download Sample CSV
                    </a>
                    <form action="{{ route('admin.bulk.uploads.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="csv_file" class="form-control mb-3" required>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Upload History</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Success</th>
                        <th>Failed</th>
                        <th>Uploaded At</th>
                        <th>Error Log</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uploads as $upload)
                        <tr>
                            <td>{{ $upload->id }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $upload->status == 'completed' ? 'success' : ($upload->status == 'processing' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($upload->status) }}
                                </span>
                            </td>
                            <td>{{ $upload->success_count }}</td>
                            <td>{{ $upload->fail_count }}</td>
                            <td>{{ $upload->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @if ($upload->error_log)
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="collapse"
                                        data-bs-target="#error-{{ $upload->id }}">
                                        View Errors
                                    </button>
                                    <div id="error-{{ $upload->id }}" class="collapse mt-2">
                                        <pre>{{ $upload->error_log }}</pre>
                                    </div>
                                @else
                                    <span class="text-muted">No Errors</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $uploads->links() }}
            </div>
        </div>
    </div>
@endsection
