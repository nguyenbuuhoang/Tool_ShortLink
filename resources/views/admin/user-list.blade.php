@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Danh sách user</div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên User</th>
                                        <th>Email</th>
                                        <th>Quyền</th>
                                        <th>Tổng URL</th>
                                        <th>Ngày đăng ký</th>
                                        <th>Trạng thái</th>
                                        <th>Hành Động</th>
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
        $(document).ready(function() {
            const tableBody = $('tbody');

            function formatDateString(dateString) {
                const createdDate = new Date(dateString);
                const formattedDate =
                    `${createdDate.getDate()}/${createdDate.getMonth() + 1}/${createdDate.getFullYear()}`;
                return formattedDate;
            }

            function createUserRow(user) {
                const formattedDate = formatDateString(user.created_at);
                const isVerified = user.is_verified ? 'Đã xác thực' : 'Chưa xác thực';
                const role = user.role.join(', ');

                const row = `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${role}</td>
                    <td>${user.total_urls}</td>
                    <td>${formattedDate}</td>
                    <td>${isVerified}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-gear"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="actionMenu">
                                <a class="dropdown-item edit-action" href="#">Sửa</a>
                                <a class="dropdown-item delete-action" href="#">Xóa</a>
                            </div>
                        </div>
                    </td>
                </tr>
            `;

                return row;
            }

            $.ajax({
                url: '/api/users-list',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.users && data.users.length > 0) {
                        const userRows = data.users.map(createUserRow).join('');
                        tableBody.html(userRows);
                    } else {
                        tableBody.html('<tr><td colspan="7">No users found</td></tr>');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
@endsection
