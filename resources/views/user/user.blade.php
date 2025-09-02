@extends('app') @section('content')
<div class="py-4">
    <h1 class="h3 mb-2">{{ $title }}</h1>

    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <button class="btn btn-success" id="btnAdd">Tambah</button>
                </div>

                <div class="card-body">
                    <table
                        id="usersTable"
                        class="display table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                            <tr
                                data-id="{{ $user['id'] }}"
                                data-name="{{ $user['name'] }}"
                                data-username="{{ $user['username'] }}"
                            >
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user["name"] }}</td>
                                <td>{{ $user["username"] }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm my-2 btn-primary btn-edit"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-danger btn-delete"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div
    class="modal fade"
    id="userModal"
    tabindex="-1"
    aria-labelledby="userModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <form id="userForm">
            @csrf
            <input type="hidden" id="userId" name="userId" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            Username
                        </label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSave">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div
    class="modal fade"
    id="deleteUserModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deleteUserModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Tutup"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus user
                <strong id="deleteUserName"></strong>?
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Batal
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="btnConfirmDelete"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@endsection @section('scripts')

<script>
    $(document).ready(function () {
        var table = $("#usersTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
        });

        // Tambah user
        $("#btnAdd").click(function () {
            $("#userModalLabel").text("Tambah User");
            $("#userForm")[0].reset();
            $("#userId").val("");
            $("#password").attr("required", true);
            $("#userModal").modal("show");
        });

        // Edit user
        $(document).on("click", ".btn-edit", function () {
            var row = $(this).closest("tr");
            var id = row.data("id");
            var name = row.data("name");
            var username = row.data("username");

            $("#userModalLabel").text("Edit User");
            $("#userId").val(id);
            $("#name").val(name);
            $("#username").val(username);
            $("#password").val("").attr("required", false);
            $("#userModal").modal("show");
        });

        // Submit form (AJAX)
        $("#userForm").submit(function (e) {
            e.preventDefault();

            var userId = $("#userId").val();
            var url = userId ? `/user/${userId}` : "{{ route('user.store') }}";
            var type = userId ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function (res) {
                    $("#userModal").modal("hide");

                    // Tampilkan alert
                    var alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${res.message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    $("#alertContainer").html(alertHtml);

                    // Update DataTables
                    if (userId) {
                        // Edit row
                        var row = table.row($(`tr[data-id='${userId}']`));
                        row.data([
                            row.index() + 1,
                            res.user.name,
                            res.user.username,
                            row.data()[3],
                        ]).draw(false);
                    } else {
                        // Tambah row baru
                        var newRow = table.row
                            .add([
                                table.rows().count() + 1,
                                res.user.name,
                                res.user.username,
                                `<button class="btn btn-sm btn-primary btn-edit">Edit</button>
                         <button class="btn btn-sm btn-danger btn-delete">Hapus</button>`,
                            ])
                            .draw(false)
                            .node();
                        $(newRow)
                            .attr("data-id", res.user.id)
                            .attr("data-name", res.user.name)
                            .attr("data-username", res.user.username);
                    }
                },
                error: function (err) {
                    $("#userModal").modal("hide");

                    var alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${err.responseJSON?.message || "Terjadi kesalahan"}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    $("#alertContainer").html(alertHtml);
                },
            });
        });

        // Hapus User
        var userIdToDelete = null;

        $(document).on("click", ".btn-delete", function () {
            var row = $(this).closest("tr");
            userIdToDelete = row.data("id");
            var userName = row.data("name");

            $("#deleteUserName").text(userName);
            $("#deleteUserModal").modal("show");
        });

        // Konfirmasi hapus
        $("#btnConfirmDelete").click(function () {
            if (!userIdToDelete) return;

            $.ajax({
                url: `/user/${userIdToDelete}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (res) {
                    if (res.success) {
                        $("#deleteUserModal").modal("hide");

                        // Tampilkan alert Bootstrap
                        var alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${res.message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                        $("#alertContainer").html(alertHtml);

                        // Hapus row di DataTables tanpa reload
                        var row = $(`tr[data-id='${userIdToDelete}']`);
                        $("#usersTable").DataTable().row(row).remove().draw();
                    }
                },
                error: function (err) {
                    $("#deleteUserModal").modal("hide");

                    // Tampilkan alert error
                    var alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${err.responseJSON?.message || "Terjadi kesalahan"}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
                    $("#alertContainer").html(alertHtml);
                },
            });
        });
    });
</script>

@endsection
