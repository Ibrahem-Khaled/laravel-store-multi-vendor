@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة المنتجات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">المنتجات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات المنتجات --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي المنتجات" :count="$productsCount" icon="box" color="primary" class="mb-4" />
            <x-stat-card title="منتجات عليها خصم" :count="$productsWithDiscount" icon="tags" color="success" class="mb-4" />
            <x-stat-card title="منتجات تحتوي على فيديو" :count="$productsWithVideo" icon="video" color="info" class="mb-4" />
            <x-stat-card title="عدد الأقسام الفرعية" :count="$subCategoriesCount" icon="folder" color="warning" class="mb-4" />
        </div>

        {{-- بطاقة قائمة المنتجات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة المنتجات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">
                    <i class="fas fa-plus"></i> إضافة منتج
                </button>
            </div>
            <div class="card-body">
                {{-- نموذج البحث والتصفية --}}
                <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث باسم المنتج أو الوصف أو المدينة..." value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="sub_category_id">
                                    <option value="">كل الأقسام الفرعية</option>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ $subCategoryId == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول المنتجات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الصور</th>
                                <th>الاسم</th>
                                <th>القسم الفرعي</th>
                                <th>المدينة</th>
                                <th>السعر</th>
                                <th>الخصم</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                                alt="{{ $product->name }}" class="img-thumbnail" width="60">
                                            @if ($product->images->count() > 1)
                                                <span
                                                    class="badge badge-primary">+{{ $product->images->count() - 1 }}</span>
                                            @endif
                                        @else
                                            <div class="no-image bg-light d-flex align-items-center justify-content-center"
                                                style="width:60px; height:60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->subCategory->name }}</td>
                                    <td>{{ $product->city->name }} - {{ $product->neighborhood->name }}</td>
                                    <td>
                                        @if ($product->discount_percent > 0)
                                            <span class="text-danger"><del>{{ number_format($product->price, 2) }}
                                                </del></span>
                                            <br>
                                            <span
                                                class="text-success">{{ number_format($product->price_after_discount, 2) }}
                                            </span>
                                        @else
                                            {{ number_format($product->price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->discount_percent > 0)
                                            <span class="badge badge-success">{{ $product->discount_percent }}%</span>
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showProductModal{{ $product->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editProductModal{{ $product->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteProductModal{{ $product->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <a href="{{ route('products.features.show', $product->id) }}"
                                            class="btn btn-sm btn-circle btn-warning" title="ميزات المنتج">
                                            <i class="fas fa-list"></i>
                                        </a>

                                        {{-- تضمين المودالات لكل منتج --}}
                                        @include('dashboard.products.modals.show', ['product' => $product])
                                        @include('dashboard.products.modals.edit', ['product' => $product])
                                        @include('dashboard.products.modals.delete', [
                                            'product' => $product,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد منتجات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة منتج (ثابت) --}}
    @include('dashboard.products.modals.create')
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

        // حساب السعر بعد الخصم تلقائياً
        function calculateDiscount() {
            var price = parseFloat($('#price').val()) || 0;
            var discount = parseInt($('#discount_percent').val()) || 0;
            var discountedPrice = price * (1 - (discount / 100));
            $('#price_after_discount').val(discountedPrice.toFixed(2));
        }

        $('#price, #discount_percent').on('input', calculateDiscount);
    </script>
@endpush
