@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Danh sách user</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="nameFilter">Tìm theo Tên:</label>
                                    <input type="text" id="nameFilter" class="form-control">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="sortBy">Sắp xếp theo:</label>
                                    <select id="sortBy" class="form-control">
                                        <option value="id">ID</option>
                                        <option value="name">Tên User</option>
                                        <option value="role">Quyền</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="sortOrder">Thứ tự:</label>
                                    <select id="sortOrder" class="form-control">
                                        <option value="asc">Tăng dần</option>
                                        <option value="desc">Giảm dần</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button id="exportCSV" class="btn btn-success btn-block">Export to CSV</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 mb-2">
                                    <div class="form-group">
                                        <button type="button" id="filter-button" class="btn btn-primary btn-block">Lọc</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination" id="pagination">
                        <!-- Pagination links will be added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const tableBody = $('tbody');
            const filterButton = $('#filter-button');
            const nameFilter = $('#nameFilter');
            const sortBy = $('#sortBy');
            const sortOrder = $('#sortOrder');
            const pagination = $('#pagination');

            let currentPage = 1;

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
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${role}</td>
                        <td>${totalUrls}</td>
                        <td>${formattedDate}</td>
                        <td>${isVerified}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

            function fetchDataAndPopulateTable(page) {
                currentPage = page;
                const name = nameFilter.val();
                const sort_by = sortBy.val();
                const sort_order = sortOrder.val();

                $.ajax({
                    url: '/api/users-list',
                    method: 'GET',
                    data: {
                        name,
                        sort_by,
                        sort_order,
                        page,
                    },
                    dataType: 'json',
                    success: function(data) {
                        const users = data.users.data;

                        if (users.length > 0) {
                            const userRows = users.map(createUserRow).join('');
                            tableBody.html(userRows);
                        } else {
                            tableBody.html('<tr><td colspan="8">Không tìm thấy người dùng</td></tr>');
                        }

                        updatePagination(data);
                    },
                    error: function(error) {
                        console.error('Lỗi:', error);
                    }
                });
            }

            const updatePagination = data => {
                pagination.empty();
                if (data.users.total > data.users.per_page) {
                    for (let i = 1; i <= data.users.last_page; i++) {
                        const pageLink = $('<a>').addClass('page-link').text(i);
                        pageLink.on('click', () => fetchDataAndPopulateTable(i));

                        const listItem = $('<li>').addClass('page-item');
                        if (i === currentPage) {
                            listItem.addClass('active');
                        }
                        listItem.append(pageLink);

                        pagination.append(listItem);
                    }
                }
            };

            filterButton.click(function() {
                fetchDataAndPopulateTable(1);
            });

            fetchDataAndPopulateTable(1);
        });
    </script>
@endsection
