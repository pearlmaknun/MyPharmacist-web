@extends('admin/admin')
@section('content')
<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="card-title">Informasi Apoteker</h3>
                {{-- <h6 class="box-title">Nama: {{ $data[0]->apoteker_name }}</h6><br/>
                <h6 class="box-title">Nama: {{ $data[0]->apoteker_stra }}</h6><br/>
                <h6 class="box-title">Nama: {{ $data[0]->apoteker_sipa1 }}</h6><br/>
                <h6 class="box-title">Nama: {{ $data[0]->apoteker_sipa2 }}</h6><br/>
                <h6 class="box-title">Nama: {{ $data[0]->apoteker_sipa3 }}</h6><br/> --}}
                
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
                    {{-- <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengirim</th>
                            <th>Pesan</th>
                        </tr>
                    </thead> --}}
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>{{ $data[0]->apoteker_name }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Surat Tanda Registrasi Apoteker</td>
                            <td>{{ $data[0]->apoteker_stra }}</td>
                        </tr>
                        <tr>
                            <td>Nomor HP</td>
                            <td>{{ $data[0]->apoteker_number }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $data[0]->apoteker_address }}</td>
                        </tr>
                        <tr>
                            <td>Nomor SIPA I</td>
                            <td>{{ $data[0]->apoteker_sipa1 }}</td>
                        </tr>
                        <tr>
                            <td>Apotek SIPA I</td>
                            <td>{{ $apotek[0]->apotik_name }}</td>
                        </tr>
                        <tr>
                            <td>Nomor SIPA II</td>
                            @if ($data[0]->apoteker_sipa2 != null)
                            <td>{{ $data[0]->apoteker_sipa2 }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Apotek SIPA II</td>
                            @if ($data[0]->apoteker_sipa2 != null)
                            <td>{{ $apotek[1]->apotik_name }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Nomor SIPA III</td>
                            @if ($data[0]->apoteker_sipa3 != null)
                            <td>{{ $data[0]->apoteker_sipa3 }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Apotek SIPA III</td>
                            @if ($data[0]->apoteker_sipa3 != null)
                            <td>{{ $apotek[2]->apotik_name }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        </tfoot>
                </table>
                <div class="row">
                    <div class="col-sm-1">
                        <form action="/admin/apoteker/update" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $data[0]->apoteker_id }}"> <br/>
                            <input type="hidden" name="status" value="1"> <br/>
                            <input type="submit" class="btn btn-primary" value="Diterima">
                        </form>
                    </div>
                    <div class="col-sm-1">
                        <form action="/admin/apoteker/update" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $data[0]->apoteker_id }}"> <br/>
                            <input type="hidden" name="status" value="2"> <br/>
                            <input type="submit" class="btn btn-danger" value="Ditolak">
                        </form>
                    </div>
                  </div> 
                                     
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