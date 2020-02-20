<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
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
                                            <th width="30%">Code APT</th>
                                            <td>{{ $model->code_apt }}</td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Status</th>
                                            <td>
                                                @if($model->status == "ACTIVE")
                                                    <span class="badge bg-green">ACTIVE</span>
                                                @elseif($model->status == "INACTIVE")
                                                    <span class="badge bg-red">INACTIVE</span>
                                                @else
                                                    <span class="badge bg-white">UNKNOWN</span>
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
                                <div class="col-md-6">
                                    <table class="table">
                                        <tbody>
                                            
                                            <tr>
                                                <th width="30%">Price</th>
                                                <td>{{ number_format($model->price) }} IDR</td>
                                            </tr>
                                           
                                            <tr>
                                                <th width="30%">Installment</th>
                                                <td>{{ number_format($model->installment) }} IDR</td>
                                            </tr>

                                            <tr>
                                                <th width="30%">Maintenance</th>
                                                <td>{{ number_format($model->maintenance) }} IDR</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table">
                                        <tbody>
                                            
                                            <tr>
                                                <th width="30%">Tenor/Month</th>
                                                <td>{{ $model->tenor }} Month</td>
                                            </tr>

                                            <tr>
                                                <th width="30%">Tenor/Year</th>
                                                <td>{{ $tahun }} Year</td>
                                            </tr>
                                           
                                            <tr>
                                                <th width="30%">Type APT</th>
                                                <td>
                                                    @if($model->type_apt == "STUDIO 21")
                                                        <span class="label label-default">STUDIO 21</span>
                                                    @elseif($model->type_apt == "STUDIO 24")
                                                        <span class="label label-warning">STUDIO 24</span>
                                                    @else
                                                        <span class="badge bg-white">UNKNOWN</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                
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
                                    @foreach($featured as $img)

                                        @if($featured->count() == 0)
                                            <img class="img-responsive" src="{{ asset('no-image.jpg') }}" alt="Photo" style="width: 635px; height: 340px;">
                                        @else
                                            <img class="img-responsive" src="{{ asset($img->image) }}" alt="Photo" style="width: 635px; height: 340px;">
                                        @endif

                                    @endforeach

                                    
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
                                    
                                    @if($other->count() == 0)
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

                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-file"></i> Another File</h3>
                            </div>
                            <div class="box-body">
                                <div class="timeline-body">
                                    <ul class="mailbox-attachments clearfix">

                                        @if($model->file == null)
                                            <img class="img-responsive" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQUK5xs4LSBpIQcaerR-n3yyZxzweiOGb2wRqk96_BboEs4isGr" alt="Photo" style="width: 635px; height: 340px;">
                                        @else
                                            <li style="width: 100% !important">
                                                <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                                                <div class="mailbox-attachment-info">
                                                    <a href="{{ asset($model->file) }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ substr($model->file, 22) }}</a>
                                                        <span class="mailbox-attachment-size">
                                                          1,245 KB
                                                            <a href="{{ asset($model->file) }}" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                                        </span>
                                                </div>
                                            </li>
                                        @endif 
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    
</div>