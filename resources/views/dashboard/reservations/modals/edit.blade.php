<div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الحجز #{{ $reservation->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="user_id">العميل</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $user->id == $reservation->user_id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="product_id">المنتج</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product->id == $reservation->product_id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->price }}   د
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="type">نوع الحجز</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="daily" {{ $reservation->type === 'daily' ? 'selected' : '' }}>يومي
                                </option>
                                <option value="hourly" {{ $reservation->type === 'hourly' ? 'selected' : '' }}>ساعي
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="status">حالة الحجز</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active" {{ $reservation->status === 'active' ? 'selected' : '' }}>نشط
                                </option>
                                <option value="returned" {{ $reservation->status === 'returned' ? 'selected' : '' }}>تم
                                    الإرجاع</option>
                                <option value="partial_refund"
                                    {{ $reservation->status === 'partial_refund' ? 'selected' : '' }}>إرجاع جزئي
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="total_price">السعر الإجمالي</label>
                            <input type="number" step="0.01" name="total_price" id="total_price"
                                class="form-control" value="{{ $reservation->total_price }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="start_time">تاريخ البدء</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                                value="{{ $reservation->start_time->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="end_time">تاريخ الانتهاء</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-control"
                                value="{{ $reservation->end_time->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
