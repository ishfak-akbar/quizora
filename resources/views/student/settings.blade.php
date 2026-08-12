@extends('layouts.student')
@section('title', 'Quizora — Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your account and application preferences.')

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

    .fields-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 20px;
    }

    .field-full {
        grid-column: 1 / -1;
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

    .field-input,
    .field-select,
    .field-textarea {
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

    .field-select {
        appearance: none;
        cursor: pointer;
    }

    .field-select option {
        background: #1e1a3e;
        color: #fff;
    }

    .field-textarea {
        resize: vertical;
        min-height: 90px;
    }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: rgba(79, 70, 229, 0.6);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .field-error {
        color: #F87171;
        font-size: 12px;
        margin-top: 5px;
    }

    .field-hint {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 5px;
    }

    .save-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 8px;
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
        width: 64px;
        height: 64px;
        border-radius: 14px;
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
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
        background: rgba(34, 211, 238, 0.12);
        color: var(--color-stat-cyan);
    }

    .color-picker-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .color-swatch {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.15s, border-color 0.15s;
        flex-shrink: 0;
    }

    .color-swatch:hover {
        transform: scale(1.15);
    }

    .color-swatch.selected {
        border-color: #fff;
        transform: scale(1.15);
    }

    .target-wrap {
        position: relative;
    }

    .target-wrap .field-input {
        padding-right: 36px;
    }

    .target-suffix {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        font-weight: 600;
        color: var(--color-text-muted);
        pointer-events: none;
    }

    /* Delete modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal-box {
        background: var(--color-bg-card);
        border: 1px solid rgba(248, 113, 113, 0.3);
        border-radius: 16px;
        padding: 28px;
        width: 100%;
        max-width: 400px;
    }

    .modal-box h3 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }

    .modal-box p {
        font-size: 13px;
        color: var(--color-text-muted);
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-cancel {
        padding: 9px 18px;
        border-radius: 9px;
        border: 1px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: var(--font);
    }
</style>
@endpush

@section('content')
@php
$activeTab = 'profile';
if ($errors->has('institution') || $errors->has('class_level') || $errors->has('education_level') || $errors->has('study_goal') || $errors->has('preparing_for')) {
$activeTab = 'academic';
} elseif ($errors->has('preferred_language') || $errors->has('target_score')) {
$activeTab = 'preferences';
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
        <a href="#" class="settings-nav-item {{ $activeTab === 'academic' ? 'active' : '' }}" onclick="switchTab('academic', this); return false;">
            <i class="ti ti-school"></i> Academic Info
        </a>
        <div class="settings-nav-divider"></div>
        <a href="#" class="settings-nav-item {{ $activeTab === 'preferences' ? 'active' : '' }}" onclick="switchTab('preferences', this); return false;">
            <i class="ti ti-adjustments"></i> Preferences
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
                <p>Your basic info and how others see you</p>
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
                            <i class="ti ti-school"></i> Student
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.settings.update') }}" enctype="multipart/form-data">
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
                                placeholder="e.g. Dhaka, Chittagong"
                                value="{{ old('location', $user->location) }}">
                            @error('location') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label>Bio</label>
                            <textarea name="bio" class="field-textarea"
                                placeholder="Tell us a little about yourself..." maxlength="300">{{ old('bio', $user->bio) }}</textarea>
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
                        @if(session('success') && !session()->has('_password_updated'))
                        <span style="font-size:13px;color:var(--color-status-success);display:flex;align-items:center;gap:6px;">
                            <i class="ti ti-circle-check"></i> {{ session('success') }}
                        </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- ACADEMIC INFO --}}
        <div class="settings-card" id="tab-academic" style="{{ $activeTab === 'academic' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Academic Information</h2>
                <p>Your educational background and learning goals</p>
            </div>
            <div class="settings-card-body">
                <form method="POST" action="{{ route('student.settings.update') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="academic">

                    <div class="fields-grid">
                        <div class="field field-full">
                            <label>Institution</label>
                            <input type="text" name="institution" class="field-input"
                                placeholder="e.g. Dhaka College, BUET, University of Dhaka"
                                value="{{ old('institution', $user->institution) }}">
                            @error('institution') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Class / Year Level</label>
                            <input type="text" name="class_level" class="field-input"
                                placeholder="e.g. Class 10, 1st Year, 3rd Semester"
                                value="{{ old('class_level', $user->class_level) }}">
                            @error('class_level') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Education Level</label>
                            <select name="education_level" class="field-select">
                                <option value="">— Select —</option>
                                @foreach(['ssc' => 'SSC', 'hsc' => 'HSC', 'bachelor' => "Bachelor's", 'master' => "Master's", 'other' => 'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('education_level', $user->education_level) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('education_level') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Study Goal</label>
                            <select name="study_goal" class="field-select">
                                <option value="">— Select —</option>
                                @foreach(['exam_prep' => 'Exam Preparation', 'self_learning' => 'Self Learning', 'bcs' => 'BCS Preparation', 'university_admission' => 'University Admission', 'other' => 'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ old('study_goal', $user->study_goal) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('study_goal') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label>Preparing For</label>
                            <input type="text" name="preparing_for" class="field-input"
                                placeholder="e.g. BUET Admission 2026, BCS 47th, HSC 2026"
                                value="{{ old('preparing_for', $user->preparing_for) }}">
                            @error('preparing_for') <p class="field-error">{{ $message }}</p> @enderror
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

        {{-- PREFERENCES --}}
        <div class="settings-card" id="tab-preferences" style="{{ $activeTab === 'preferences' ? '' : 'display:none;' }}">
            <div class="settings-card-header">
                <h2>Preferences</h2>
                <p>Customize your learning experience</p>
            </div>
            <div class="settings-card-body">
                <form method="POST" action="{{ route('student.settings.update') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="preferences">

                    <div class="fields-grid">
                        <div class="field">
                            <label>Preferred Language</label>
                            <select name="preferred_language" class="field-select">
                                <option value="english" {{ old('preferred_language', $user->preferred_language) === 'english' ? 'selected' : '' }}>English</option>
                                <option value="bangla" {{ old('preferred_language', $user->preferred_language) === 'bangla' ? 'selected' : '' }}>বাংলা</option>
                            </select>
                            @error('preferred_language') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label>Target Score</label>
                            <div class="target-wrap">
                                <input type="number" name="target_score" class="field-input"
                                    placeholder="e.g. 80" min="1" max="100"
                                    value="{{ old('target_score', $user->target_score) }}">
                                <span class="target-suffix">%</span>
                            </div>
                            <p class="field-hint">Your personal score goal per quiz</p>
                            @error('target_score') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="save-row" style="margin-top:20px;">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-check"></i> Save Preferences
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
                <form method="POST" action="{{ route('student.settings.password') }}">
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
                    Deleting your account will permanently remove all your quiz attempts, results, bookmarks, and personal data. This cannot be recovered.
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
        <p>This will permanently delete all your data including quiz history, bookmarks, and results. Enter your password to confirm.</p>
        <form method="POST" action="{{ route('student.settings.delete') }}">
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