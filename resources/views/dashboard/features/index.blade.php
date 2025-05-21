@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الميزات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الميزات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الميزات --}}
        <div class="row mb-4" id="features-statistics">
            {{-- إجمالي الميزات --}}
            <x-stat-card icon="fas fa-star" title="إجمالي الميزات" value="0" color="primary" id="total-features" />
            {{-- ميزات السكن --}}
            <x-stat-card icon="fas fa-home" title="ميزات السكن" value="0" color="success" id="residency-features" />
            {{-- ميزات القاعات --}}
            <x-stat-card icon="fas fa-building" title="ميزات القاعات" value="0" color="info" id="hall-features" />

        </div>

        {{-- بطاقة قائمة الميزات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الميزات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createFeatureModal">
                    <i class="fas fa-plus"></i> إضافة ميزة
                </button>
            </div>
            <div class="card-body">
                {{-- تبويب أنواع الميزات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedType === 'all' ? 'active' : '' }}"
                            href="{{ route('features.index') }}">الكل</a>
                    </li>
                    @foreach ($applicableTypes as $type => $typeName)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedType === $type ? 'active' : '' }}"
                                href="{{ route('features.index', ['type' => $type]) }}">
                                {{ $typeName }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('features.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث باسم الميزة..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الميزات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الميزة</th>
                                <th>النوع</th>
                                <th>تاريخ الإضافة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($features as $feature)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $feature->name }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $feature->applicable_to === 'residency' ? 'success' : 'info' }}">
                                            {{ $applicableTypes[$feature->applicable_to] }}
                                        </span>
                                    </td>
                                    <td>{{ $feature->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editFeatureModal{{ $feature->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteFeatureModal{{ $feature->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل ميزة --}}
                                        @include('dashboard.features.modals.edit', ['feature' => $feature])
                                        @include('dashboard.features.modals.delete', [
                                            'feature' => $feature,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد ميزات مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $features->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة ميزة (ثابت) --}}
    @include('dashboard.features.modals.create')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // جلب الإحصائيات عند تحميل الصفحة
            fetchStatistics();

            // تحديث الإحصائيات كل 30 ثانية
            setInterval(fetchStatistics, 30000);

            function fetchStatistics() {
                $.get('{{ route('features.statistics') }}', function(data) {
                    $('#total-features .stats-value').text(data.totalFeatures);
                    $('#residency-features .stats-value').text(data.residencyFeatures);
                    $('#hall-features .stats-value').text(data.hallFeatures);

                    // تأثيرات عند تحديث الأرقام
                    $('.stats-card').addClass('animate__animated animate__pulse');
                    setTimeout(function() {
                        $('.stats-card').removeClass('animate__animated animate__pulse');
                    }, 1000);
                });
            }
        });
    </script>
@endpush
