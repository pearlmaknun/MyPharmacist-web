@extends('admin/admin')
@section('content')
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="card-title">Import Apoteker</h3>

        {{-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div> --}}
    </div>
    <!-- /.box-header -->

    <div class="box-body">

        {{-- notifikasi form validasi --}}
		@if ($errors->has('file'))
		{{-- <span class="invalid-feedback" role="alert">
			<strong>{{ $errors->first('file') }}</strong>
        </span> --}}
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $errors->first('file') }}</strong>
        </div>
		@endif
 
		{{-- notifikasi sukses --}}
		@if ($sukses = Session::get('sukses'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button> 
			<strong>{{ $sukses }} <a href="{{URL::to('admin/apoteker')}}">Lihat Disini</a></strong>
		</div>
		@endif

        {{-- <div class="form-group">
            <button type="submit" class="btn btn-success">Download Template</button>

            <p class="help-block">Harap mengimport menggunakan Template diatas.</p>
        </div> --}}
        <p class="help-block">Perhatikan!</p>
        <ol>
            <li>Tipe File .xlxs</li>
            <li>Sesuai Template yang disediakan di atas.</li>
        </ol>
        <form role="form" method="post" action="{{ route('import.import') }}" name="importform" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputFile">File input</label>
                {{ csrf_field() }}
                <input type="file" name="file" required="required">
            </div>
            <button type="submit" class="btn btn-warning">Import Excel</button>

        </form>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
</div>
@endsection