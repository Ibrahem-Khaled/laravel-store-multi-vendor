<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل مستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الاسم</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الهاتف</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>الدور</label>
                            <select name="role" class="form-control" required>
                                @foreach (['user' => 'مستخدم', 'trader' => 'متداول', 'moderator' => 'مشرف', 'admin' => 'مدير'] as $val => $label)
                                    <option value="{{ $val }}" @selected($user->role === $val)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الحالة</label>
                            <select name="status" class="form-control" required>
                                @foreach (['pending' => 'قيد الاعتماد', 'active' => 'نشط', 'inactive' => 'غير نشط', 'banned' => 'محظور'] as $val => $label)
                                    <option value="{{ $val }}" @selected($user->status === $val)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>الرصيد (Coins)</label>
                            <input type="number" min="0" name="coins" class="form-control"
                                value="{{ $user->coins }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>الصورة الشخصية</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="editAvatar{{ $user->id }}"
                                name="avatar">
                            <label class="custom-file-label" for="editAvatar{{ $user->id }}">اختر ملف</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إ取消</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
