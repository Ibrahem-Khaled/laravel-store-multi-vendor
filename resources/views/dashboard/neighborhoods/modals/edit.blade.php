<div class="modal fade" id="editNeighborhoodModal{{ $neighborhood->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editNeighborhoodModalLabel{{ $neighborhood->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNeighborhoodModalLabel{{ $neighborhood->id }}">تعديل بيانات الحي</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('neighborhoods.update', $neighborhood->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الحي</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $neighborhood->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="city_id">المدينة</label>
                        <select class="form-control" id="city_id" name="city_id" required>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ $neighborhood->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="active" name="active"
                                value="1" {{ $neighborhood->active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="active">الحالة (نشط/غير نشط)</label>
                        </div>
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
