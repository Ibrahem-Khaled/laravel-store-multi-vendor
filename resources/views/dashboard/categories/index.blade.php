@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة التصنيفات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">التصنيفات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات التصنيفات --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي التصنيفات" :count="$categoriesCount" icon="tags" color="primary" class="mb-4" />
            <x-stat-card title="تصنيفات تحتوي على صور" :count="$categoriesWithImages" icon="image" color="success" class="mb-4" />
            <x-stat-card title="تصنيفات تحتوي على وصف" :count="$categoriesWithDescription" icon="align-left" color="info" class="mb-4" />
        </div>

        {{-- بطاقة قائمة التصنيفات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة التصنيفات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">
                    <i class="fas fa-plus"></i> إضافة تصنيف
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('categories.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث باسم التصنيف أو الوصف..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول التصنيفات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>الوصف</th>
                                <th>نسبة العمولة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                alt="{{ $category->name }}" class="img-thumbnail" width="60"
                                                height="60">
                                        @else
                                            <div class="no-image bg-light d-flex align-items-center justify-content-center"
                                                style="width:60px; height:60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ? Str::limit($category->description, 50) : 'لا يوجد وصف' }}
                                    </td>
                                    <td>
                                        @if($category->commission_rate)
                                            <span class="badge badge-success">{{ number_format($category->commission_rate * 100, 2) }}%</span>
                                        @else
                                            <span class="badge badge-secondary">افتراضي</span>
                                        @endif
                                    </td>
                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showCategoryModal{{ $category->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editCategoryModal{{ $category->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteCategoryModal{{ $category->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل تصنيف --}}
                                        @include('dashboard.categories.modals.show', [
                                            'category' => $category,
                                        ])
                                        @include('dashboard.categories.modals.edit', [
                                            'category' => $category,
                                        ])
                                        @include('dashboard.categories.modals.delete', [
                                            'category' => $category,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد تصنيفات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة تصنيف (ثابت) --}}
    @include('dashboard.categories.modals.create')
@endsection

@push('scripts')
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
    </script>
@endpush
