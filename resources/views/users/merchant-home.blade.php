@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'users'])

@section('content')
<style>

.sidebar .sidebar-wrapper>.nav [data-toggle="collapse"]~div>ul>li>a i, .sidebar .sidebar-wrapper .user .info [data-toggle="collapse"]~div>ul>li>a i, .off-canvas-sidebar .sidebar-wrapper>.nav [data-toggle="collapse"]~div>ul>li>a i, .off-canvas-sidebar .sidebar-wrapper .user .info [data-toggle="collapse"]~div>ul>li>a i {
  line-height: 32px;
}
.custom_color , .sorting_1 , table.dataTable.stripe tbody tr.odd, table.dataTable.display tbody tr.odd {
    background: #27293d !important;
}
.dataTables_wrapper .dataTables_length select {
    color: #fff !important;
}
.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
    color: #fff !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
    color: #e7e4e4 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button , .dataTables_wrapper .dataTables_filter input {
    color: #fff !important;
}
</style>



    <div class="content">
        <div class="row">
            @if(Session::has('message'))
                <p class="alert alert-success">{{ Session::get('message') }}</p>
            @endif
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="card-title">Merchants</h4>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{route('addMerchant')}}" class="btn btn-sm btn-primary">Add Merchant</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="">
                            <table class="table tablesorter " id="myTable">
                                <thead class=" text-primary">
                                <tr class="custom_color">
                                    <th scope="col">Merchant ID</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Store Name</th>
                                    <th scope="col">Merchantboost</th>
                                    <th scope="col">Maxboost</th>
                                    <th scope="col">latitude</th>
                                    <th scope="col">longitude</th>
                                    <th scope="col">Creation Date</th>
                                    <th scope="col">Action</th>
                                </tr></thead>
                                <tbody>
                                @foreach($data as $datum)

                                    <tr class="custom_color" >
                                    <td>{{$datum->merchant_name}}</td>
                                    <td>{{$datum->email}}</td>
                                    <td>{{$datum->password}}</td>
                                    <td>{{$datum->store_name}}</td>
                                    <td>{{$datum->merchant_maxboost}}</td>
                                    <td>{{$datum->maxboost_limit}}</td>


                                    <td>{{$datum->latitude}}</td>
                                    <td>{{$datum->longitude}}</td>
                                    <td>{{\Carbon\Carbon::parse($datum->created_at)->format('M-D-Y / H:I:S')}}</td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{route('editMerchant',[$datum->id])}}">Edit</a>
                                                 <a class="dropdown-item" href="{{route('merchantDelete',[$datum->id])}}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">

                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
