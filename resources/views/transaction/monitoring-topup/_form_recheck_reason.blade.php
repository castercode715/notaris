<div class="modal fade" id="modal-recheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Recheck Reason</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body-recheck">
                    {!! Form::open(['route'=>'monitoring-topup.recheck', 'method'=>'post', 'id'=>'recheck-reason']) !!}
                        <input type="hidden" name="balance_in_id" id="balance_in_id_recheck" />

                        <div class="form-group">
                            <label for="reason" class="control-label">Reason*</label>
                            {!! Form::textarea('recheck_reason', null, ['class'=>'form-control', 'id'=>'recheck_reason', 'rows'=>3]) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn bg-orange" id="modal-save-recheck">Reject</button>
            </div>
        </div>
    </div>
</div>