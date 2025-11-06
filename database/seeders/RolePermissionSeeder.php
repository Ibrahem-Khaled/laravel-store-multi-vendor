<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات
        $permissions = [
            // إدارة المستخدمين
            ['name' => 'manage-users', 'display_name' => 'إدارة المستخدمين', 'group' => 'users', 'description' => 'إمكانية إدارة المستخدمين (عرض، إضافة، تعديل، حذف)'],
            ['name' => 'view-users', 'display_name' => 'عرض المستخدمين', 'group' => 'users', 'description' => 'إمكانية عرض قائمة المستخدمين'],
            ['name' => 'create-users', 'display_name' => 'إضافة مستخدمين', 'group' => 'users', 'description' => 'إمكانية إضافة مستخدمين جدد'],
            ['name' => 'edit-users', 'display_name' => 'تعديل المستخدمين', 'group' => 'users', 'description' => 'إمكانية تعديل بيانات المستخدمين'],
            ['name' => 'delete-users', 'display_name' => 'حذف المستخدمين', 'group' => 'users', 'description' => 'إمكانية حذف المستخدمين'],

            // إدارة المنتجات
            ['name' => 'manage-products', 'display_name' => 'إدارة المنتجات', 'group' => 'products', 'description' => 'إمكانية إدارة المنتجات بالكامل'],
            ['name' => 'view-products', 'display_name' => 'عرض المنتجات', 'group' => 'products', 'description' => 'إمكانية عرض المنتجات'],
            ['name' => 'create-products', 'display_name' => 'إضافة منتجات', 'group' => 'products', 'description' => 'إمكانية إضافة منتجات جديدة'],
            ['name' => 'edit-products', 'display_name' => 'تعديل المنتجات', 'group' => 'products', 'description' => 'إمكانية تعديل المنتجات'],
            ['name' => 'delete-products', 'display_name' => 'حذف المنتجات', 'group' => 'products', 'description' => 'إمكانية حذف المنتجات'],

            // إدارة الطلبات
            ['name' => 'manage-orders', 'display_name' => 'إدارة الطلبات', 'group' => 'orders', 'description' => 'إمكانية إدارة الطلبات بالكامل'],
            ['name' => 'view-orders', 'display_name' => 'عرض الطلبات', 'group' => 'orders', 'description' => 'إمكانية عرض الطلبات'],
            ['name' => 'edit-orders', 'display_name' => 'تعديل الطلبات', 'group' => 'orders', 'description' => 'إمكانية تعديل حالة الطلبات'],
            ['name' => 'cancel-orders', 'display_name' => 'إلغاء الطلبات', 'group' => 'orders', 'description' => 'إمكانية إلغاء الطلبات'],

            // إدارة التجار
            ['name' => 'manage-merchants', 'display_name' => 'إدارة التجار', 'group' => 'merchants', 'description' => 'إمكانية إدارة التجار'],
            ['name' => 'view-merchants', 'display_name' => 'عرض التجار', 'group' => 'merchants', 'description' => 'إمكانية عرض قائمة التجار'],
            ['name' => 'settle-merchants', 'display_name' => 'تسوية دفعات التجار', 'group' => 'merchants', 'description' => 'إمكانية تسوية دفعات التجار'],

            // المحاسبة والمالية
            ['name' => 'manage-accounting', 'display_name' => 'إدارة المحاسبة', 'group' => 'accounting', 'description' => 'إمكانية إدارة الأمور المالية والمحاسبية'],
            ['name' => 'view-reports', 'display_name' => 'عرض التقارير المالية', 'group' => 'accounting', 'description' => 'إمكانية عرض التقارير المالية'],
            ['name' => 'manage-payments', 'display_name' => 'إدارة المدفوعات', 'group' => 'accounting', 'description' => 'إمكانية إدارة المدفوعات'],

            // إدارة التسويق
            ['name' => 'manage-marketing', 'display_name' => 'إدارة التسويق', 'group' => 'marketing', 'description' => 'إمكانية إدارة الحملات التسويقية'],
            ['name' => 'manage-promotions', 'display_name' => 'إدارة العروض الترويجية', 'group' => 'marketing', 'description' => 'إمكانية إدارة العروض والخصومات'],
            ['name' => 'view-analytics', 'display_name' => 'عرض التحليلات', 'group' => 'marketing', 'description' => 'إمكانية عرض إحصائيات التسويق'],

            // إدارة المحتوى
            ['name' => 'manage-content', 'display_name' => 'إدارة المحتوى', 'group' => 'content', 'description' => 'إمكانية إدارة محتوى الموقع'],
            ['name' => 'manage-categories', 'display_name' => 'إدارة التصنيفات', 'group' => 'content', 'description' => 'إمكانية إدارة التصنيفات والتصنيفات الفرعية'],
            ['name' => 'manage-slideshows', 'display_name' => 'إدارة السلايدشو', 'group' => 'content', 'description' => 'إمكانية إدارة السلايدشو'],

            // إدارة الأدوار والصلاح عليه
            ['name' => 'manage-roles', 'display_name' => 'إدارة الأدوار', 'group' => 'system', 'description' => 'إمكانية إدارة الأدوار والصلاحيات'],
            ['name' => 'manage-permissions', 'display_name' => 'إدارة الصلاحيات', 'group' => 'system', 'description' => 'إمكانية إدارة الصلاحيات'],

            // عرض سجل التدقيق
            ['name' => 'view-audit-logs', 'display_name' => 'عرض سجل التدقيق', 'group' => 'system', 'description' => 'إمكانية عرض سجل التدقيق'],

            // إدارة النسخ الاحتياطي
            ['name' => 'manage-backups', 'display_name' => 'إدارة النسخ الاحتياطي', 'group' => 'system', 'description' => 'إمكانية إنشاء واستعادة النسخ الاحتياطية'],
            ['name' => 'manage-settings', 'display_name' => 'إدارة الإعدادات', 'group' => 'system', 'description' => 'إمكانية تعديل إعدادات الموقع'],
            ['name' => 'manage-tickets', 'display_name' => 'إدارة التذاكر', 'group' => 'system', 'description' => 'إمكانية إدارة تذاكر الدعم الفني ومركز المساعدة'],

            // لوحة التحكم
            ['name' => 'access-dashboard', 'display_name' => 'الوصول للوحة التحكم', 'group' => 'system', 'description' => 'إمكانية الوصول إلى لوحة التحكم'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // إنشاء الأدوار
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'description' => 'مدير النظام - لديه جميع الصلاحيات',
                'permissions' => Permission::pluck('name')->toArray(), // جميع الصلاحيات
            ],
            [
                'name' => 'accountant',
                'display_name' => 'محاسب',
                'description' => 'محاسب النظام - يدير الأمور المالية والمحاسبية',
                'permissions' => [
                    'access-dashboard',
                    'view-orders',
                    'manage-accounting',
                    'view-reports',
                    'manage-payments',
                    'manage-merchants',
                    'view-merchants',
                    'settle-merchants',
                    'view-audit-logs',
                    'manage-backups',
                    'manage-settings',
                    'manage-tickets',
                ],
            ],
            [
                'name' => 'marketing-supervisor',
                'display_name' => 'مشرف مسوق',
                'description' => 'مشرف التسويق - يدير الحملات التسويقية والعروض',
                'permissions' => [
                    'access-dashboard',
                    'view-products',
                    'view-orders',
                    'manage-marketing',
                    'manage-promotions',
                    'view-analytics',
                    'manage-content',
                    'manage-slideshows',
                ],
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'description' => 'مشرف النظام - يدير المحتوى والمستخدمين',
                'permissions' => [
                    'access-dashboard',
                    'view-users',
                    'edit-users',
                    'manage-products',
                    'view-orders',
                    'edit-orders',
                    'manage-content',
                    'manage-categories',
                    'manage-slideshows',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'] ?? [];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // ربط الصلاحيات بالدور
            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();
            $role->permissions()->sync($permissionIds);
        }
    }
}

