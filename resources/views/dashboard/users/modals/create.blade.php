<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الهاتف</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>الدور (القديم)</label>
                            <select name="role" class="form-control" required>
                                <option value="user">مستخدم</option>
                                <option value="trader">متداول</option>
                                <option value="moderator">مشرف</option>
                                <option value="admin">مدير</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>الأدوار (الحديثة) <small class="text-muted">يمكن اختيار عدة أدوار</small></label>
                        <div class="row">
                            @php
                                try {
                                    $dbRoles = \App\Models\Role::active()->orderBy('order')->get();
                                } catch (\Exception $e) {
                                    $dbRoles = collect();
                                }
                            @endphp
                            @if($dbRoles && $dbRoles->isNotEmpty())
                                <div class="col-md-12">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            @foreach($dbRoles as $role)
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="role_ids[]" 
                                                           class="form-check-input" 
                                                           value="{{ $role->id }}" 
                                                           id="create_role_{{ $role->id }}">
                                                    <label class="form-check-label" for="create_role_{{ $role->id }}">
                                                        <strong>{{ $role->display_name }}</strong>
                                                        @if($role->description)
                                                            <br><small class="text-muted">{{ $role->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <p class="text-muted">لا توجد أدوار متاحة حالياً. قم بتشغيل Seeder أولاً.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>الصورة الشخصية</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="createAvatar" name="avatar">
                            <label class="custom-file-label" for="createAvatar">اختر ملف</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_verified" class="form-check-input" id="createIsVerified" value="1">
                            <label class="form-check-label" for="createIsVerified">
                                <strong>حساب موثق</strong>
                                <small class="text-muted d-block">تفعيل التحقق من الحساب</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
