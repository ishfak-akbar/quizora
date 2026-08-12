@extends('layouts.teacher')
@section('title', 'Quizora — Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your account and application preferences.')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
@endpush

@section('content')
@php
$activeTab = 'profile';
if ($errors->has('institution') || $errors->has('designation')) {
$activeTab = 'professional';
} elseif ($errors->has('current_password') || $errors->has('password')) {
$activeTab = 'password';
} elseif (request()->query('tab')) {
$activeTab = request()->query('tab');
}
@endphp

<div class="settings-layout">

    {{-- LEFT NAV --}}
    <div class="settings-nav">
        <a href="#" class="settings-nav-item {{ $activeTab === 'profile' ? 'active' : '' }}" onclick="switchTab('profile', this); return false;">
            <i class="ti ti-user"></i> Profile Info
        </a>
        <div class="settings-nav-divider"></div>
        <a href="#" class="settings-nav-item {{ $activeTab === 'professional' ? 'active' : '' }}" onclick="switchTab('professional', this); return false;">
            <i class="ti ti-building-bank"></i> Professional Info
        </a>
        <div class="settings-nav-divider"></div>
        <a href="#" class="settings-nav-item {{ $activeTab === 'password' ? 'active' : '' }}" onclick="switchTab('password', this); return false;">
            <i class="ti ti-lock"></i> Change Password
        </a>
        <div class="settings-nav-divider"></div>
        <a href="#" class="settings-nav-item {{ $activeTab === 'danger' ? 'active' : '' }}" onclick="switchTab('danger', this); return false;">
            <i class="ti ti-trash"></i> Delete Account
        </a>
    </div>

    {{-- RIGHT CONTENT --}}
    <div class="settings-content">

        {{-- PROFILE INFO --}}
        <div class="settings-card" id="tab-profile" style="{{ $activeTab === 'profile' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Profile Information</h2>
                <p>Your basic info and how students see you</p>
            </div>
            <div class="settings-card-body">

                <div class="avatar-section">
                    <div class="avatar-large" id="avatarPreview"
                        style="overflow:hidden; background: linear-gradient(135deg,#4F46E5,#A78BFA);">
                        @if($user->hasAvatar())
                        <img src="{{ $user->avatarUrl() }}" alt="Avatar"
                            style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <h3 style="font-size:16px;font-weight:700;color:#fff;">{{ $user->name }}</h3>
                        <p style="font-size:13px;color:var(--color-text-muted);margin-top:2px;">{{ $user->email }}</p>
                        <div class="role-pill">
                            <i class="ti ti-chalkboard"></i> Teacher
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('teacher.settings.update') }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="profile">

                    <div class="fields-grid">
                        <div class="field">
                            <label>Full Name</label>
                            <input type="text" name="name" class="field-input"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Email Address</label>
                            <input type="email" name="email" class="field-input"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="field-input"
                                placeholder="+880 1xxx xxxxxx"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="field-input"
                                value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Gender</label>
                            <select name="gender" class="field-select">
                                <option value="">— Select —</option>
                                @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say'] as $val => $label)
                                <option value="{{ $val }}" {{ old('gender', $user->gender) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('gender') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Location</label>
                            <input type="text" name="location" class="field-input"
                                placeholder="e.g. Dhaka, Sylhet"
                                value="{{ old('location', $user->location) }}">
                            @error('location') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label>Bio</label>
                            <textarea name="bio" class="field-textarea"
                                placeholder="A short line about yourself, shown to students on your quizzes..." maxlength="300">{{ old('bio', $user->bio) }}</textarea>
                            <p class="field-hint">Max 300 characters</p>
                            @error('bio') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label>Profile Picture</label>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="field-input">
                            <p class="field-hint">JPG, PNG or WebP · Max 20MB</p>
                            @error('avatar') <p class="field-error">{{ $message }}</p> @enderror
                            @if($user->hasAvatar())
                            <label style="display:flex;align-items:center;gap:6px;margin-top:10px;font-size:12px;color:#F87171;cursor:pointer;">
                                <input type="checkbox" name="remove_avatar" value="1">
                                Remove current photo
                            </label>
                            @endif
                        </div>
                    </div>

                    <div class="save-row" style="margin-top:20px;">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-check"></i> Save Changes
                        </button>
                        @if(session('success'))
                        <span style="font-size:13px;color:var(--color-status-success);display:flex;align-items:center;gap:6px;">
                            <i class="ti ti-circle-check"></i> {{ session('success') }}
                        </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- PROFESSIONAL INFO --}}
        <div class="settings-card" id="tab-professional" style="{{ $activeTab === 'professional' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Professional Information</h2>
                <p>Your institution and title, shown to students on every quiz you create</p>
            </div>
            <div class="settings-card-body">
                <form method="POST" action="{{ route('teacher.settings.update') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="professional">

                    <div class="fields-grid">
                        <div class="field field-full">
                            <label>Institution</label>
                            <input type="text" name="institution" class="field-input"
                                placeholder="e.g. Metropolitan University, Sylhet"
                                value="{{ old('institution', $user->institution) }}">
                            @error('institution') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label>Designation</label>
                            <input type="text" name="designation" class="field-input"
                                placeholder="e.g. Senior Lecturer, Dept. of Computer Science"
                                value="{{ old('designation', $user->designation) }}">
                            <p class="field-hint">Displayed alongside your name wherever your quizzes appear</p>
                            @error('designation') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="save-row" style="margin-top:20px;">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-check"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CHANGE PASSWORD --}}
        <div class="settings-card" id="tab-password" style="{{ $activeTab === 'password' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Change Password</h2>
                <p>Use a strong password to keep your account secure</p>
            </div>
            <div class="settings-card-body">
                <form method="POST" action="{{ route('teacher.settings.password') }}">
                    @csrf @method('PUT')

                    <div class="fields-grid">
                        <div class="field field-full">
                            <label>Current Password</label>
                            <input type="password" name="current_password" class="field-input">
                            @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label>New Password</label>
                            <input type="password" name="password" class="field-input">
                            @error('password') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="field-input">
                        </div>
                    </div>

                    <div class="save-row" style="margin-top:20px;">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-lock"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DANGER ZONE --}}
        <div class="settings-card danger-card" id="tab-danger" style="{{ $activeTab === 'danger' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Delete Account</h2>
                <p>This action is permanent and cannot be undone.</p>
            </div>
            <div class="settings-card-body">
                <p style="color:var(--color-text-secondary);margin-bottom:20px;line-height:1.7;">
                    Deleting your account will permanently remove all your quizzes, questions, student submissions tied to them, and your Question Bank. This cannot be recovered.
                </p>
                <button onclick="document.getElementById('deleteModal').classList.add('open')" class="btn-danger">
                    <i class="ti ti-trash"></i> Delete My Account
                </button>
            </div>
        </div>

    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Delete your account?</h3>
        <p>This will permanently delete all your quizzes, student results tied to them, and your Question Bank. Enter your password to confirm.</p>
        <form method="POST" action="{{ route('teacher.settings.delete') }}">
            @csrf @method('DELETE')
            <div class="field" style="margin-bottom:16px;">
                <label>Your Password</label>
                <input type="password" name="password" class="field-input" placeholder="Enter your password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel"
                    onclick="document.getElementById('deleteModal').classList.remove('open')">
                    Cancel
                </button>
                <button type="submit" class="btn-danger">
                    <i class="ti ti-trash"></i> Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab, el) {
        document.querySelectorAll('.settings-content .settings-card').forEach(card => {
            card.style.display = card.id === 'tab-' + tab ? 'block' : 'none';
        });
        document.querySelectorAll('.settings-nav-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>
@endpush