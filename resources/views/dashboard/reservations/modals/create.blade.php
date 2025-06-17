<div class="modal fade" id="createReservationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة حجز جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="user_id">العميل</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">اختر العميل</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="product_id">المنتج</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                <option value="">اختر المنتج</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->price }}   د
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="type">نوع الحجز</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="daily">يومي</option>
                                <option value="hourly">ساعي</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="total_price">السعر الإجمالي</label>
                            <input type="number" step="0.01" name="total_price" id="total_price"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="start_time">تاريخ البدء</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="end_time">تاريخ الانتهاء</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الحجز</button>
                </div>
            </form>
        </div>
    </div>
</div>
