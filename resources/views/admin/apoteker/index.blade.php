@extends('admin/admin')
@section('content')
<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="card-title">Data Konsultasi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if (Session::has('message'))
                <div id="alert-msg" class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ Session::get('message') }}
                </div>
                @endif
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1 @endphp
                        @foreach($apoteker as $product)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $product['apoteker_nik'] }}</td>
                            <td>{{ $product['apoteker_name'] }}</td>
                            <td>{{ $product['apoteker_email'] }}</td>
                            <td>{{ $product['apoteker_address'] }}</td>
                            {{-- <td>
                                <form method="POST" action="{{ URL::to('/admin/product/'.$product['id']) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <div class="btn-group">
                                        <a class="btn btn-info"
                                            href="{{ URL::to('/admin/product/'.$product['id']) }}"><i
                                                class="fa fa-eye"></i></a>
                                        <a class="btn btn-success"
                                            href="{{ URL::to('/admin/product/'.$product['id'].'/edit') }}"><i
                                                class="fa fa-pencil"></i></a>
                                        <button type="submit" class="btn btn-danger"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                </form>
                            </td> --}}
                        </tr>
                        @endforeach
                        </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->


    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@section('script')
<!-- DataTables -->
<script src="{{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- page script -->
<script>
    $(function () {
        $('#example1').DataTable()
        $('#example2').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    })
</script>
@endsection

@endsection