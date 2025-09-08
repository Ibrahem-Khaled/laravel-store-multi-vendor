<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم الرئيسية</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        الإدارة
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-expanded="true" aria-controls="collapseUsers">
            <i class="fas fa-fw fa-users-cog"></i>
            @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                <span class="badge badge-danger badge-counter ml-2">{{ $pendingRequestsCount }}</span>
            @endif
            <span>إدارة المستخدمين</span>
        </a>
        <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة المستخدمين:</h6>
                <a class="collapse-item" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-users text-gray-600"></i>
                    <span>كل المستخدمين</span>
                </a>
                <a class="collapse-item" href="{{ route('role-requests.index') }}"> {{-- تأكد من اسم الراوت هنا --}}
                    <i class="fas fa-fw fa-user-shield text-gray-600"></i>
                    <span>طلبات الصلاحيات</span>
                    @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                        <span class="badge badge-danger badge-counter ml-2">{{ $pendingRequestsCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCatalog"
            aria-expanded="true" aria-controls="collapseCatalog">
            <i class="fas fa-fw fa-store"></i>
            <span>إدارة الكتالوج</span>
        </a>
        <div id="collapseCatalog" class="collapse" aria-labelledby="headingCatalog" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">عناصر الكتالوج:</h6>
                <a class="collapse-item" href="{{ route('products.index') }}">المنتجات</a>
                <a class="collapse-item" href="{{ route('categories.index') }}">التصنيفات</a>
                <a class="collapse-item" href="{{ route('sub-categories.index') }}">التصنيفات الفرعية</a>
                <a class="collapse-item" href="{{ route('brands.index') }}">العلامات التجارية</a>
                <a class="collapse-item" href="{{ route('features.index') }}">الميزات الرئيسية</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders"
            aria-expanded="false" aria-controls="collapseOrders">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>إدارة الطلبات</span>
        </a>
        <div id="collapseOrders" class="collapse" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">الطلبات:</h6>
                <a class="collapse-item" href="{{ route('orders.index') }}">كل الطلبات</a>
                {{-- ممكن تضيف فلاتر سريعة كرابط إن أحببت: --}}
                <a class="collapse-item" href="{{ route('orders.index', ['status' => 'pending']) }}">قيد
                    الانتظار</a>
                <a class="collapse-item" href="{{ route('orders.index', ['status' => 'paid']) }}">مدفوعة</a>
                <a class="collapse-item" href="{{ route('orders.index', ['status' => 'shipped']) }}">مُرسلة</a>
                <a class="collapse-item" href="{{ route('orders.index', ['status' => 'completed']) }}">مكتملة</a>
                <a class="collapse-item" href="{{ route('orders.index', ['status' => 'cancelled']) }}">ملغاة</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMerchants"
            aria-expanded="false" aria-controls="collapseMerchants">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>التجّار والمحاسبة</span>
        </a>
        <div id="collapseMerchants" class="collapse" aria-labelledby="headingMerchants" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة التجّار:</h6>
                <a class="collapse-item" href="{{ route('merchants.index') }}">قائمة التجّار</a>
                {{-- أمثلة روابط تقارير سريعة --}}
                <a class="collapse-item" href="{{ route('merchants.index', ['sort' => 'balance']) }}">أعلى
                    أرصدة</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLocations"
            aria-expanded="true" aria-controls="collapseLocations">
            <i class="fas fa-fw fa-map-marked-alt"></i>
            <span>إدارة المواقع</span>
        </a>
        <div id="collapseLocations" class="collapse" aria-labelledby="headingLocations"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">المواقع الجغرافية:</h6>
                <a class="collapse-item" href="{{ route('cities.index') }}">المدن</a>
                <a class="collapse-item" href="{{ route('neighborhoods.index') }}">الاحياء</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContent"
            aria-expanded="true" aria-controls="collapseContent">
            <i class="fas fa-fw fa-desktop"></i>
            <span>إدارة المحتوى</span>
        </a>
        <div id="collapseContent" class="collapse" aria-labelledby="headingContent" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">محتوى الموقع:</h6>
                <a class="collapse-item" href="{{ route('slide-shows.index') }}">السلايدشو</a>
                <a class="collapse-item" href="{{ route('reviews.index') }}">التقييمات</a>
                <a class="collapse-item" href="{{ route('notifications.index') }}">الاشعارات</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
