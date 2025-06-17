@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الحجوزات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الحجوزات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الحجوزات --}}
        <div class="row mb-4">
            {{-- إجمالي الحجوزات --}}
            <x-stat-card icon="fas fa-calendar-check" title="إجمالي الحجوزات" :value="$totalReservations" color="primary" />
            {{-- الحجوزات النشطة --}}
            <x-stat-card icon="fas fa-clock" title="حجوزات نشطة" :value="$activeReservations" color="success" />
            {{-- حجوزات يومية --}}
            <x-stat-card icon="fas fa-sun" title="حجوزات يومية" :value="$dailyReservations" color="info" />
            {{-- حجوزات ساعية --}}
            <x-stat-card icon="fas fa-hourglass-half" title="حجوزات ساعية" :value="$hourlyReservations" color="warning" />
        </div>

        {{-- بطاقة قائمة الحجوزات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الحجوزات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createReservationModal">
                    <i class="fas fa-plus"></i> حجز جديد
                </button>
            </div>
            <div class="card-body">
                {{-- تبويب أنواع الحجوزات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedType === 'all' ? 'active' : '' }}"
                            href="{{ route('reservations.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedType === 'daily' ? 'active' : '' }}"
                            href="{{ route('reservations.index', ['type' => 'daily']) }}">يومية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedType === 'hourly' ? 'active' : '' }}"
                            href="{{ route('reservations.index', ['type' => 'hourly']) }}">ساعية</a>
                    </li>
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('reservations.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث باسم العميل أو المنتج..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الحجوزات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>العميل</th>
                                <th>المنتج</th>
                                <th>النوع</th>
                                <th>تاريخ البدء</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة</th>
                                <th>السعر</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $reservation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $reservation->user->avatar ? asset('storage/' . $reservation->user->avatar) : asset('img/default-avatar.png') }}"
                                                alt="{{ $reservation->user->name }}" class="rounded-circle mr-2"
                                                width="40" height="40">
                                            {{ $reservation->user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $reservation->product->name }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $reservation->type === 'daily' ? 'info' : 'warning' }}">
                                            {{ $reservation->type === 'daily' ? 'يومية' : 'ساعية' }}
                                        </span>
                                    </td>
                                    <td>{{ $reservation->start_time->format('Y-m-d H:i') }}</td>
                                    <td>{{ $reservation->end_time->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'returned' => 'primary',
                                                'partial_refund' => 'warning',
                                            ];
                                            $statusNames = [
                                                'active' => 'نشط',
                                                'returned' => 'تم الإرجاع',
                                                'partial_refund' => 'إرجاع جزئي',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$reservation->status] }}">
                                            {{ $statusNames[$reservation->status] }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($reservation->total_price, 2) }}   د</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showReservationModal{{ $reservation->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editReservationModal{{ $reservation->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteReservationModal{{ $reservation->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل حجز --}}
                                        @include('dashboard.reservations.modals.show', [
                                            'reservation' => $reservation,
                                        ])
                                        @include('dashboard.reservations.modals.edit', [
                                            'reservation' => $reservation,
                                        ])
                                        @include('dashboard.reservations.modals.delete', [
                                            'reservation' => $reservation,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد حجوزات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة حجز (ثابت) --}}
    @include('dashboard.reservations.modals.create')
@endsection

@push('scripts')
    <script>
        // حساب السعر التلقائي بناء على النوع والمدة
        $(document).ready(function() {
            $('select[name="product_id"]').change(function() {
                updatePrice();
            });

            $('select[name="type"]').change(function() {
                updatePrice();
            });

            $('input[name="start_time"], input[name="end_time"]').change(function() {
                updatePrice();
            });

            function updatePrice() {
                const productId = $('select[name="product_id"]').val();
                const type = $('select[name="type"]').val();
                const startTime = $('input[name="start_time"]').val();
                const endTime = $('input[name="end_time"]').val();

                if (productId && type && startTime && endTime) {
                    // هنا يمكنك إضافة منطق حساب السعر بناء على المنتج والنوع والمدة
                    // هذا مثال بسيط فقط
                    const start = new Date(startTime);
                    const end = new Date(endTime);
                    const diffHours = (end - start) / (1000 * 60 * 60);
                    const diffDays = diffHours / 24;

                    let price = 0;
                    if (type === 'hourly') {
                        price = diffHours * 50; // افتراضي 50 ريال للساعة
                    } else {
                        price = Math.ceil(diffDays) * 200; // افتراضي 200 ريال لليوم
                    }

                    $('input[name="total_price"]').val(price.toFixed(2));
                }
            }
        });
    </script>
@endpush
