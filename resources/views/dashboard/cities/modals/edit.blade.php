<div class="modal fade" id="editCityModal{{ $city->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editCityModalLabel{{ $city->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCityModalLabel{{ $city->id }}">تعديل المدينة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cities.update', $city->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم المدينة *</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $city->name }}" required>
                        <small class="form-text text-muted">يجب أن يكون اسم المدينة فريداً</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
