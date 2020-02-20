@if ($model->status == 'NEW')
<a href="{{ route('kpr.booking.survey', $model->id) }}" class="btn btn-primary modal-show" title="Assign Surveyor" style="margin: 10px 0;">Assign Surveyor</a>
<a href="{{ route('kpr.booking.cancel', $model->id) }}" class="btn bg-purple modal-show" title="Cancel Booking" style="margin: 10px 0;">Cancel Booking</a>
@endif
@if ($model->status == 'SURVEY')
<a href="{{ route('kpr.booking.approve-form', $model->id) }}" class="btn btn-success modal-show" id="btn-approve"
    style="margin: 10px 0;">Approve</a>
<a href="{{ route('kpr.booking.reject-form', $model->id) }}" class="btn btn-danger modal-show" id="btn-reject"
    style="margin: 10px 0;">Reject</a>
@endif
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Booking</a></li>
        <li><a href="#tab_2" data-toggle="tab">Surveyor</a></li>
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
                        <td>{{ date('d/m/Y H:i', strtotime($model->created_at)) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $booking->statusLabeled() !!}</td>
                    </tr>
                    <tr>
                        <th>Code</th>
                        <td>{{ $model->code }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $model->name }}</td>
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
        <div class="tab-pane" id="tab_2">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Surveyor</th>
                        <td>{{ $model->surveyor }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $model->surveyor_phone }}</td>
                    </tr>
                    <tr>
                        <th>Start date</th>
                        <td>{{ $model->survey_start_date ? date('d/m/Y', strtotime($model->survey_start_date)) : null }}
                        </td>
                    </tr>
                    <tr>
                        <th>End date</th>
                        <td>{{ $model->survey_end_date ? date('d/m/Y', strtotime($model->survey_end_date)) : null }}
                        </td>
                    </tr>
                    <tr>
                        <th>Reason/Note</th>
                        <td>{{ $model->note }}
                        </td>
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