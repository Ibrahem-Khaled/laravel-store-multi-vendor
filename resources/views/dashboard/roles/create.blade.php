@extends('layouts.app')

@section('title', 'إضافة دور جديد - لوحة التحكم')

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-plus-circle text-primary"></i>
                    إضافة دور جديد
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">الأدوار والصلاحيات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة دور</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات الدور</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>اسم الدور (مثلاً: accountant) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') تعديل-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">يجب أن يكون باللغة الإنجليزية ولا يحتوي على مسافات</small>
                            </div>

                            <div class="form-group">
                                <label>الاسم المعروض (مثلاً: محاسب) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="display_name" 
                                       class="form-control @error('display_name') is-invalid @enderror" 
                                       value="{{ old('display_name') }}" 
                                       required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>الوصف</label>
                                <textarea name="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>الترتيب</label>
                                <input type="number" 
                                       name="order" 
                                       class="form-control" 
                                       value="{{ old('order', 0) }}" 
                                       min="0">
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" 
                                       name="is_active" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نشط
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="mb-3">الصلاحيات <span class="text-danger">*</span></label>
                                
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           class="form-check-input" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        <strong>{{ $permission->display_name }}</strong>
                                                        @if($permission->description)
                                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Select all permissions in a group
        $(document).ready(function() {
            $('.card-header').click(function() {
                var card = $(this).parent();
                var checkboxes = card.find('input[type="checkbox"]');
                var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                checkboxes.prop('checked', !allChecked);
            });
        });
    </script>
@endpush

