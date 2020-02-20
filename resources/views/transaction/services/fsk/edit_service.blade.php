@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => ['fsk-service.update', $model->id],
    'method'=> $method,
]) !!}

	<div class="row">

		<div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Category Service*</label>
                <select name="category_service_edit" id="category_service_edit" class="form-control dynamic3" data-table="mst_provinces" data-key="countries_id">
                    <option value="">- Silahkan Pilih -</option>
                        
	                    @foreach($categories as $row)
	                        <option value="{{ $row->id }}" {{ $category->category_id == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
	                    @endforeach
	                
                </select>
            </div>
        </div>
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Service*</label>
		       	<select name="service_id" id="service_id" class="form-control select2" >
                    <option value="">- Silahkan Pilih -</option>
                    @foreach($services as $result)
                        <option value="{{ $result->id }}" {{ $result->id == $service_id ? 'selected' : '' }}>{{ $result->name }}</option>
                    @endforeach
                </select>
		    </div>
		</div>

		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="email" class="control-label">Keterangan*</label>

		        	{!! Form::textarea('keterangan_service_edit', $keterangan_service, ['class'=>'form-control', 'id'=>'keterangan_service_edit']) !!}
		        
		    </div>
		</div>

        <input type="hidden" name="" id="id_penting" value="{{ $id }}">
        <input type="hidden" name="" id="name_service_penting" >


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
                    $('#service_id').html(result);
                    $('#name_service_penting').val()
                }
            });
        }
    });

    $(function () {
      $('.select2').select2()
    });

    $('#modal-btn-save-service-edit').click(function(e){
    

        if ($('#category_service_edit').val().trim() == '') {
            // alert('test');
            $('#category_service_edit').parent().closest('.form-group')
                            .addClass('has-error')
                            .append('<span class="help-block">Silahkan pilih kategori service !</span>'); 
            
        }else if($('#service_id').val().trim() == ''){

            $('#service_id').parent().closest('.form-group')
                            .addClass('has-error')
                            .append('<span class="help-block">Silahkan pilih service !</span>');

        }else{

            var url = "{{ route('fsk-service.getServiceName', ':id'  )}}";
                    url = url.replace(':id', $('#service_id').val());

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'html',
                success: function(response)
                {

                    // alert(response);
                    
                    var id = $('#id_penting').val();

                    $('#serv_' + id).val($('#service_id').val());
                    $('#ket_' + id).val($('#keterangan_service_edit').val());
                    $('#servic_' + id).text(response);

                    $('#asatu_' + id).data('service', response);
                    $('#asatu_' + id).data('keterangan', $('#keterangan_service_edit').val());

                    $('#adua_' + id).data('service_id', $('#service_id').val());
                    $('#adua_' + id).data('keterangan', $('#keterangan_service_edit').val());

                    $('#modal-service-edit').modal('hide');

                },
                error : function(error)
                {
                    $('#modal-service-edit').modal('hide');
                    swal({
                        type : 'error',
                        title : 'Error 401',
                        text : 'Unauthorized action'
                    });
                }
            });


            


        }
    });


</script>