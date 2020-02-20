<form action="{{ route('kpr.booking.cancelling', $id) }}" method="post">
    @csrf
    <input type="hidden" name="_method" value="put">
    <div class="form-group">
        <label for="reason">Reason*</label>
        <textarea name="reason" id="reason" cols="30" rows="10" class="form-control"></textarea>
        @if($errors->has('reason'))
        <span class="invalid-feedback" role="alert">
            {{ $errors->first('reason') }}
        </span>
        @endif
    </div>
</form>