<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الادارات
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>المستخدمين</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>التصنيفات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sub-categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>التصنيفات الفرعية</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('brands.index') }}">
            <i class="fas fa-fw fa-stamp"></i>
            <span>العلامات التجارية</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('cities.index') }}">
            <i class="fas fa-fw fa-city"></i>
            <span>المدن</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('neighborhoods.index') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>الاحياء</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('slide-shows.index') }}">
            <i class="fas fa-fw fa-images"></i>
            <span>
                السلايدشو</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('features.index') }}">
            <i class="fas fa-fw fa-gem"></i>
            <span>الميزات الرئيسية للمنتجات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>المنتجات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('reviews.index') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>التقييمات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('notifications.index') }}">
            <i class="fas fa-fw fa-bell"></i>
            <span>الاشعارات</span></a>
    </li>




    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
