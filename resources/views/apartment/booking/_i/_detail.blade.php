@if ($model->status == 'NEW')
<a href="{{ route('booking.interview.form', $model->id) }}" class="btn btn-warning modal-show" id="btn-interview" title="Interview" style="margin: 10px 0;">Invite to interview</a>
<a href="{{ route('booking.cancel.form', $model->id) }}" class="btn bg-purple modal-show" id="btn-cancel" title="Cancel Booking" style="margin: 10px 0;">Cancel Booking</a>
@endif
@if ($model->status == 'INTERVIEW')
<a href="{{ route('booking.approve.form', $model->id) }}" class="btn btn-success modal-show" id="btn-approve"
    style="margin: 10px 0;">Approve</a>
<a href="{{ route('booking.reject.form', $model->id) }}" class="btn btn-danger modal-show" id="btn-reject"
    style="margin: 10px 0;">Reject</a>
@endif
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Booking</a></li>
        <li><a href="#tab_3" data-toggle="tab">Contract</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Booking by</th>
                        <td>{{ $model->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Booking date</th>
                        <td>{{ date('d/m/Y H:i', strtotime($model->booking_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $booking->statusLabeled() !!}</td>
                    </tr>
                    <tr>
                        <th>Unit</th>
                        <td>{!! $model->name.' - '.$model->floor.'<br>'.$model->apartment !!}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $model->regency }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ number_format($model->price, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th>Tenor</th>
                        <td>{{ ($model->tenor / 12).' Years ('.$model->tenor.' Months)' }}</td>
                    </tr>
                    <tr>
                        <th>Installment</th>
                        <td>{{ number_format($model->installment, 0, '.', ',') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_3">
            <table class="table table-">

            </table>
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>