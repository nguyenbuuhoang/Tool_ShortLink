@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
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
                                        <th>Thời hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="shortUrlTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function fetchShortURLList() {
            fetch('/api/user-list/shortURL')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    const tableBody = document.getElementById('shortUrlTableBody');
                    const currentTime = new Date();

                    data.data.forEach(function(item) {
                        const userName = item.user ? item.user.name : "Khách";
                        const expiredAt = new Date(item.expired_at);
                        const daysRemaining = Math.ceil((expiredAt - currentTime) / (1000 * 60 * 60 * 24));
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
                        tableBody.appendChild(row);
                    });
                })
                .catch(function(error) {
                    console.error('Lỗi khi tải dữ liệu: ' + error);
                });
        }

        function shortenURLIfLong(url) {
            if (url.length > 30) {
                return url.substring(0, 30) + '...';
            }
            return url;
        }

        fetchShortURLList();
    </script>
@endsection
