@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0 text-gray-800">إدارة مميزات المنتج: {{ $product->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                            <li class="breadcrumb-item active" aria-current="page">مميزات المنتج</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- الميزات الحالية للمنتج --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">الميزات المضافة حالياً</h6>
                        <span class="badge badge-light">{{ $product->features->count() }} ميزة</span>
                    </div>
                    <div class="card-body">
                        @if ($product->features->isEmpty())
                            <div class="alert alert-info text-center">
                                لا توجد ميزات مضافة لهذا المنتج حتى الآن
                            </div>
                        @else
                            <div class="row">
                                @foreach ($product->features as $feature)
                                    <div class="col-md-6 mb-4">
                                        <div
                                            class="feature-card card h-100 border-left-{{ $feature->applicable_to === 'residency' ? 'success' : 'info' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="card-title">
                                                            <i
                                                                class="fas fa-{{ $feature->applicable_to === 'residency' ? 'home' : 'building' }} mr-2"></i>
                                                            {{ $feature->name }}
                                                        </h5>
                                                        <span
                                                            class="badge badge-{{ $feature->applicable_to === 'residency' ? 'success' : 'info' }}">
                                                            {{ $feature->applicable_to === 'residency' ? 'سكن' : 'قاعة' }}
                                                        </span>
                                                    </div>
                                                    <form action="{{ route('features.remove-from-product') }}"
                                                        method="POST" class="delete-feature-form">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="feature_id"
                                                            value="{{ $feature->id }}">
                                                        <button type="submit" class="btn btn-sm btn-circle btn-danger"
                                                            title="حذف الميزة">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent">
                                                <small class="text-muted">
                                                    أضيفت في: {{ $feature?->pivot?->created_at?->format('Y-m-d') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- إضافة ميزات جديدة --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">إضافة ميزات جديدة</h6>
                    </div>
                    <div class="card-body">
                        <form id="add-features-form" action="{{ route('features.add-to-product') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="form-group">
                                <label for="feature-search">بحث عن الميزات</label>
                                <input type="text" id="feature-search" class="form-control" placeholder="اكتب للبحث...">
                            </div>

                            <div class="form-group">
                                <label>اختر الميزات المطلوبة</label>
                                <div class="features-list" style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($availableFeatures as $feature)
                                        <div class="custom-control custom-checkbox mb-3 feature-item">
                                            <input type="checkbox" class="custom-control-input"
                                                id="feature-{{ $feature->id }}" name="feature_ids[]"
                                                value="{{ $feature->id }}">
                                            <label class="custom-control-label d-flex align-items-center"
                                                for="feature-{{ $feature->id }}">
                                                <span
                                                    class="badge badge-{{ $feature->applicable_to === 'residency' ? 'success' : 'info' }} mr-2">
                                                    {{ $feature->applicable_to === 'residency' ? 'سكن' : 'قاعة' }}
                                                </span>
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-plus-circle"></i> إضافة الميزات المحددة
                            </button>
                        </form>
                    </div>
                </div>

                {{-- إضافة ميزة جديدة مباشرة --}}
                <div class="card shadow">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">إنشاء ميزة جديدة</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('features.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="form-group">
                                <label for="new-feature-name">اسم الميزة الجديدة</label>
                                <input type="text" class="form-control" id="new-feature-name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="new-feature-type">نوع الميزة</label>
                                <select class="form-control" id="new-feature-type" name="applicable_to" required>
                                    <option value="residency">ميزة سكن</option>
                                    <option value="hall">ميزة قاعة</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-save"></i> حفظ الميزة
                                </button>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="document.getElementById('new-feature-name').value = ''">
                                    <i class="fas fa-undo"></i> إعادة تعيين
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تأكيد الحذف Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في حذف هذه الميزة من المنتج؟</p>
                    <p class="text-danger"><small>هذا الإجراء لا يمكن التراجع عنه.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">حذف الميزة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .features-list::-webkit-scrollbar {
            width: 8px;
        }

        .features-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .features-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .features-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // البحث عن الميزات
            $('#feature-search').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('.feature-item').each(function() {
                    const featureText = $(this).text().toLowerCase();
                    $(this).toggle(featureText.includes(searchText));
                });
            });

            // تأكيد الحذف
            $('.delete-feature-form').on('submit', function(e) {
                e.preventDefault();
                $('#confirmDeleteModal').modal('show');
                $('#deleteForm').attr('action', $(this).attr('action'));
                $('#deleteForm').attr('method', $(this).attr('method'));
                $('#deleteForm input[name="product_id"]').val($(this).find('input[name="product_id"]')
                .val());
                $('#deleteForm input[name="feature_id"]').val($(this).find('input[name="feature_id"]')
                .val());
            });

            // رسالة نجاح الإضافة
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush
