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
        <div class="row mb-4">
            {{-- إجمالي الميزات --}}
            <x-stat-card icon="fas fa-star" title="إجمالي الميزات" :value="$totalFeatures" color="primary" />
            {{-- الميزات بدون تصنيف --}}
            <x-stat-card icon="fas fa-question-circle" title="بدون تصنيف" :value="$featuresWithoutCategory" color="warning" />
            {{-- عدد التصنيفات --}}
            <x-stat-card icon="fas fa-tags" title="عدد التصنيفات" :value="$categoriesCount" color="success" />
            {{-- الميزات المضافة هذا الشهر --}}
            <x-stat-card icon="fas fa-calendar" title="المضاف حديثاً" :value="$featuresThisMonth" color="info" />
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
                {{-- تبويب التصنيفات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('category_id') ? 'active' : '' }}"
                            href="{{ route('features.index') }}">الكل</a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ request('category_id') == $category->id ? 'active' : '' }}"
                                href="{{ route('features.index', ['category_id' => $category->id]) }}">
                                {{ $category->name }}
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
                                <th>التصنيف</th>
                                <th>تاريخ الإضافة</th>
                                <th>تاريخ التعديل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($features as $feature)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $feature->name }}</td>
                                    <td>
                                        @if ($feature->category)
                                            <span class="badge badge-primary">{{ $feature->category->name }}</span>
                                        @else
                                            <span class="badge badge-secondary">بدون تصنيف</span>
                                        @endif
                                    </td>
                                    <td>{{ $feature->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $feature->updated_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showFeatureModal{{ $feature->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

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
                                        @include('dashboard.features.modals.show', ['feature' => $feature])
                                        @include('dashboard.features.modals.edit', ['feature' => $feature])
                                        @include('dashboard.features.modals.delete', [
                                            'feature' => $feature,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا يوجد ميزات</td>
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
        // تفعيل التولتيب الافتراضي
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
