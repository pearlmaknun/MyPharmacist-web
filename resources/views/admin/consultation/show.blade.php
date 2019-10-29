@extends('admin/admin')
@section('content')
<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="card-title">Data Pesan</h3>
                <h5 class="box-title">Konseli Terkait: </h5><p></p>
                <h5 class="box-title">Apoteker Terkait: </h5>
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
                            <th>Pengirim</th>
                            <th>Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1 @endphp
                        @foreach($all_chats as $product)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $product['pengirim'] }}</td>
                            <td>{{ $product['pesan'] }}</td>
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