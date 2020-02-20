@extends('base.main')
@section('title') Book @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Book @endsection
@section('page_subtitle') list @endsection
@section('menu')
<div class="box box-solid" style="text-align:right;">
    <div class="box-body">
        <a href="{{ route('book.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Create</a>
    </div>
</div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <table class="table table-bordered" id="table2">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Author</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($books))
                        @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->id }}</td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->year }}</td>
                                <td>{{ $book->price }}</td>
                                <td>
                                    <a href="{{ route('book.edit', $book->id) }}"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('book.destroy', $book->id) }}"><i class="fa fa-eraser"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No data :(</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- <script>
    $(document).on('click', '.btn-create', function(){
        $('#modal-create').modal('show');
    });

    $(document).on('click', '.btn-save', function(e){
        e.preventDefault();

        $.ajax({
            type : 'post',
            url : '/store',
            data : {
                '_token' : $('input[name=_token]').val(),
                'title' : $('input[name=title]').val(),
                'author' : $('input[name=author]').val(),
                'year' : $('input[name=year]').val(),
                'price' : $('input[name=price]').val()
            },
            success : {
                if (data.errors) {
                    $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                } else {
                    $('.error').remove();
                    $('.table').append("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
                }
            }
        });
    });

    $(document).on('click', '.btn-edit', function(){

    });
</script>

@include('book.create') --}}