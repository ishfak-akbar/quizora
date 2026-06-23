@extends('layouts.teacher')

@section('title', 'Quizora — Settings')

@push('styles')
<style>
    .settings-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 24px;
        align-items: start;
    }

    .settings-nav {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
        position: sticky;
        top: 84px;
    }

    .settings-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 18px;
        font-size: 13px;
        font-weight: 500;
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        border-left: 3px solid transparent;
        text-decoration: none;
    }

    .settings-nav-item:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .settings-nav-item.active {
        background: rgba(79, 70, 229, 0.12);
        color: var(--color-primary-glow);
        border-left-color: var(--color-primary-solid);
        font-weight: 600;
    }

    .settings-nav-item i {
        font-size: 18px;
    }

    .settings-nav-divider {
        height: 1px;
        background: var(--color-border-light);
    }

    .settings-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .settings-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .settings-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--color-border-light);
    }

    .settings-card-header h2 {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .settings-card-header p {
        font-size: 13px;
        color: var(--color-text-muted);
    }

    .settings-card-body {
        padding: 24px;
    }

    .field {
        margin-bottom: 18px;
    }

    .field label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .field-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 14px;
        font-family: var(--font);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field-input:focus {
        border-color: rgba(79, 70, 229, 0.6);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .field-error {
        color: #F87171;
        font-size: 12px;
        margin-top: 5px;
        padding-left: 4px;
    }

    .save-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 6px;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--color-primary-solid);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    .saved-msg {
        font-size: 13px;
        color: var(--color-status-success);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .danger-card {
        border-color: rgba(248, 113, 113, 0.2);
    }

    .danger-card .settings-card-header h2 {
        color: var(--color-status-error);
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(248, 113, 113, 0.1);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: var(--color-status-error);
        font-size: 14px;
        font-weight: 600;
        padding: 10px 22px;
        border-radius: 10px;
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: rgba(248, 113, 113, 0.2);
    }

    .avatar-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--color-border-light);
    }

    .avatar-large {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--color-primary-solid), var(--color-stat-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        margin-top: 6px;
    }

    .role-teacher {
        background: rgba(79, 70, 229, 0.15);
        color: var(--color-primary-glow);
    }

    .role-student {
        background: rgba(34, 211, 238, 0.12);
        color: var(--color-stat-cyan);
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="settings-layout">

        <!-- LEFT NAV -->
        <div class="settings-nav">
            <a href="#profile" class="settings-nav-item active" onclick="switchTab('profile', this)">
                <i class="ti ti-user"></i> Profile Info
            </a>
            <div class="settings-nav-divider"></div>
            <a href="#password" class="settings-nav-item" onclick="switchTab('password', this)">
                <i class="ti ti-lock"></i> Change Password
            </a>
            <div class="settings-nav-divider"></div>
            <a href="#danger" class="settings-nav-item" onclick="switchTab('danger', this)">
                <i class="ti ti-trash"></i> Delete Account
            </a>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="settings-content">

            <!-- PROFILE INFO -->
            <div class="settings-card" id="tab-profile">
                <div class="settings-card-header">
                    <h2>Profile Information</h2>
                    <p>Update your name and email address</p>
                </div>
                <div class="settings-card-body">
                    <div class="avatar-section">
                        <div class="avatar-large">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <h3>{{ auth()->user()->name }}</h3>
                            <p>{{ auth()->user()->email }}</p>
                            <div class="role-pill role-{{ auth()->user()->role }}">
                                <i class="ti ti-{{ auth()->user()->role === 'teacher' ? 'chalkboard' : 'school' }}"></i>
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="field">
                            <label>Full Name</label>
                            <input type="text" name="name" class="field-input"
                                value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label>Email Address</label>
                            <input type="email" name="email" class="field-input"
                                value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="save-row">
                            <button type="submit" class="btn-save">
                                <i class="ti ti-check"></i> Save Changes
                            </button>
                            @if (session('status') === 'profile-updated')
                            <span class="saved-msg"><i class="ti ti-circle-check"></i> Profile updated successfully</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="settings-card" id="tab-password" style="display:none;">
                <div class="settings-card-header">
                    <h2>Change Password</h2>
                    <p>Use a strong password to keep your account secure</p>
                </div>
                <div class="settings-card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="field">
                            <label>Current Password</label>
                            <input type="password" name="current_password" class="field-input">
                            @error('current_password')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label>New Password</label>
                            <input type="password" name="password" class="field-input">
                            @error('password')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="field-input">
                        </div>

                        <div class="save-row">
                            <button type="submit" class="btn-save">
                                <i class="ti ti-lock"></i> Update Password
                            </button>
                            @if (session('status') === 'password-updated')
                            <span class="saved-msg"><i class="ti ti-circle-check"></i> Password updated successfully</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- DANGER ZONE -->
            <div class="settings-card danger-card" id="tab-danger" style="display:none;">
                <div class="settings-card-header">
                    <h2>Delete Account</h2>
                    <p>This action is permanent and cannot be undone.</p>
                </div>
                <div class="settings-card-body">
                    <p style="color:var(--color-text-secondary); margin-bottom:20px;">
                        Deleting your account will remove all your quizzes, results, and data permanently.
                    </p>
                    <button onclick="document.getElementById('deleteModal').classList.add('open')"
                        class="btn-danger">
                        <i class="ti ti-trash"></i> Delete My Account
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab, el) {
        document.querySelectorAll('.settings-content .settings-card').forEach(card => {
            card.style.display = card.id === 'tab-' + tab ? 'block' : 'none';
        });

        document.querySelectorAll('.settings-nav-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>
@endpush