@extends('layouts.app')

@section('title', 'تفاصيل سجل التدقيق - لوحة التحكم')

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-info-circle text-primary"></i>
                    تفاصيل سجل التدقيق #{{ $auditLog->id }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('audit-logs.index') }}">سجل التدقيق</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تفاصيل السجل</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            {{-- معلومات أساسية --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> معلومات العملية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%" class="bg-light">المستخدم</th>
                                    <td>
                                        @if($auditLog->user)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-primary mr-2"></i>
                                                <div>
                                                    <strong>{{ $auditLog->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $auditLog->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-robot"></i> نظام
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">العملية</th>
                                    <td>
                                        @php
                                            $actionClasses = [
                                                'created' => 'badge-success',
                                                'updated' => 'badge-warning',
                                                'deleted' => 'badge-danger',
                                                'restored' => 'badge-info',
                                            ];
                                            $class = $actionClasses[$auditLog->action] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $class }} badge-lg">
                                            {{ $auditLog->getActionLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">النموذج</th>
                                    <td>
                                        <span class="badge badge-info badge-lg">
                                            {{ $auditLog->getModelLabel() }}
                                        </span>
                                        <small class="text-muted d-block mt-1">
                                            {{ $auditLog->auditable_type }} #{{ $auditLog->auditable_id }}
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الوصف</th>
                                    <td>{{ $auditLog->description }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">التاريخ والوقت</th>
                                    <td>
                                        {{ $auditLog->created_at->format('Y-m-d H:i:s') }}<br>
                                        <small class="text-muted">{{ $auditLog->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- القيم القديمة والجديدة --}}
                @if($auditLog->action === 'updated' && ($auditLog->old_values || $auditLog->new_values))
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-exchange-alt"></i> التغييرات
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-danger mb-3">
                                        <i class="fas fa-arrow-left"></i> القيم القديمة
                                    </h6>
                                    @if($auditLog->old_values)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>الحقل</th>
                                                        <th>القيمة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($auditLog->old_values as $field => $value)
                                                        <tr>
                                                            <td><strong>{{ $field }}</strong></td>
                                                            <td>
                                                                @if(is_array($value))
                                                                    <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $value ?? '-' }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">لا توجد قيم قديمة</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-arrow-right"></i> القيم الجديدة
                                    </h6>
                                    @if($auditLog->new_values)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>الحقل</th>
                                                        <th>القيمة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($auditLog->new_values as $field => $value)
                                                        <tr>
                                                            <td><strong>{{ $field }}</strong></td>
                                                            <td>
                                                                @if(is_array($value))
                                                                    <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $value ?? '-' }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">لا توجد قيم جديدة</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($auditLog->action === 'created' && $auditLog->new_values)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-plus-circle"></i> البيانات المُنشأة
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>الحقل</th>
                                            <th>القيمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditLog->new_values as $field => $value)
                                            <tr>
                                                <td><strong>{{ $field }}</strong></td>
                                                <td>
                                                    @if(is_array($value))
                                                        <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value ?? '-' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if($auditLog->action === 'deleted' && $auditLog->old_values)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-danger text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-trash"></i> البيانات المحذوفة
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>الحقل</th>
                                            <th>القيمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditLog->old_values as $field => $value)
                                            <tr>
                                                <td><strong>{{ $field }}</strong></td>
                                                <td>
                                                    @if(is_array($value))
                                                        <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value ?? '-' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- معلومات تقنية --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cog"></i> معلومات تقنية
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th class="bg-light">عنوان IP</th>
                                <td>{{ $auditLog->ip_address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">الطريقة (Method)</th>
                                <td>
                                    <span class="badge badge-info">{{ $auditLog->method ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">الرابط (URL)</th>
                                <td>
                                    <small class="text-break">{{ $auditLog->url ?? '-' }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">معلومات المتصفح</th>
                                <td>
                                    <small class="text-break">{{ Str::limit($auditLog->user_agent ?? '-', 100) }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list"></i> الإجراءات
                        </h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                        @if($auditLog->auditable)
                            <a href="#" class="btn btn-info btn-block" onclick="alert('سيتم التوجيه إلى نموذج {{ $auditLog->getModelLabel() }}');">
                                <i class="fas fa-external-link-alt"></i> عرض النموذج المرتبط
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

