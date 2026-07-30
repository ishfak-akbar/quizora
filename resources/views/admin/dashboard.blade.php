@extends('layouts.admin')

@section('title', 'Quizora — Admin Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="card" style="padding: 40px; text-align: center;">
    <i class="ti ti-shield-check" style="font-size: 48px; color: var(--color-primary-glow); margin-bottom: 16px;"></i>
    <h2 style="font-size: 20px; margin-bottom: 8px;">Admin Panel is Ready</h2>
    <p style="color: var(--color-text-muted);">Placeholder.</p>
</div>
@endsection