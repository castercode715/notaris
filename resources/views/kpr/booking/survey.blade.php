<div class="modal-body">
    <form action="{{ route('kpr.booking.assign', $model->id) }}" method="post" id="form-assign">
        @csrf
        <input type="hidden" name="_method" value="put">
        <div class="form-group">
            <label for="surveyor">Surveyor*</label>
            <input type="text" name="surveyor" id="surveyor" class="form-control">
            @if($errors->has('surveyor'))
            <span class="invalid-feedback" role="alert">
                {{ $errors->first('surveyor') }}
            </span>
            @endif
        </div>
        <div class="form-group">
            <label for="phone">Phone*</label>
            <input type="text" name="phone" id="phone" class="form-control">
            @if($errors->has('phone'))
            <span class="invalid-feedback" role="alert">
                {{ $errors->first('phone') }}
            </span>
            @endif
        </div>
        <div class="form-group">
            <label for="start_date">Start date*</label>
            <input type="date" name="start_date" id="start_date" class="form-control datepicker">
            @if($errors->has('start_date'))
            <span class="invalid-feedback" role="alert">
                {{ $errors->first('start_date') }}
            </span>
            @endif
        </div>
    </form>
</div>