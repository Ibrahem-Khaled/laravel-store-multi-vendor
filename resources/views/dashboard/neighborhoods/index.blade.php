@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأحياء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأحياء</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأحياء --}}
        <div class="row mb-4">
            {{-- إجمالي الأحياء --}}
            <x-stat-card icon="fas fa-map-marker-alt" title="إجمالي الأحياء" :value="$neighborhoodsCount" color="primary" />
            {{-- الأحياء النشطة --}}
            <x-stat-card icon="fas fa-check-circle" title="الأحياء النشطة" :value="$activeNeighborhoodsCount" color="success" />
            {{-- المدن التي تحتوي أحياء --}}
            <x-stat-card icon="fas fa-city" title="عدد المدن" :value="$citiesWithNeighborhoodsCount" color="info" />
            {{-- آخر حي مضاف --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    آخر تحديث</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $neighborhoods->isNotEmpty() ? $neighborhoods->first()->updated_at->diffForHumans() : 'لا يوجد بيانات' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة قائمة الأحياء --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأحياء</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createNeighborhoodModal">
                    <i class="fas fa-plus"></i> إضافة حي
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث والتصفية --}}
                <form action="{{ route('neighborhoods.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search">بحث بالاسم:</label>
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="ابحث باسم الحي..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city_id">تصفية حسب المدينة:</label>
                                <select name="city_id" id="city_id" class="form-control">
                                    <option value="">كل المدن</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> تطبيق الفلتر
                            </button>
                            <a href="{{ route('neighborhoods.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>

                {{-- جدول الأحياء --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الحي</th>
                                <th>المدينة</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($neighborhoods as $neighborhood)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $neighborhood->name }}</td>
                                    <td>{{ $neighborhood->city->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $neighborhood->active ? 'success' : 'danger' }}">
                                            {{ $neighborhood->status }}
                                        </span>
                                    </td>
                                    <td>{{ $neighborhood->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showNeighborhoodModal{{ $neighborhood->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editNeighborhoodModal{{ $neighborhood->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteNeighborhoodModal{{ $neighborhood->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل حي --}}
                                        @include('dashboard.neighborhoods.modals.show', [
                                            'neighborhood' => $neighborhood,
                                        ])
                                        @include('dashboard.neighborhoods.modals.edit', [
                                            'neighborhood' => $neighborhood,
                                        ])
                                        @include('dashboard.neighborhoods.modals.delete', [
                                            'neighborhood' => $neighborhood,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا يوجد أحياء مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $neighborhoods->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة حي (ثابت) --}}
    @include('dashboard.neighborhoods.modals.create')
@endsection

@push('scripts')
    <script>
        // تفعيل التولتيب الافتراضي
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // عرض رسالة تأكيد قبل الحذف
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من استعادة هذه البيانات!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
