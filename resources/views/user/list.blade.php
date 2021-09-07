@extends('paging.main')

    @section('content')
    @csrf
        <div class="col-md-10 row">
            <div class="col-md-7">
                <a href="/create{{$page_data["page_data_urlname"]}}" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Data"><i class="fas fa-plus text-white"></i></a> 
            </div>
            <div class="col-md-2 text-center">
                <a href="/chart{{$page_data["page_data_urlname"]}}total" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ucwords($page_data["page_data_urlname"])}} Total"><i class="fas fa-chart-area text-white"></i></a>
                <label><strong>User Total</strong></label>
            </div>
        </div>
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </tfoot>
        </table>

        <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Warning!!</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger row-delete-confirmed">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

@endsection