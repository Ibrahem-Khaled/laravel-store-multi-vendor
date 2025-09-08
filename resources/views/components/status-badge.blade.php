{{-- resources/views/components/status-badge.blade.php --}}
@php
    $map = [
        'pending' => 'warning',
        'paid' => 'primary',
        'shipped' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
@endphp
<span class="badge badge-{{ $map[$status] ?? 'secondary' }}">{{ $status }}</span>
