@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card" id="totalUsersCard">
                            <div class="card-body">
                                <h5 class="card-title">Tổng User</h5>
                                <p class="card-text"><i class="fa fa-user"></i> <span id="totalUsersCount"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card" id="totalShortURLCard">
                            <div class="card-body">
                                <h5 class="card-title">Tổng Số Link</h5>
                                <p class="card-text"><i class="fa fa-link"></i> <span id="totalShortURLCount"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card" id="totalClicksCard">
                            <div class="card-body">
                                <h5 class="card-title">Tổng Số Lần Click</h5>
                                <p class="card-text"><i class="fa fa-eye"></i> <span id="totalClicksCount"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Quản lý links</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="nameFilter">Tìm theo Tên:</label>
                                    <input type="text" id="nameFilter" class="form-control">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="urlFilter">Tìm theo Url gốc:</label>
                                    <input type="text" id="urlFilter" class="form-control">
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
                                <div class="col-md-1 mb-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" id="filter-button"
                                            class="btn btn-primary btn-block">Lọc</button>
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
                                        <th>Đường dẫn</th>
                                        <th>Tổng click</th>
                                        <th>Thời hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="shortUrlTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination" id="pagination">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editShortModal" tabindex="-1" role="dialog" aria-labelledby="editShortModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editShortModalLabel" style="color: black;">Chỉnh sửa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editShortForm">
                        <div class="form-group">
                            <label for="shortCodeInput" style="color: black;">Short Code</label>
                            <input type="text" class="form-control" id="shortCodeInput"
                                style="background-color: rgb(89, 89, 92); color: white;">
                        </div>
                        <div class="form-group">
                            <label for="newStatus" style="color: black;">Trạng thái:</label>
                            <select id="newStatus" class="form-control"
                                style="background-color: rgb(89, 89, 92); color: white;">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveShortButton">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrcodeModalLabel">QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div id="qrcode"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" id="downloadQRCode">Download</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        const filterButton = $('#filter-button');
        const nameFilter = $('#nameFilter');
        const urlFilter = $('#urlFilter ');
        const sortBy = $('#sortBy');
        const sortOrder = $('#sortOrder');
        const pagination = $('#pagination');
        const tableBody = $('#shortUrlTableBody');
        const currentTime = new Date();
        let currentPage = 1;

        function fetchDataAndDisplayTotals() {
            fetch('/api/totals')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    document.getElementById('totalUsersCount').textContent = data.total_users;
                    document.getElementById('totalShortURLCount').textContent = data.total_short_url;
                    document.getElementById('totalClicksCount').textContent = data.total_clicks;
                })
                .catch(function(error) {
                    console.error('Lỗi khi tải dữ liệu:', error);
                });
        }

        function fetchDataAndPopulateTable(page) {
            currentPage = page;
            const name = nameFilter.val();
            const url = urlFilter.val();
            const sort_by = sortBy.val();
            const sort_order = sortOrder.val();

            $.ajax({
                url: '/api/shortURL',
                method: 'GET',
                data: {
                    name,
                    url,
                    sort_by,
                    sort_order,
                    page,
                },
                dataType: 'json',
                success: function(data) {
                    const shortUrls = data.data;
                    tableBody.empty();

                    const updatePagination = data => {
                        pagination.empty();
                        if (data.last_page > 1) {
                            for (let i = 1; i <= data.last_page; i++) {
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

                    updatePagination(data);

                    shortUrls.forEach(function(item) {
                        const userName = item.user ? item.user.name : "Khách";
                        const expiredAt = new Date(item.expired_at);
                        const daysRemaining = Math.ceil((expiredAt - currentTime) / (1000 * 60 * 60 *
                            24));
                        const expiredText = daysRemaining > 0 ? `${daysRemaining} ngày` :
                            "<span class='text-danger'>Hết hạn</span>";
                        const statusText = item.status === "active" ?
                            '<span class="badge badge-success">Công khai</span>' :
                            '<span class="badge badge-danger">Riêng tư</span>';
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.id}</td>
                            <td>${userName}</td>
                            <td>
                                <div class="mb-2">
                                    <i class="fas fa-2x mr-2 fa-copy pointer d-none d-md-inline align-middle copy-icon" onclick="copyToClipboard('${item.short_url_link}')"></i>
                                    <span class="align-middle" style="color: yellow;" id="shortUrl_${item.id}">${item.short_url_link}</span>
                                </div>
                                <div>
                                    <small><span style="color: yellow;">${shortenURLIfLong(item.url)}</span></small>
                                </div>
                            </td>
                            <td>${item.clicks}</td>
                            <td>${expiredText}</td>
                            <td>${statusText}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-gear"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="actionMenu">
                                        <a class="dropdown-item edit-action" href="#">Sửa</a>
                                        <a class="dropdown-item delete-action" href="#">Xóa</a>
                                        <a class="dropdown-item qr-action" href="#">QR code</a>
                                    </div>
                                </div>
                            </td>
                        `;
                        tableBody.append(row);

                        // Edit action
                        row.querySelector('.edit-action').addEventListener('click', function(e) {
                            e.preventDefault();
                            const shortUrlId = item.id;
                            const currentShortCode = item.short_code;
                            const currentStatus = item.status;
                            document.getElementById('shortCodeInput').value = currentShortCode;
                            document.getElementById('newStatus').value = currentStatus;

                            $('#editShortModal').modal('show');

                            $('#saveShortButton').off('click').on('click', function() {
                                const newShortCode = document.getElementById(
                                    'shortCodeInput').value;
                                const newStatus = document.getElementById('newStatus')
                                    .value;

                                if (newShortCode && newStatus) {
                                    $.ajax({
                                        url: `/api/shortURL/${shortUrlId}`,
                                        method: 'PUT',
                                        data: {
                                            short_code: newShortCode,
                                            status: newStatus,
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $(
                                                    'meta[name="csrf-token"]')
                                                .attr('content'),
                                        },
                                        success: function(data) {
                                            alert('Cập nhật thành công.');
                                            location.reload();
                                        },
                                        error: function(error) {
                                            console.log(error);
                                        },
                                    });

                                    $('#editShortModal').modal('hide');
                                }
                            });
                        });

                        // Delete action
                        row.querySelector('.delete-action').addEventListener('click', function(e) {
                            e.preventDefault();
                            const shortUrlId = item.id;
                            if (confirm("Bạn có chắc chắn muốn xóa liên kết ngắn này không?")) {
                                $.ajax({
                                    url: `/api/shortURL/${shortUrlId}`,
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content'),
                                    },
                                    success: function(data) {
                                        alert('Xóa thành công.');
                                        location.reload();
                                    },
                                    error: function(error) {
                                        console.log(error);
                                    },
                                });
                            }
                        });

                        // QR Code action
                        row.querySelector('.qr-action').addEventListener('click', function(e) {
                            e.preventDefault();
                            const shortUrl = item.short_url_link;

                            $('#qrcode').empty();

                            const qrcode = new QRCode(document.getElementById("qrcode"), {
                                text: shortUrl,
                                width: 128,
                                height: 128
                            });

                            $('#qrcodeModal').modal('show');

                            $('#downloadQRCode').on('click', function(e) {
                                e.preventDefault();
                                const qrCodeImg = $('#qrcode img')[0];
                                const downloadLink = document.createElement('a');
                                downloadLink.href = qrCodeImg.src;
                                downloadLink.download = 'qrcode.png';
                                downloadLink.style.display = 'none';
                                document.body.appendChild(downloadLink);
                                downloadLink.click();
                                document.body.removeChild(downloadLink);
                            });
                        });
                    });
                },
                error: function(error) {
                    console.error('Lỗi khi tải dữ liệu: ' + error);
                }
            });
        }

        function shortenURLIfLong(url) {
            if (url.length > 30) {
                return url.substring(0, 30) + '...';
            }
            return url;
        }

        function copyToClipboard(text) {
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Đã sao chép đường dẫn thành công: ' + text);
        }

        fetchDataAndDisplayTotals();
        filterButton.click(function() {
            fetchDataAndPopulateTable(1);
        });
        fetchDataAndPopulateTable(1);
    </script>
@endsection
