@extends('layouts.app')
@section('page-title', 'User Management')

@section('content')
<div class="page-header">
	<div>
		<h4>User Management</h4>
		<p>Search and manage system users</p>
	</div>
</div>

@if(session('success'))
	<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
	<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="search-wrap">
	<form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
		<i class="bi bi-search"></i>
		<input type="text" name="search" placeholder="Search by ID, name, or email..." value="{{ $search ?? '' }}">
		@if(!empty($search))
			<a href="{{ route('admin.users.index') }}" class="search-clear">&times;</a>
		@endif
	</form>
	@if(!empty($search))
		<span style="font-size:12px;color:var(--text-muted);">
			Results for <strong style="color:var(--primary);">"{{ $search }}"</strong>
		</span>
	@endif
</div>

<div class="panel">
	<table class="clinic-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Email</th>
				<th>Role</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			@forelse($users as $user)
				<tr>
					<td>{{ $users->firstItem() + $loop->index }}</td>
					<td><strong>{{ $user->name }}</strong></td>
					<td>{{ $user->email }}</td>
					<td>
						<span class="badge badge-brand" style="text-transform:capitalize;">{{ $user->role }}</span>
					</td>
					<td class="text-center">
						<a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1" title="Edit Role">
							<i class="bi bi-pencil"></i>
						</a>
						@if(auth()->id() !== $user->id)
							<form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user? This action cannot be undone.')">
								@csrf
								@method('DELETE')
								<button class="btn btn-sm btn-danger" title="Delete User">
									<i class="bi bi-trash"></i>
								</button>
							</form>
						@endif
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="5" class="text-center text-muted py-4">
						@if(!empty($search))
							No users found for "{{ $search }}".
						@else
							No users found.
						@endif
					</td>
				</tr>
			@endforelse
		</tbody>
	</table>

	<div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid var(--border);">
		<span style="font-size:12px;color:var(--text-muted);">
			Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
		</span>
		{{ $users->links() }}
	</div>
</div>
@endsection
