<div class="modal fade" id="updateCoinsModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="updateCoinsModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="updateCoinsModalLabel{{ $user->id }}">تحديث عملات المستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('users.updateCoins', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="current-coins mb-4 text-center">
                        <h4>الرصيد الحالي: <span class="text-primary">{{ $user->coins }}</span> عملة</h4>
                    </div>

                    <div class="form-group">
                        <label for="coins_amount">المبلغ</label>
                        <input type="number" class="form-control" id="coins_amount" name="coins" min="0"
                            required>
                    </div>

                    <div class="form-group">
                        <label>نوع العملية</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="operation"
                                id="add_coins{{ $user->id }}" value="add" checked>
                            <label class="form-check-label" for="add_coins{{ $user->id }}">
                                <i class="fas fa-plus-circle text-success"></i> إضافة
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="operation"
                                id="subtract_coins{{ $user->id }}" value="subtract">
                            <label class="form-check-label" for="subtract_coins{{ $user->id }}">
                                <i class="fas fa-minus-circle text-danger"></i> خصم
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="operation"
                                id="set_coins{{ $user->id }}" value="set">
                            <label class="form-check-label" for="set_coins{{ $user->id }}">
                                <i class="fas fa-equals text-info"></i> تعيين
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">السبب (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تأكيد التحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>
