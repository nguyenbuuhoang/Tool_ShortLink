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
                                        <th></th>
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
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="delete-selected-users">Xóa Người Dùng Được Chọn</button>
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
                const role = user.roles.map(role => role.name).join(', ');
                const totalUrls = user.total_urls.length > 0 ? user.total_urls[0].total_url : 0;

                const row = `
                        <tr>
                            <td><input type="checkbox" class="user-checkbox"></td>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${role}</td>
                            <td>${totalUrls}</td>
                            <td>${formattedDate}</td>
                            <td>${isVerified}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenu" data-toggle="dropdown" aria-haspopup="true" ariaexpanded="false">
                                        <i class="fas fa-gear"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="actionMenu">
                                        <a class="dropdown-item edit-action" href="#">Sửa Role</a>
                                        <a class="dropdown-item delete-action" href="#">Xóa</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                return row;
            }

            $("#delete-selected-users").on("click", function() {
                const selectedUsers = $(".user-checkbox:checked");
                const userIds = selectedUsers.map(function() {
                    return $(this).closest("tr").find("td:nth-child(2)").text();
                }).get();

                if (userIds.length === 0) {
                    alert("Hãy chọn ít nhất một người dùng để xóa.");
                    return;
                }

                if (confirm("Bạn có chắc chắn muốn xóa các người dùng đã chọn?")) {
                    $.ajax({
                        url: "/api/delete-selected-users",
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: { user_ids: userIds },
                        dataType: "json",
                        success: function(data) {
                            alert(data.message);
                            selectedUsers.closest("tr").remove();
                        },
                        error: function(error) {
                            console.error("Lỗi:", error);
                            alert("Lỗi xóa người dùng");
                        }
                    });
                }
            });

            $.ajax({
                url: '/api/users-list',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const users = data.users.data;

                    if (users.length > 0) {
                        const userRows = users.map(createUserRow).join('');
                        tableBody.html(userRows);
                    } else {
                        tableBody.html('<tr><td colspan="8">Không tìm thấy người dùng</td></tr>');
                    }
                },
                error: function(error) {
                    console.error('Lỗi:', error);
                }
            });
        });
    </script>
@endsection
