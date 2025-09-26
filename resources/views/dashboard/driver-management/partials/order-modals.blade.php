<!-- Confirm Delivery Modal -->
<div class="modal fade" id="confirmDeliveryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد التسليم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="confirmDeliveryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="أي ملاحظات إضافية حول التسليم..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تأكيد التسليم</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">سبب الإلغاء <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                                  placeholder="يرجى توضيح سبب إلغاء الطلب..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">إلغاء الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>
