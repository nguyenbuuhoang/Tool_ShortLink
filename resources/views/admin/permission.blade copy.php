<!DOCTYPE html>
<html>

<head>
    <title>Phân quyền</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h1>Quản lý quyền và vai trò</h1>
    <h2>Phân quyền:</h2>
    <form id="assign-permission-form">
        @csrf
        <label>Chọn vai trò:</label>
        <div id="role-checkboxes">
            <!-- Danh sách vai trò sẽ được thêm vào đây bằng jQuery -->
        </div>

        <label>Chọn quyền:</label>
        <div id="permission-checkboxes">
            <!-- Danh sách quyền sẽ được thêm vào đây bằng jQuery -->
        </div>

        <button type="button" id="assign-button">Gán quyền</button>
        <button type="button" id="revoke-button">Xóa quyền</button>
    </form>

    <div id="message"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy token CSRF từ thẻ meta
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Xử lý sự kiện gán quyền khi nhấn nút "Gán quyền"
            $('#assign-button').click(function() {
                var roleIds = [];
                var permissionIds = [];

                // Lấy danh sách vai trò được chọn
                $('input[name="role"]:checked').each(function() {
                    roleIds.push($(this).val());
                });

                // Lấy danh sách quyền được chọn
                $('input[name="permission"]:checked').each(function() {
                    permissionIds.push($(this).val());
                });

                // Thực hiện yêu cầu POST để gán quyền
                $.ajax({
                    url: '/api/assign_permission',
                    type: 'POST',
                    data: {
                        role_ids: roleIds,
                        permission_ids: permissionIds
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Thêm token CSRF vào tiêu đề
                    },
                    success: function(response) {
                        $('#message').text(response);
                    }
                });
            });

            // Xử lý sự kiện xóa quyền khi nhấn nút "Xóa quyền"
            $('#revoke-button').click(function() {
                var roleIds = [];
                var permissionIds = [];

                // Lấy danh sách vai trò được chọn
                $('input[name="role"]:checked').each(function() {
                    roleIds.push($(this).val());
                });

                // Lấy danh sách quyền được chọn
                $('input[name="permission"]:checked').each(function() {
                    permissionIds.push($(this).val());
                });

                // Thực hiện yêu cầu POST để xóa quyền
                $.ajax({
                    url: '/api/revoke_permission',
                    type: 'POST',
                    data: {
                        role_ids: roleIds,
                        permission_ids: permissionIds
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Thêm token CSRF vào tiêu đề
                    },
                    success: function(response) {
                        $('#message').text(response);
                    }
                });
            });
        });
    </script>
</body>

</html>
