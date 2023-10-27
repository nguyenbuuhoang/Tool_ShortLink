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

    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        function fetchShortURLList() {
            fetch('/api/shortURL')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    const tableBody = document.getElementById('shortUrlTableBody');
                    const currentTime = new Date();

                    data.data.forEach(function (item) {
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
                                <i class="fas fa-2x mr-2 fa-copy pointer d-none d-md-inline align-middle copy-icon"
                                    onclick="copyToClipboard('${item.short_url_link}')"></i>
                                <span class="align-middle" style="color: yellow;">${item.short_url_link}</span>
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
                                    <a class="dropdown-item qr-action" href="#" data-shorturl="${item.short_url_link}">QR code</a>
                                </div>
                            </div>
                        </td>
                    `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(function (error) {
                    console.error('Lỗi khi tải dữ liệu: ' + error);
                });
        }

        $(document).on('click', 'a.qr-action', function (e) {
            e.preventDefault();
            const shortUrl = $(this).data('shorturl');

            $('#qrcode').empty();

            const qrcode = new QRCode(document.getElementById("qrcode"), {
                text: shortUrl,
                width: 128,
                height: 128
            });

            $('#qrcodeModal').modal('show');

            $('#downloadQRCode').on('click', function (e) {
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

        function shortenURLIfLong(url) {
            if (url.length > 30) {
                return url.substring(0, 30) + '...';
            }
            return url;
        }

        fetchShortURLList();
    </script>

@endsection
