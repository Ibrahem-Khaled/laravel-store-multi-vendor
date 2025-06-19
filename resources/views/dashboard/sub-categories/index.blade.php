@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الأقسام الفرعية</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الأقسام الفرعية</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الأقسام الفرعية --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي الأقسام الفرعية" :count="$subCategoriesCount" icon="tags" color="primary" class="mb-4" />
            <x-stat-card title="أقسام تحتوي على صور" :count="$subCategoriesWithImages" icon="image" color="success" class="mb-4" />
            <x-stat-card title="عدد الأقسام الرئيسية" :count="$categoriesCount" icon="folder" color="info" class="mb-4" />
        </div>

        {{-- بطاقة قائمة الأقسام الفرعية --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الأقسام الفرعية</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createSubCategoryModal">
                    <i class="fas fa-plus"></i> إضافة قسم فرعي
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('sub-categories.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث باسم القسم الفرعي أو الوصف أو القسم الرئيسي..."
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الأقسام الفرعية --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>القسم الرئيسي</th>
                                <th>الوصف</th>
                                <th>نوع الحجز </th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subCategories as $subCategory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($subCategory->image)
                                            <img src="{{ asset('storage/' . $subCategory->image) }}"
                                                alt="{{ $subCategory->name }}" class="img-thumbnail" width="60"
                                                height="60">
                                        @else
                                            <div class="no-image bg-light d-flex align-items-center justify-content-center"
                                                style="width:60px; height:60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ $subCategory->category->name }}</td>
                                    <td>{{ $subCategory->description ? Str::limit($subCategory->description, 50) : 'لا يوجد وصف' }}
                                    </td>
                                    <td>{{ $subCategory->type == 'daily' ? 'باليوم' : 'بالفترات' }}</td>
                                    <td>{{ $subCategory->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showSubCategoryModal{{ $subCategory->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editSubCategoryModal{{ $subCategory->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteSubCategoryModal{{ $subCategory->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل قسم فرعي --}}
                                        @include('dashboard.sub-categories.modals.show', [
                                            'subCategory' => $subCategory,
                                        ])
                                        @include('dashboard.sub-categories.modals.edit', [
                                            'subCategory' => $subCategory,
                                        ])
                                        @include('dashboard.sub-categories.modals.delete', [
                                            'subCategory' => $subCategory,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد أقسام فرعية</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $subCategories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة قسم فرعي (ثابت) --}}
    @include('dashboard.sub-categories.modals.create')
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
