<div class="box-body">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Code</th>
                            <td>{{ $model->code }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $model->title }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $model->type }}</td>
                        </tr>
                        <tr>
                            <th>Asset</th>
                            <td>{{ $model->asset_name }} <a href="{{ route('asset.show', base64_encode($model->asset_id)) }}" target="_blank"><i class="fa fa-external-link"></i></a></td>
                        </tr>
                        <tr>
                            <th>Asset Owner Name</th>
                            <td>{{ $model->owner_name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($model->status == 'DRAFT')
                                    <span class="label label-warning">{{ $model->status }}</span>
                                @elseif($model->status == 'PUBLISHED')
                                    <span class="label label-primary">{{ $model->status }}</span>
                                @elseif($model->status == 'CANCELED')
                                    <span class="label label-danger">{{ $model->status }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Value</th>
                            <td>{{ $model->value_type == 'PRECENTAGE' ? $model->value.'%' : 'Rp.'.number_format($model->value) }}</td>
                        </tr>
                        <tr>
                            <th>Quota</th>
                            <td>{!! '<i>Rem.: </i>'.$model->remain_quota.'. <i>Of:</i> '.$model->quota !!}</td>
                        </tr>
                        <tr>
                            <th>Tome of use</th>
                            <td>{!! $model->time_of_use !!}</td>
                        </tr>
                        <tr>
                            <th>Min. Invest Amount</th>
                            <td>{{ 'Rp.'.number_format($model->min_invest_amount) }}</td>
                        </tr>
                        <tr>
                            <th>Date Start</th>
                            <td>{{ date('d/m/Y', strtotime($model->date_start)) }}</td>
                        </tr>
                        <tr>
                            <th>Date End</th>
                            <td>{{ date('d/m/Y', strtotime($model->date_end)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">
    <!-- start accordion -->
    <div class="box-group" id="accordion">
        <div class="panel box box-solid">
            <div class="box-header with-border">
                <h4 class="box-title"><strong>
                    <a href="#collapse1" data-toggle="collapse" data-parent="#accordion">Indonesia</a>
                </strong></h4>
                <div class="box-tools pull-right">
                    <a href="{{ route('vouchers.edit-new', [base64_encode($model->voucher_lang_id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">
                <div class="box-body">
                    <h3><strong>{{ $model->title }}</strong></h3>
                    {!! html_entity_decode($model->desc) !!}
                    <hr>
                    <div class="row">
                        @if($model->image != '')
                        <div class="col-md-6">
                            <img src="/images/voucher/{{ $model->image }}" class="img-responsive">
                        </div>
                        @endif
                        @if($model->iframe != '')
                        <div class="col-md-6">
                        	{{ $model->iframe }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php $no = 2; @endphp
        @foreach($language as $l)
        @php 
        $data = $voucher->getData($l->code);
        @endphp
        <div class="panel box box-solid">
            <div class="box-header with-border">
                <h4 class="box-title"><strong>
                    <a href="#collapse{{ $no }}" data-toggle="collapse" data-parent="#accordion">{!! ucwords(strtolower($l->language)) !!}</a>
                </strong></h4>
                @if($data)
                <div class="box-tools pull-right">
                    <a href="{{ route('vouchers.edit-new', [base64_encode($data->id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                </div>
                @endif
            </div>
            <div id="collapse{{ $no }}" class="panel-collapse collapse">
                <div class="box-body">
                    @if($data)
                    <h3><strong>{{ $data->title }}</strong></h3>
                    {!! html_entity_decode($data->desc) !!}
                    <hr>
                    <div class="row">
                        @if($data->image != '')
                        <div class="col-md-6">
                            <img src="/images/voucher/{{ $data->image }}" class="img-responsive">
                        </div>
                        @endif
                        @if($data->iframe != '')
                        <div class="col-md-6">
                        	{!! $data->iframe !!}
                        </div>
                        @endif
                    </div>
                    @else
                    <p align="center">Please add desc using this language</p>
                    <p align="center"><a href="{{ route('vouchers.create-new',[ base64_encode($model->id), $l->code]) }}" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add desc</a></p>
                    @endif
                </div>
            </div>
        </div>
        @php $no++; @endphp
        @endforeach
    </div>
    <!-- end accordion -->
</div>

