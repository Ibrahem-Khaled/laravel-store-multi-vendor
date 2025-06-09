@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة التقييمات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">التقييمات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات التقييمات --}}
        <div class="row mb-4">
            {{-- إجمالي التقييمات --}}
            <x-stat-card icon="fas fa-star" title="إجمالي التقييمات" :value="$totalReviews" color="primary" />
            {{-- التقييمات المعتمدة --}}
            <x-stat-card icon="fas fa-check-circle" title="المعتمدة" :value="$approvedReviews" color="success" />
            {{-- التقييمات المعلقة --}}
            <x-stat-card icon="fas fa-clock" title="المعلقة" :value="$pendingReviews" color="warning" />
            {{-- متوسط التقييمات --}}
            <x-stat-card icon="fas fa-chart-line" title="المتوسط" :value="number_format($averageRating, 1)" color="info" />
        </div>

        {{-- بطاقة قائمة التقييمات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة التقييمات</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createReviewModal">
                    <i class="fas fa-plus"></i> إضافة تقييم
                </button>
            </div>
            <div class="card-body">
                {{-- تبويب حالة التقييمات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}"
                            href="{{ route('reviews.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}"
                            href="{{ route('reviews.index', ['status' => 'approved']) }}">المعتمدة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}"
                            href="{{ route('reviews.index', ['status' => 'pending']) }}">المعلقة</a>
                    </li>
                </ul>

                {{-- فلترة التقييمات --}}
                <form action="{{ route('reviews.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="product_id" class="form-control">
                                <option value="">كل المنتجات</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="user_id" class="form-control">
                                <option value="">كل المستخدمين</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="ابحث في التعليقات..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
                                <i class="fas fa-filter"></i> تصفية
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول التقييمات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>المستخدم</th>
                                <th>التقييم</th>
                                <th>التعليق</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $review->product->name }}</td>
                                    <td>{{ $review->user->name }}</td>
                                    <td>
                                        <div class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rate ? 'text-warning' : 'text-secondary' }}"></i>
                                            @endfor
                                            <span class="badge badge-light">{{ $review->rate }}</span>
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>
                                        @if ($review->is_approved)
                                            <span class="badge badge-success">معتمد</span>
                                        @else
                                            <span class="badge badge-warning">معلق</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showReviewModal{{ $review->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editReviewModal{{ $review->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteReviewModal{{ $review->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- زر الموافقة --}}
                                        @if (!$review->is_approved)
                                            <form action="{{ route('reviews.approve', $review->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-circle btn-success"
                                                    title="موافقة">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('reviews.disapprove', $review->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-circle btn-warning"
                                                    title="رفض">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- تضمين المودالات لكل تقييم --}}
                                        @include('dashboard.reviews.modals.show', ['review' => $review])
                                        @include('dashboard.reviews.modals.edit', ['review' => $review])
                                        @include('dashboard.reviews.modals.delete', ['review' => $review])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا يوجد تقييمات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة تقييم (ثابت) --}}
    @include('dashboard.reviews.modals.create')
@endsection

@push('styles')
    <style>
        .rating {
            display: inline-block;
            unicode-bidi: bidi-override;
            direction: ltr;
        }

        .rating i {
            font-size: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // تفعيل التولتيب الافتراضي
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
