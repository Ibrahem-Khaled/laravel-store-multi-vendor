@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة السلايد شو</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">السلايد شو</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات السلايد شو --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي الشرائح" :count="$slidesCount" icon="images" color="primary" class="mb-4" />
            <x-stat-card title="شرائح نشطة" :count="$activeSlides" icon="eye" color="success" class="mb-4" />
            <x-stat-card title="شرائح تحتوي على روابط" :count="$slidesWithLinks" icon="link" color="info" class="mb-4" />
        </div>

        {{-- بطاقة قائمة الشرائح --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الشرائح</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createSlideModal">
                    <i class="fas fa-plus"></i> إضافة شريحة
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث --}}
                <form action="{{ route('slide-shows.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث بعنوان الشريحة أو الوصف..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الشرائح مع خاصية السحب والإفلات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="slidesTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th width="100">الصورة</th>
                                <th>العنوان</th>
                                <th>الوصف</th>
                                <th width="120">الرابط</th>
                                <th width="100">الحالة</th>
                                <th width="100">الترتيب</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @forelse($slides as $slide)
                                <tr data-id="{{ $slide->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}"
                                            class="img-thumbnail" width="80">
                                    </td>
                                    <td>{{ $slide->title ?? 'بدون عنوان' }}</td>
                                    <td>{{ $slide->description ? Str::limit($slide->description, 50) : 'لا يوجد وصف' }}</td>
                                    <td>
                                        @if ($slide->link)
                                            <a href="{{ $slide->link }}" target="_blank" class="btn btn-sm btn-link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $slide->is_active ? 'success' : 'secondary' }}">
                                            {{ $slide->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td class="order-cell">{{ $slide->order }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showSlideModal{{ $slide->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editSlideModal{{ $slide->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteSlideModal{{ $slide->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- تضمين المودالات لكل شريحة --}}
                                        @include('dashboard.slide-shows.modals.show', ['slide' => $slide])
                                        @include('dashboard.slide-shows.modals.edit', ['slide' => $slide])
                                        @include('dashboard.slide-shows.modals.delete', [
                                            'slide' => $slide,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد شرائح</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $slides->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة شريحة (ثابت) --}}
    @include('dashboard.slide-shows.modals.create')
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
                var slides = [];
                $('#sortable tr').each(function(index) {
                    slides.push({
                        id: $(this).data('id'),
                        order: index + 1
                    });
                    $(this).find('.order-cell').text(index + 1);
                });

                $.ajax({
                    url: "{{ route('slide-shows.update-order') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        slides: slides
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
