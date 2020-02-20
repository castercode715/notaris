@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => 'fsk-service.store',
    'method'=> $method,
]) !!}

	<div class="row">

		<div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Category Service*</label>
                <select name="category_service" id="category_service" class="form-control dynamic3" data-table="mst_provinces" data-key="countries_id">
                    <option value="">- Silahkan Pilih -</option>
                    
	                    @foreach($categories as $row)
	                        <option value="{{ $row->id }}">{{ $row->name }}</option>
	                    @endforeach
	                
                </select>
            </div>
        </div>
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Service*</label>
		       	<select name="service" id="service" class="form-control select2" >
                    <option value="">- Silahkan Pilih -</option>
                </select>
		    </div>
		</div>

		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="email" class="control-label">Keterangan*</label>

		        	{!! Form::textarea('keterangan_service', $keterangan_service, ['class'=>'form-control', 'id'=>'keterangan_service']) !!}
		        
		    </div>
		</div>


	</div>

{!! Form::close() !!}

<script>

	$('.dynamic3').change(function(){

        if($(this).val() != ''){
            var value = $(this).val(),
                token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('fsk-service.fetch') }}",
                method : 'post',
                data : {
                    value : value,
                    _token : token
                },
                success : function(result){
                    $('#service').html(result);
                }
            });
        }
    });

    $(function () {
      $('.select2').select2()
    });
</script>