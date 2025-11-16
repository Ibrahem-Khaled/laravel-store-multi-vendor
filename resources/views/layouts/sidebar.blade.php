<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم الرئيسية</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        الإدارة
    </div>

    <li class="nav-item {{ request()->routeIs('users.*') || request()->routeIs('role-requests.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('role-requests.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('role-requests.*') ? 'true' : 'false' }}"
            aria-controls="collapseUsers">
            <i class="fas fa-fw fa-users-cog"></i>
            @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                <span class="badge badge-danger badge-counter ml-2">{{ $pendingRequestsCount }}</span>
            @endif
            <span>إدارة المستخدمين</span>
        </a>
        <div id="collapseUsers" class="collapse {{ request()->routeIs('users.*') || request()->routeIs('role-requests.*') ? 'show' : '' }}"
             aria-labelledby="headingUsers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة المستخدمين:</h6>
                <a class="collapse-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-users text-gray-600"></i>
                    <span>كل المستخدمين</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('role-requests.*') ? 'active' : '' }}" href="{{ route('role-requests.index') }}">
                    <i class="fas fa-fw fa-user-shield text-gray-600"></i>
                    <span>طلبات الصلاحيات</span>
                    @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                        <span class="badge badge-danger badge-counter ml-2">{{ $pendingRequestsCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('sub-categories.*') || request()->routeIs('brands.*') || request()->routeIs('features.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('sub-categories.*') || request()->routeIs('brands.*') || request()->routeIs('features.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseCatalog"
            aria-expanded="{{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('sub-categories.*') || request()->routeIs('brands.*') || request()->routeIs('features.*') ? 'true' : 'false' }}"
            aria-controls="collapseCatalog">
            <i class="fas fa-fw fa-store"></i>
            @if (isset($pendingApprovalCount) && $pendingApprovalCount > 0)
                <span class="badge badge-danger badge-counter ml-2">{{ $pendingApprovalCount }}</span>
            @endif
            <span>إدارة الكتالوج</span>
        </a>
        <div id="collapseCatalog" class="collapse {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('sub-categories.*') || request()->routeIs('brands.*') || request()->routeIs('features.*') ? 'show' : '' }}"
             aria-labelledby="headingCatalog" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">عناصر الكتالوج:</h6>
                <a class="collapse-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    المنتجات
                    @if (isset($pendingApprovalCount) && $pendingApprovalCount > 0)
                        <span class="badge badge-danger badge-counter ml-2">{{ $pendingApprovalCount }}</span>
                    @endif
                </a>
                <a class="collapse-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">التصنيفات</a>
                <a class="collapse-item {{ request()->routeIs('sub-categories.*') ? 'active' : '' }}" href="{{ route('sub-categories.index') }}">التصنيفات الفرعية</a>
                <a class="collapse-item {{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.index') }}">العلامات التجارية</a>
                <a class="collapse-item {{ request()->routeIs('features.*') ? 'active' : '' }}" href="{{ route('features.index') }}">الميزات الرئيسية</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('orders.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseOrders"
            aria-expanded="{{ request()->routeIs('orders.*') ? 'true' : 'false' }}"
            aria-controls="collapseOrders">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>إدارة الطلبات</span>
        </a>
        <div id="collapseOrders" class="collapse {{ request()->routeIs('orders.*') ? 'show' : '' }}"
             aria-labelledby="headingOrders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">الطلبات:</h6>
                <a class="collapse-item {{ request()->routeIs('orders.index') && !request()->has('status') ? 'active' : '' }}"
                   href="{{ route('orders.index') }}">كل الطلبات</a>
                <a class="collapse-item {{ request()->routeIs('orders.index') && request('status') == 'pending' ? 'active' : '' }}"
                   href="{{ route('orders.index', ['status' => 'pending']) }}">قيد الانتظار</a>
                <a class="collapse-item {{ request()->routeIs('orders.index') && request('status') == 'paid' ? 'active' : '' }}"
                   href="{{ route('orders.index', ['status' => 'paid']) }}">مدفوعة</a>
                <a class="collapse-item {{ request()->routeIs('orders.index') && request('status') == 'shipped' ? 'active' : '' }}"
                   href="{{ route('orders.index', ['status' => 'shipped']) }}">مُرسلة</a>
                <a class="collapse-item {{ request()->routeIs('orders.index') && request('status') == 'completed' ? 'active' : '' }}"
                   href="{{ route('orders.index', ['status' => 'completed']) }}">مكتملة</a>
                <a class="collapse-item {{ request()->routeIs('orders.index') && request('status') == 'cancelled' ? 'active' : '' }}"
                   href="{{ route('orders.index', ['status' => 'cancelled']) }}">ملغاة</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('merchants.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('merchants.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseMerchants"
            aria-expanded="{{ request()->routeIs('merchants.*') ? 'true' : 'false' }}"
            aria-controls="collapseMerchants">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>التجّار والمحاسبة</span>
        </a>
        <div id="collapseMerchants" class="collapse {{ request()->routeIs('merchants.*') ? 'show' : '' }}"
             aria-labelledby="headingMerchants" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة التجّار:</h6>
                <a class="collapse-item {{ request()->routeIs('merchants.index') && !request()->has('sort') ? 'active' : '' }}"
                   href="{{ route('merchants.index') }}">قائمة التجّار</a>
                <a class="collapse-item {{ request()->routeIs('merchants.index') && request('sort') == 'balance' ? 'active' : '' }}"
                   href="{{ route('merchants.index', ['sort' => 'balance']) }}">أعلى أرصدة</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.driver.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.driver.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseDrivers"
            aria-expanded="{{ request()->routeIs('admin.driver.*') ? 'true' : 'false' }}"
            aria-controls="collapseDrivers">
            <i class="fas fa-fw fa-truck"></i>
            <span>إدارة السواقين</span>
        </a>
        <div id="collapseDrivers" class="collapse {{ request()->routeIs('admin.driver.*') ? 'show' : '' }}"
             aria-labelledby="headingDrivers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة السواقين:</h6>
                <a class="collapse-item {{ request()->routeIs('admin.driver.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.driver.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt text-gray-600"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.driver.drivers') ? 'active' : '' }}"
                   href="{{ route('admin.driver.drivers') }}">
                    <i class="fas fa-fw fa-users text-gray-600"></i>
                    <span>قائمة السواقين</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.driver.orders') ? 'active' : '' }}"
                   href="{{ route('admin.driver.orders') }}">
                    <i class="fas fa-fw fa-shopping-cart text-gray-600"></i>
                    <span>إدارة الطلبات</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('admin.driver.create') ? 'active' : '' }}"
                   href="{{ route('admin.driver.create') }}">
                    <i class="fas fa-fw fa-user-plus text-gray-600"></i>
                    <span>إضافة سواق</span>
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('cities.*') || request()->routeIs('neighborhoods.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('cities.*') || request()->routeIs('neighborhoods.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseLocations"
            aria-expanded="{{ request()->routeIs('cities.*') || request()->routeIs('neighborhoods.*') ? 'true' : 'false' }}"
            aria-controls="collapseLocations">
            <i class="fas fa-fw fa-map-marked-alt"></i>
            <span>إدارة المواقع</span>
        </a>
        <div id="collapseLocations" class="collapse {{ request()->routeIs('cities.*') || request()->routeIs('neighborhoods.*') ? 'show' : '' }}"
             aria-labelledby="headingLocations" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">المواقع الجغرافية:</h6>
                <a class="collapse-item {{ request()->routeIs('cities.*') ? 'active' : '' }}" href="{{ route('cities.index') }}">المدن</a>
                <a class="collapse-item {{ request()->routeIs('neighborhoods.*') ? 'active' : '' }}" href="{{ route('neighborhoods.index') }}">الاحياء</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('slide-shows.*') || request()->routeIs('reviews.*') || request()->routeIs('notifications.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('slide-shows.*') || request()->routeIs('reviews.*') || request()->routeIs('notifications.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseContent"
            aria-expanded="{{ request()->routeIs('slide-shows.*') || request()->routeIs('reviews.*') || request()->routeIs('notifications.*') ? 'true' : 'false' }}"
            aria-controls="collapseContent">
            <i class="fas fa-fw fa-desktop"></i>
            <span>إدارة المحتوى</span>
        </a>
        <div id="collapseContent" class="collapse {{ request()->routeIs('slide-shows.*') || request()->routeIs('reviews.*') || request()->routeIs('notifications.*') ? 'show' : '' }}"
             aria-labelledby="headingContent" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">محتوى الموقع:</h6>
                <a class="collapse-item {{ request()->routeIs('slide-shows.*') ? 'active' : '' }}" href="{{ route('slide-shows.index') }}">السلايدشو</a>
                <a class="collapse-item {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">التقييمات</a>
                <a class="collapse-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">الاشعارات</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('loyalty-management.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('loyalty-management.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseLoyalty"
            aria-expanded="{{ request()->routeIs('loyalty-management.*') ? 'true' : 'false' }}"
            aria-controls="collapseLoyalty">
            <i class="fas fa-fw fa-star"></i>
            <span>نقاط الولاء</span>
        </a>
        <div id="collapseLoyalty" class="collapse {{ request()->routeIs('loyalty-management.*') ? 'show' : '' }}"
             aria-labelledby="headingLoyalty" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">إدارة نقاط الولاء:</h6>
                <a class="collapse-item {{ request()->routeIs('loyalty-management.dashboard') ? 'active' : '' }}" href="{{ route('loyalty-management.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt text-gray-600"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('loyalty-management.users') ? 'active' : '' }}" href="{{ route('loyalty-management.users') }}">
                    <i class="fas fa-fw fa-users text-gray-600"></i>
                    <span>المستخدمين</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('loyalty-management.transactions') ? 'active' : '' }}" href="{{ route('loyalty-management.transactions') }}">
                    <i class="fas fa-fw fa-exchange-alt text-gray-600"></i>
                    <span>المعاملات</span>
                </a>
                <a class="collapse-item {{ request()->routeIs('loyalty-management.export') ? 'active' : '' }}" href="{{ route('loyalty-management.export') }}">
                    <i class="fas fa-fw fa-download text-gray-600"></i>
                    <span>تصدير التقارير</span>
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('shipping-proofs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('shipping-proofs.index') }}">
            <i class="fas fa-fw fa-shipping-fast"></i>
            @if (isset($pendingShippingProofsCount) && $pendingShippingProofsCount > 0)
                <span class="badge badge-danger badge-counter ml-2">{{ $pendingShippingProofsCount }}</span>
            @endif
            <span>طلبات الشحن والعملات</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('currencies.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('currencies.index') }}">
            <i class="fas fa-fw fa-coins"></i>
            <span>إدارة العملات</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('audit-logs.index') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>سجل التدقيق</span>
        </a>
    </li>

        @can('manage-roles')
        <li class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('roles.index') }}">
                <i class="fas fa-fw fa-user-shield"></i>
                <span>الأدوار والصلاحيات</span>
            </a>
        </li>
        @endcan

        {{-- @can('manage-backups') --}}
        <li class="nav-item {{ request()->routeIs('backups.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('backups.index') }}">
                <i class="fas fa-fw fa-database"></i>
                <span>النسخ الاحتياطية</span>
            </a>
        </li>
        {{-- @endcan --}}

        @can('manage-settings')
        <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>إعدادات الموقع</span>
            </a>
        </li>
        @endcan

        @can('manage-tickets')
        <li class="nav-item {{ request()->routeIs('help-center.*') || request()->routeIs('tickets.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('help-center.index') }}">
                <i class="fas fa-fw fa-headset"></i>
                <span>مركز المساعدة</span>
            </a>
        </li>
        @endcan

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
