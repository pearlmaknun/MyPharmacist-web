@extends('admin/admin')
@section('content')
<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="card-title">Data Pesan</h3>
                {{-- <a class="btn btn-info" href="">Suspend Apoteker</a>
                <a class="btn btn-info" href="">Suspend Konseli</a> --}}
                <h5 class="box-title">Konseli: {{ $data[0]->konseli->user_name }}
                    {{-- @php echo $data['chat_id']; @endphp --}}</h5>
                <p></p>
                <h5 class="box-title">Apoteker: {{ $data[0]->apoteker->apoteker_name }}</h5>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {{-- <dl class="dl-horizontal">
                    <dt>
                        <p>Apoteker:</p>
                    </dt>
                    <dd>
                        <p></p>
                    </dd>
                </dl> --}}
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
                        @foreach($all_chats as $a)
                        <tr>
                            <td>{{ $no++ }}</td>
                            @if ($a['pengirim'] == $data[0]->konseli->user_id)
                            <td>Konseli</td>
                            @elseif ($a['pengirim'] == $data[0]->apoteker->apoteker_id)
                            <td>Apoteker</td>
                            @endif
                            <td>{{ $a['pesan'] }}</td>
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