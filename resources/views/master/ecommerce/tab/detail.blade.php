<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-cube"></i>
                <h3 class="box-title">{{ $model->name }}</h3>
            </div>

            <div class="box-body">
                <blockquote>
                    <p><b>{{ $model->name }}</b></p>
                    <small>Description <cite title="Source Title">Product Name </cite></small>
                    <p>{!! html_entity_decode($model->desc) !!}</p>
                </blockquote>

                <blockquote>
                    <small>Term Conditions <cite title="Source Title">Product </cite></small>
                    <p>{!! html_entity_decode($model->term_conds) !!}</p>
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
                                            <th width="30%">Product Name</th>
                                            <td>{{ $model->name }}</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Price</th>
                                            <td>{{ number_format($model->price) }} IDR</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Discount</th>
                                            <td>{{ $model->discount }} %</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Total Price</th>
                                            <td>{{ number_format($total_price) }} IDR</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Tenor/Month</th>
                                            <td>
                                                @php 
                                                    $no = 0;
                                                    $max = count($tenor);
                                                    $cc = $max--;
                                                @endphp

                                                @foreach($tenor as $key)
                                                    @php 

                                                        echo $value = $key->tenor." Month";
                                                        if($no < $max){
                                                            echo $value = ", ";
                                                        }
                                                        $no++;
                                                    
                                                    @endphp
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Bunga</th>
                                            <td>
                                                @php 
                                                    $no = 0;
                                                    $max = count($tenor);
                                                    $cc = $max--;
                                                @endphp

                                                @foreach($tenor as $key)
                                                    @php 

                                                        echo $value = $key->bunga."% ";
                                                        if($no < $max){
                                                            echo $value = ", ";
                                                        }
                                                        $no++;
                                                    
                                                    @endphp
                                                @endforeach
                                            </td>
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
                                            <th width="30%">Created By</th>
                                            <td>{{ $uti->getUser($model->created_by) }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th width="30%">Created At</th>
                                            <td>{{ date('d M Y H:i', strtotime($model->created_at)) }} WIB</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Updated By</th>
                                            <td>{{ $uti->getUser($model->updated_by) }}</td>
                                        </tr>

                                        <tr>
                                            <th width="30%">Updated At</th>
                                            <td>{{ date('d M Y H:i', strtotime($model->updated_at)) }} WIB</td>
                                        </tr>
                                    
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-bookmark"></i> Attributes</h3>
                            </div>
                            <div class="box-body">
                                
                                    <table class="table">
                                        <tbody>
                                            <th>Category</th>
                                            <td>
                                                @php 
                                                    $no = 0;
                                                    $max = count($category);
                                                    $cc = $max--;
                                                @endphp

                                                @foreach($category as $key)
                                                    @php 

                                                        echo $value = $key->name;
                                                        if($no < $max){
                                                            echo $value = ", ";
                                                        }
                                                        $no++;
                                                    
                                                    @endphp
                                                @endforeach
                                            </td>

                                            @foreach($attributeValue as $key)
                                                <tr>
                                                    <th width="30%">{{ $key->name }}</th>
                                                    <td>
                                                        {{ $model->getValueAttributeProduct($key->id, $model->id) }}
                                                    </td>
                                                </tr>
                                            @endforeach
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
                                    @foreach($featuredImg as $img)

                                        @if($featuredImg->count() == 0)
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
                                    
                                    @if($otherImg->count() == 0)
                                        <img class="img-responsive" src="{{ asset('no-image.jpg') }}" alt="Photo" style="width: 635px; height: 340px;">
                                    @else
                                        @foreach($otherImg as $img)
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