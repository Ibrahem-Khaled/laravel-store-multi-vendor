@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة العلامات التجارية</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">العلامات التجارية</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات العلامات التجارية --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي العلامات" :count="$brandsCount" icon="tags" color="primary" class="mb-4" />
            <x-stat-card title="علامات نشطة" :count="$activeBrands" icon="check-circle" color="success" class="mb-4" />
            <x-stat-card title="علامات لديها مواقع" :count="$brandsWithLocations" icon="map-marker-alt" color="info" class="mb-4" />
        </div>

        {{-- بطاقة قائمة العلامات التجارية --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة العلامات التجارية</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createBrandModal">
                    <i class="fas fa-plus"></i> إضافة علامة
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث والتصفية --}}
                <form action="{{ route('brands.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث باسم العلامة أو الوصف..." value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="user_id">
                                    <option value="">كل المستخدمين</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="is_active">
                                    <option value="">كل الحالات</option>
                                    <option value="1" {{ $isActive === '1' ? 'selected' : '' }}>نشطة فقط</option>
                                    <option value="0" {{ $isActive === '0' ? 'selected' : '' }}>غير نشطة فقط</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-filter"></i> تصفية
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول العلامات التجارية مع خاصية السحب والإفلات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="brandsTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th width="100">الشعار</th>
                                <th>الاسم</th>
                                <th>المستخدم</th>
                                <th>الرابط</th>
                                <th width="100">الحالة</th>
                                <th width="100">الترتيب</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @forelse($brands as $brand)
                                <tr data-id="{{ $brand->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                                            class="img-thumbnail" width="60">
                                    </td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->user->name }}</td>
                                    <td>
                                        @if ($brand->link)
                                            <a href="{{ $brand->link }}" target="_blank" class="btn btn-sm btn-link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $brand->is_active ? 'success' : 'secondary' }}">
                                            {{ $brand->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td class="order-cell">{{ $brand->order }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showBrandModal{{ $brand->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editBrandModal{{ $brand->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteBrandModal{{ $brand->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل علامة تجارية --}}
                                        @include('dashboard.brands.modals.show', ['brand' => $brand])
                                        @include('dashboard.brands.modals.edit', ['brand' => $brand])
                                        @include('dashboard.brands.modals.delete', ['brand' => $brand])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد علامات تجارية</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $brands->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة علامة تجارية (ثابت) --}}
    @include('dashboard.brands.modals.create')
@endsection

@push('styles')
    <style>
        #sortable tr {
            cursor: move;
        }

        #sortable tr.sortable-ghost {
            opacity: 0.5;
            background: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        // عرض اسم الملف المختار في حقول upload
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // تفعيل التولتيب الافتراضي
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        // تفعيل خاصية السحب والإفلات للترتيب
        new Sortable(document.getElementById('sortable'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                var brands = [];
                $('#sortable tr').each(function(index) {
                    brands.push({
                        id: $(this).data('id'),
                        order: index + 1
                    });
                    $(this).find('.order-cell').text(index + 1);
                });

                $.ajax({
                    url: "{{ route('brands.update-order') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        brands: brands
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('تم تحديث الترتيب بنجاح');
                        }
                    }
                });
            }
        });
    </script>
@endpush
