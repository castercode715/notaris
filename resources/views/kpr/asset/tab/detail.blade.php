
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-bullhorn"></i>
                <h3 class="box-title">Detail Asset {{ $model->name }}</h3>
            </div>

            <div class="box-body">
                <blockquote>
                    <p><b>{{ $model->name }}</b></p>
                    <small>Description <cite title="Source Title">Asset </cite></small>
                    <p>{!! html_entity_decode($model->description) !!}</p>
                </blockquote>

                <blockquote>
                    <small>Term Conditions <cite title="Source Title">Asset </cite></small>
                    <p>{!! html_entity_decode($model->term_cond) !!}</p>
                </blockquote>

                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-list"></i> More Detail</h3>
                            </div>
                            <div class="box-body">
                                <table class="table">
                                    <tbody>

                                        <tr>
                                            <th width="30%">Code</th>
                                            <td>{{ $model->code }}</td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Status</th>
                                            <td>
                                                @if($model->status == "D")
                                                    <span class="badge bg-yellow">Draft</span>
                                                @elseif($model->status == "P")
                                                    <span class="badge bg-green">Published</span>
                                                @elseif($model->status == "U")
                                                    <span class="badge bg-red">Un Published</span>
                                                @elseif($model->status == "B")
                                                    <span class="badge bg-blue">Booked</span>
                                                @else
                                                    <span class="badge bg-white">Unknown</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Location</th>
                                            <td>{{ $location->location }}</td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Created At</th>
                                            <td>{{ date('d M Y H:i', strtotime($model->created_at)) }} WIB</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-dollar"></i> Price</h3>
                            </div>
                            <div class="box-body">
                                <table class="table">
                                    <tbody>
                                        
                                        <tr>
                                            <th width="30%">Price</th>
                                            <td>{{ number_format($model->price) }} IDR</td>
                                        </tr>
                                       
                                        <tr>
                                            <th width="30%">Tenor/Month</th>
                                            <td>{{ $model->tenor }} Month</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Tenor/Year</th>
                                            <td>{{ $tahun }} Year</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Installment</th>
                                            <td>{{ number_format($model->installment) }} IDR</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-photo"></i> Featured Image</h3>
                            </div>
                            <div class="box-body">
                                <table class="table">
                                    <tbody>
                                        @if($featuredCount == 0)
                                            <img class="img-responsive" src="{{ asset('no-image.jpg') }}" alt="Photo" style="width: 635px; height: 340px;">
                                        @else
                                            <img class="img-responsive" src="{{ asset($featured->image) }}" alt="Photo" style="width: 635px; height: 340px;">
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-photo"></i> Other Image</h3>
                            </div>
                            <div class="box-body">
                                <div class="timeline-body">
                                    @if($otherCount == 0)
                                        <img class="img-responsive" src="{{ asset('no-image.jpg') }}" alt="Photo" style="width: 635px; height: 340px;">
                                    @else
                                        @foreach($other as $img)
                                            <img src="{{ asset($img->image) }}" alt="..." class="margin" style="width: 189px; height: 150px;">
                                        @endforeach
                                    @endif  
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    
</div>
