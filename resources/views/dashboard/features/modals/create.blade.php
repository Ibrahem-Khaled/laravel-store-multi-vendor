<div class="modal fade" id="createFeatureModal" tabindex="-1" role="dialog" aria-labelledby="createFeatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFeatureModalLabel">إضافة ميزة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('features.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الميزة</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="form-text text-muted">مثال: تكييف, إنترنت, إفطار...</small>
                    </div>

                    <div class="form-group">
                        <label for="applicable_to">نوع الميزة</label>
                        <select class="form-control" id="applicable_to" name="applicable_to" required>
                            <option value="">اختر نوع الميزة</option>
                            <option value="residency">ميزة سكن</option>
                            <option value="hall">ميزة قاعة</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الميزة</button>
                </div>
            </form>
        </div>
    </div>
</div>
