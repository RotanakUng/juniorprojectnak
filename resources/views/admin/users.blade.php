@extends('layouts.app')

@section('title', 'Transcent Profumo · Admin Panel')

@section('content')
    <div class="dashboard-wrapper">
        <div class="brand-panel">
            <div class="brand-row">
                @include('partials.user-menu')
                <div class="brand-chip">
                    <img src="{{ asset('431219605_1100922654446504_1462438396502192723_n.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="brand-title">
                    <h1>Transcent Profumo</h1>
                    <p>Admin Panel · User Management</p>
                </div>
            </div>
            <div class="action-group">
                <a href="{{ route('orders.index') }}" class="btn btn-ghost">← Back to Orders</a>
            </div>
        </div>

        <div class="page-card dashboard-card">
            <div class="panel-row toolbar-row">
                <div class="section-header" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                    <div>
                        <h2>User Accounts</h2>
                        <p style="color:#64748b; margin-top:6px;">Manage staff and admin accounts</p>
                    </div>
                    <div style="display:flex; gap:12px; align-items:center;">
                        <span class="orders-count">{{ $users->count() }} {{ Str::plural('user', $users->count()) }}</span>
                        <button type="button" class="btn btn-primary" onclick="openUserModal()">Add User</button>
                    </div>
                </div>
            </div>

            @if($users->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">👤</div>
                    <p>No users found</p>
                    <p class="text-slate">Create user accounts for your staff.</p>
                    <button type="button" class="btn btn-primary" onclick="openUserModal()">Add First User</button>
                </div>
            @else
                <div class="table-wrapper" style="overflow: visible;">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Name</th>
                                <th style="text-align:left;">Username</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td style="text-align:left; font-weight:600;">{{ $user->name }}</td>
                                    <td style="text-align:left; color:#64748b;">{{ $user->username }}</td>
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="role-badge" style="color:#1e40af; background:#eff6ff; border:1px solid #bfdbfe;">Admin</span>
                                        @else
                                            <span class="role-badge" style="color:#166534; background:#f0fdf4; border:1px solid #bbf7d0;">Staff</span>
                                        @endif
                                    </td>
                                    <td style="color:#64748b; font-size:0.9rem;">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="row-actions" style="justify-content:center;">
                                            <button type="button" class="btn btn-secondary btn-small" data-user='@json($user, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)' onclick="openEditModal(JSON.parse(this.getAttribute('data-user')))">Edit</button>
                                            @if($user->id !== auth()->id())
                                                <form id="deleteForm-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-small" onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')">Delete</button>
                                                </form>
                                            @else
                                                <span style="font-size:0.8rem; color:#94a3b8; font-weight:600;">(You)</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Create/Edit User Modal --}}
    <div id="userModalBackdrop" class="modal-backdrop" style="display:none;" onclick="closeUserModal()"></div>
    <div id="userModalContainer" class="modal-container" style="display:none;">
        <div class="modal-card" onclick="event.stopPropagation()">
            <div class="modal-heading">
                <h2 id="userModalTitle">Add New User</h2>
            </div>

            <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div id="userFormMethod"></div>

                <div class="form-grid" style="grid-template-columns: 1fr;">
                    <div class="form-group">
                        <label for="user_name">Full Name</label>
                        <input type="text" id="user_name" name="name" required autocomplete="off" placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group">
                        <label for="user_username">Username</label>
                        <input type="text" id="user_username" name="username" required autocomplete="off" placeholder="e.g. john">
                    </div>
                    <div class="form-group">
                        <label for="user_password" style="white-space: nowrap;">Password <small style="font-weight:500; color:#94a3b8;">(min 8 chars)</small> <span id="passwordHint" style="display:none; font-size:0.8rem; color:#94a3b8;">(leave blank)</span></label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" id="user_password" name="password" autocomplete="new-password" placeholder="Min 8 chars" style="padding-right: 40px; width: 100%;">
                            <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 4px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: #64748b; border: none; background: transparent; cursor: pointer; padding: 0;">
                                <svg id="eye-open" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_role">Role</label>
                        <select id="user_role" name="role" required>
                            <option value="worker">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="userSubmitBtn">Create User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div id="deleteModalBackdrop" class="modal-backdrop" style="display:none;" onclick="closeDeleteModal()"></div>
    <div id="deleteModalContainer" class="modal-container" style="display:none;">
        <div class="modal-card" style="max-width: 400px; text-align: center;" onclick="event.stopPropagation()">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 8px;">Delete User</h2>
            <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 24px;">
                Are you sure you want to delete <strong id="deleteModalUserName" style="color:#111827;"></strong>?<br>This action cannot be undone.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="flex: 1;">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()" style="flex: 1;">Yes, Delete</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openUserModal() {
        document.getElementById('userModalTitle').textContent = 'Add New User';
        document.getElementById('userSubmitBtn').textContent = 'Create User';
        document.getElementById('userForm').action = '{{ route("admin.users.store") }}';
        document.getElementById('userFormMethod').innerHTML = '';
        document.getElementById('user_name').value = '';
        document.getElementById('user_username').value = '';
        document.getElementById('user_password').value = '';
        document.getElementById('user_password').required = true;
        document.getElementById('user_role').value = 'worker';
        document.getElementById('passwordHint').style.display = 'none';

        document.getElementById('userModalBackdrop').style.display = '';
        document.getElementById('userModalContainer').style.display = '';
        document.body.classList.add('modal-open');
    }

    function openEditModal(user) {
        document.getElementById('userModalTitle').textContent = 'Edit User';
        document.getElementById('userSubmitBtn').textContent = 'Save Changes';
        document.getElementById('userForm').action = '/admin/users/' + user.id;
        document.getElementById('userFormMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('user_name').value = user.name;
        document.getElementById('user_username').value = user.username;
        document.getElementById('user_password').value = '';
        document.getElementById('user_password').required = false;
        document.getElementById('user_role').value = user.role;
        document.getElementById('passwordHint').style.display = 'inline';

        document.getElementById('userModalBackdrop').style.display = '';
        document.getElementById('userModalContainer').style.display = '';
        document.body.classList.add('modal-open');
    }

    function closeUserModal() {
        document.getElementById('userModalBackdrop').style.display = 'none';
        document.getElementById('userModalContainer').style.display = 'none';
        document.body.classList.remove('modal-open');
        
        // Reset password visibility
        const pwdInput = document.getElementById('user_password');
        pwdInput.type = 'password';
        document.getElementById('eye-open').style.display = 'none';
        document.getElementById('eye-closed').style.display = 'block';
    }

    function togglePasswordVisibility() {
        const input = document.getElementById('user_password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        } else {
            input.type = 'password';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        }
    }

    let currentDeleteId = null;

    function openDeleteModal(userId, userName) {
        currentDeleteId = userId;
        document.getElementById('deleteModalUserName').textContent = userName;
        document.getElementById('deleteModalBackdrop').style.display = '';
        document.getElementById('deleteModalContainer').style.display = '';
        document.body.classList.add('modal-open');
    }

    function closeDeleteModal() {
        currentDeleteId = null;
        document.getElementById('deleteModalBackdrop').style.display = 'none';
        document.getElementById('deleteModalContainer').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function confirmDelete() {
        if (currentDeleteId) {
            document.getElementById('deleteForm-' + currentDeleteId).submit();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUserModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
