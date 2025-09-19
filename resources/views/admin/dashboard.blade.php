@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <a href="{{ route('admin.bulk.uploads') }}"><i class="fas fa-file-upload"></i> Bulk Uploads</a>
    <a href="#"><i class="fas fa-users"></i> Manage Users</a>
    <a href="#"><i class="fas fa-box"></i> Manage Products</a>
    <a href="#"><i class="fas fa-cogs"></i> Settings</a>
@endsection

@section('page_title', 'Welcome Admin, ' . Auth::user()->name)

@section('content')
    <div class="row">

        <div class="col-md-12">
            {{-- <div class="mb-4">
                <form action="{{ route('admin.users.bulkUpload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" name="csv_file" class="form-control" required>
                        <button type="submit" class="btn btn-primary">Upload CSV</button>
                    </div>
                </form>
            </div> --}}


            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-users"></i> All Users</h4>

                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Country ID</th>
                                <th>City ID</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <td>{{ $user->country?->name }}</td>
                                    <td>{{ $user->city?->name }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div> --}}
                    <div class="d-flex justify-content-center mt-3">
                        {!! $users->links('pagination::bootstrap-5') !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
