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

        function fetchShortURLList() {
            fetch('/api/user-list/shortURL')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    const tableBody = document.getElementById('shortUrlTableBody');
                    const currentTime = new Date();

                    data.forEach(function(item) {
                        const userIdText = item.user_id !== null ? item.user_id : "Khách";
                        const expiredAt = new Date(item.expired_at);
                        const daysRemaining = Math.ceil((expiredAt - currentTime) / (1000 * 60 * 60 * 24));
                        const expiredText = daysRemaining > 0 ? `${daysRemaining} ngày` :
                            "<span class='text-danger'>Hết hạn</span>";
                        const statusText = item.status === "active" ?
                            '<span class="badge badge-success">Công khai</span>' :
                            '<span class="badge badge-danger">Riêng tư</span>';
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${userIdText}</td>
                            <td>${item.user_name}</td>
                            <td>
                                <div class="mb-2">
                                    <i class=" fas fa-2x mr-2 fa-copy pointer d-none d-md-inline align-middle copy-icon" onclick="copyToClipboard('${item.short_url_link}')"></i>
                                    <span class="align-middle" style="color: yellow; id="shortUrl_${item.id}">${item.short_url_link}</span>
                                </div>
                                <div>
                                    <small><span style="color: yellow;">${shortenURLIfLong(item.url)}</span></small>
                                </div>
                            </td>
                            <td>${item.total_clicks}</td>
                            <td>${expiredText}</td>
                            <td>${statusText}</td>
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
        fetchShortURLList();
    </script>
@endsection
