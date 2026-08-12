@extends('layouts.admin')
@section('title', 'Quizora — Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your admin account')

@push('styles')
<style>
    .settings-grid {
        display: grid;
        gap: 20px;
        max-width: 640px;
    }

    .settings-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .settings-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--color-border-light);
    }

    .settings-card-header h2 {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px;
    }

    .settings-card-header p {
        font-size: 13px;
        color: var(--color-text-muted);
        margin: 0;
    }

    .settings-card-body {
        padding: 22px;
    }

    .field {
        margin-bottom: 16px;
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
        outline: none;
    }

    .field-input:focus {
        border-color: rgba(16, 185, 129, 0.5);
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

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #10b981;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #059669;
    }

    .avatar-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .avatar-preview {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="settings-grid">

    {{-- PROFILE --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <h2>Profile</h2>
            <p>Name, email, and profile picture</p>
        </div>
        <div class="settings-card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')

                <div class="avatar-row">
                    <div class="avatar-preview">
                        @if($user->hasAvatar())
                        <img src="{{ $user->avatarUrl() }}" alt="">
                        @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:#fff;">{{ $user->name }}</div>
                        <div style="font-size:12px;color:var(--color-text-muted);">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" class="field-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" class="field-input" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label>Profile Picture</label>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="field-input">
                    <p class="field-hint">JPG, PNG or WebP · Max 20MB</p>
                    @error('avatar') <p class="field-error">{{ $message }}</p> @enderror
                    @if($user->hasAvatar())
                    <label style="display:flex;align-items:center;gap:6px;margin-top:8px;font-size:12px;color:#F87171;cursor:pointer;">
                        <input type="checkbox" name="remove_avatar" value="1"> Remove current photo
                    </label>
                    @endif
                </div>

                <button type="submit" class="btn-save"><i class="ti ti-check"></i> Save Profile</button>
            </form>
        </div>
    </div>

    {{-- PASSWORD --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <h2>Change Password</h2>
            <p>Use a strong password for your admin account</p>
        </div>
        <div class="settings-card-body">
            <form method="POST" action="{{ route('admin.settings.password') }}">
                @csrf @method('PUT')

                <div class="field">
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

                <button type="submit" class="btn-save"><i class="ti ti-lock"></i> Update Password</button>
            </form>
        </div>
    </div>

</div>
@endsection