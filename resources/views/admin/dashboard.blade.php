@extends('layouts.admin')

@section('title', 'Quizora — Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . ' 👋')

@section('content')
<div style="padding: 40px; text-align: center; color: var(--admin-text-muted);">
    <i class="ti ti-layout-dashboard" style="font-size: 48px; color: var(--admin-primary); margin-bottom: 16px;"></i>
    <h2 style="color: #fff; margin-bottom: 8px;">New Admin Layout Ready</h2>
    <p>Emerald Control Center theme is active. We will build the full dashboard next.</p>
</div>
@endsection