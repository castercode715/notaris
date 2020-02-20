<form action="{{ route('booking.interview', $id) }}" method="post">
    @csrf
    <input type="hidden" name="_method" value="put">
    <div class="form-group">
        <label for="invite_date">Meet up date*</label>
        <input type="datetime-local" name="invite_date" id="invite_date" class="form-control">
        @if($errors->has('invite_date'))
        <span class="invalid-feedback" role="alert">
            {{ $errors->first('invite_date') }}
        </span>
        @endif
    </div>
    <div class="form-group">
        <label for="place">Place*</label>
        <input type="text" name="place" id="place" class="form-control">
        @if($errors->has('place'))
        <span class="invalid-feedback" role="alert">
            {{ $errors->first('place') }}
        </span>
        @endif
    </div>
</form>