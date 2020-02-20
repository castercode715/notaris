<form action="{{ route('kpr.booking.approve', $id) }}" method="post">
    @csrf
    <input type="hidden" name="_method" value="put">

    <div class="form-group">
        <label for="survey_end_date">Survey end date*</label>
        <div class="input-group mb-3">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
            <input type="date" name="survey_end_date" id="survey_end_date" class="form-control">
            @if($errors->has('survey_end_date'))
            <span class="invalid-feedback" role="alert">
                {{ $errors->first('survey_end_date') }}
            </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="reason">Reason*</label>
        <textarea name="reason" id="reason" class="form-control" cols="30" rows="10"></textarea>
        @if($errors->has('reason'))
        <span class="invalid-feedback" role="alert">
            {{ $errors->first('reason') }}
        </span>
        @endif
    </div>

    <div class="form-group">
        <label for="installment_start_date">Installment start date*</label>
        <div class="input-group mb-3">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
            <input type="date" name="installment_start_date" id="installment_start_date" class="form-control">
            @if($errors->has('installment_start_date'))
            <span class="invalid-feedback" role="alert">
                {{ $errors->first('installment_start_date') }}
            </span>
            @endif
        </div>
    </div>

</form>