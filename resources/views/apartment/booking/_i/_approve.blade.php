<form action="{{ route('booking.approve', $id) }}" method="post">
    @csrf
    <input type="hidden" name="_method" value="put">
    <div class="form-group">
        <label for="note">Note*</label>
        <textarea name="note" id="note" cols="30" rows="10" class="form-control"></textarea>
        @if($errors->has('note'))
        <span class="invalid-feedback" role="alert">
            {{ $errors->first('note') }}
        </span>
        @endif
    </div>
</form>