
@extends('base.main')
@section('title') Address @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Address @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/address" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

<script>
function pilih(){
		var a = document.getElementById("country").value;
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("province").innerHTML = this.responseText;
				
            }
        };
        xmlhttp.open("GET","/master/address/province/"+a,true);
        xmlhttp.send();

}

function regencynya(){
		var b = document.getElementById("regency").value;
		xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("data_regency").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","/master/address/regency/"+b,true);
        xmlhttp.send();
}

function districtnya(){
		var c = document.getElementById("district").value;
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("data_district").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","/master/address/district/"+c,true);
        xmlhttp.send();

}

function villagenya(){
		var c = document.getElementById("village").value;
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("data_village").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","/master/address/village/"+c,true);
        xmlhttp.send();

}


</script>



@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box box-solid" >
	
		<div class="col-md-12" style="background-color:#fff;">
	
        <form action="/master/address/post_address" method="post" >
            @csrf
            <div class="box-body">
                
				
				<div class="form-group">
                    <label for="name"> Country <span class="required">*</span></label>
                    <select name="country" id="country" class="form-control" id="country" onchange="pilih()">
					<option value=" "> Pilih Country </option>
					@foreach($country as $a)
						<option value="{{$a->id}}">{{$a->name}}</option>
					@endforeach;
					</select>
                </div>
				
				
				<div id="province"> </div>

				
				<div id="data_regency"> </div>
				
				
				<div id="data_district"></div>
				
				
				<div id="data_village"></div>
				
				<div class="box-footer" style="text-align:left;">
                    <input type="submit" value="Save" class="btn btn-primary">
                    <input type="button" value="Cancel" class="btn btn-primary" onclick="javascript:history.go(-1)">
                </div>
				
				
            </div>
        
		</form>		
	   </div>
		
    </div>
@endsection