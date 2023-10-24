@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
                    <div class="mt-3">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tổng User</h5>
                                <p class="card-text"><i class="fa fa-user"></i></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tổng Số Link</h5>
                                <p class="card-text"><i class="fa fa-link"></i></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tổng Số Lần Click</h5>
                                <p class="card-text"><i class="fa fa-eye"></i></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header">Quản lý links</div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-borderless">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên User</th>
                                    <th>Đường dẫn</th>
                                    <th>Tổng click</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>

@endsection
