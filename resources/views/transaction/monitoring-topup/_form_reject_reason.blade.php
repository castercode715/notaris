<div class="modal fade" id="modal-reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Reject Reason</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body-reject">
                    {!! Form::open(['route'=>'monitoring-topup.reject', 'method'=>'post', 'id'=>'reject-reason']) !!}
                        <input type="hidden" name="balance_in_id" id="balance_in_id_reject" />

                        <div class="form-group">
                            <label for="reason" class="control-label">Reason*</label>
                            {!! Form::textarea('reject_reason', null, ['class'=>'form-control', 'id'=>'reject_reason', 'rows'=>3]) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" id="modal-save-reject">Reject</button>
            </div>
        </div>
    </div>
</div>