<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    حذف مستخدم
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                </div>
                <p class="text-center">
                    هل أنت متأكد من حذف المستخدم <strong>{{ $user->name }}</strong>؟
                </p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>تحذير:</strong> سيتم حذف جميع بيانات المستخدم ولا يمكن التراجع عن هذا الإجراء.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
