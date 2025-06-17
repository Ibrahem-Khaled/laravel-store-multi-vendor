<div class="modal fade" id="deleteReservationModal{{ $reservation->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف الحجز الخاص بـ <strong>{{ $reservation->user->name }}</strong> للمنتج
                    <strong>{{ $reservation->product->name }}</strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف الحجز</button>
                </form>
            </div>
        </div>
    </div>
</div>
