@extends('layouts.app')

@section('title', 'إدارة النسخ الاحتياطية')

@section('content')
    <div class="container-fluid">
        <!-- بطاقة العنوان -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-database"></i> إدارة النسخ الاحتياطية
            </h1>
            <form action="{{ route('backups.create') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إنشاء نسخة احتياطية جديدة
                </button>
            </form>
        </div>

        <!-- إحصائيات -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي النسخ</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-database fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    الحجم الإجمالي</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hdd fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    تلقائية</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['automatic'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-robot fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    يدوية</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['manual'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-pointer fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول النسخ الاحتياطية -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">قائمة النسخ الاحتياطية</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>النوع</th>
                                <th>الحجم</th>
                                <th>تاريخ الإنشاء</th>
                                <th>منشئ بواسطة</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $backup)
                                <tr>
                                    <td>{{ $backup->id }}</td>
                                    <td>
                                        <i class="fas fa-file-archive"></i>
                                        {{ $backup->filename }}
                                    </td>
                                    <td>
                                        @if($backup->type === 'automatic')
                                            <span class="badge badge-info">
                                                <i class="fas fa-robot"></i> تلقائي
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-hand-pointer"></i> يدوي
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $backup->size_formatted }}</td>
                                    <td>{{ $backup->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        {{ $backup->creator->name ?? 'نظام' }}
                                    </td>
                                    <td>
                                        @if($backup->fileExists())
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> متوفر
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> غير متوفر
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($backup->fileExists())
                                                <a href="{{ route('backups.download', $backup) }}" 
                                                   class="btn btn-sm btn-info" title="تنزيل">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <form action="{{ route('backups.restore', $backup) }}" method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من استعادة هذه النسخة؟ سيتم استبدال جميع البيانات الحالية.');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="استعادة">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('backups.destroy', $backup) }}" method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه النسخة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-muted">لا توجد نسخ احتياطية حالياً</p>
                                        <form action="{{ route('backups.create') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إنشاء أول نسخة احتياطية
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $backups->links() }}
                </div>
            </div>
        </div>

        <!-- معلومات مهمة -->
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle"></i> معلومات مهمة:</h5>
            <ul class="mb-0">
                <li>يتم إنشاء نسخة احتياطية تلقائياً كل يوم الساعة 2:00 صباحاً</li>
                <li>يتم الاحتفاظ بآخر 30 نسخة احتياطية تلقائياً</li>
                <li>يمكنك إنشاء نسخة احتياطية يدوية في أي وقت</li>
                <li>تأكد من وجود مساحة كافية قبل الاستعادة</li>
                <li>الاستعادة ستحل محل جميع البيانات الحالية</li>
            </ul>
        </div>
    </div>
@endsection

