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
                        id="rumahsakitTable"
                        class="display table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Rumah Sakit</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $rs)
                            <tr
                                data-id="{{ $rs['id'] }}"
                                data-nama_rs="{{ $rs['nama_rs'] }}"
                                data-alamat="{{ $rs['alamat'] }}"
                                data-email="{{ $rs['email'] }}"
                                data-telepon="{{ $rs['telepon'] }}"
                            >
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rs->nama_rs }}</td>
                                <td>{{ $rs->alamat }}</td>
                                <td>{{ $rs->email }}</td>
                                <td>{{ $rs->telepon }}</td>
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
    id="rumahsakitModal"
    tabindex="-1"
    aria-labelledby="rumahsakitModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <form id="rumahsakitForm">
            @csrf
            <input type="hidden" id="rumahsakitId" name="rumahsakitId" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rumahsakitModalLabel">
                        Tambah Rumah Sakit
                    </h5>
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
                        <label for="nama_rs" class="form-label">
                            Nama Rumah Sakit
                        </label>
                        <input
                            type="text"
                            name="nama_rs"
                            id="nama_rs"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input
                            type="text"
                            name="alamat"
                            id="alamat"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"> Email </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">
                            Telepon
                        </label>
                        <input
                            type="text"
                            name="telepon"
                            id="telepon"
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
    id="deleteRumahSakitModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deleteRumahSakitModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Rumah Sakit</h5>
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
                Apakah Anda yakin ingin menghapus rumah sakit
                <strong id="deleteRumahSakitName"></strong>?
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
        var table = $("#rumahsakitTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
        });

        // Tambah Rumah Sakit
        $("#btnAdd").click(function () {
            $("#rumahsakitModalLabel").text("Tambah Rumah Sakit");
            $("#rumahsakitForm")[0].reset();
            $("#rumahsakitId").val("");
            $("#rumahsakitModal").modal("show");
        });

        // Edit Rumah Sakit
        $(document).on("click", ".btn-edit", function () {
            var row = $(this).closest("tr");
            var id = row.data("id");
            var nama_rs = row.data("nama_rs");
            var alamat = row.data("alamat");
            var email = row.data("email");
            var telepon = row.data("telepon");

            $("#rumahsakitModalLabel").text("Edit Rumah Sakit");
            $("#rumahsakitId").val(id);
            $("#nama_rs").val(nama_rs);
            $("#alamat").val(alamat);
            $("#email").val(email);
            $("#telepon").val(telepon);
            $("#rumahsakitModal").modal("show");
        });

        // Submit form (AJAX)
        $("#rumahsakitForm").submit(function (e) {
            e.preventDefault();

            var rumahsakitId = $("#rumahsakitId").val();
            var url = rumahsakitId
                ? `/rumahsakit/${rumahsakitId}`
                : "{{ route('rumahsakit.store') }}";
            var type = rumahsakitId ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function (res) {
                    $("#rumahsakitModal").modal("hide");

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
                    if (rumahsakitId) {
                        // Edit row
                        var row = table.row($(`tr[data-id='${rumahsakitId}']`));
                        row.data([
                            row.index() + 1,
                            res.rumahsakit.nama_rs,
                            res.rumahsakit.alamat,
                            res.rumahsakit.email,
                            res.rumahsakit.telepon,
                            row.data()[5],
                        ]).draw(false);
                    } else {
                        // Tambah row baru
                        var newRow = table.row
                            .add([
                                table.rows().count() + 1,
                                res.rumahsakit.nama_rs,
                                res.rumahsakit.alamat,
                                res.rumahsakit.email,
                                res.rumahsakit.telepon,
                                `<button class="btn btn-sm my-2 btn-primary btn-edit">Edit</button>
                         <button class="btn btn-sm btn-danger btn-delete">Hapus</button>`,
                            ])
                            .draw(false)
                            .node();
                        $(newRow)
                            .attr("data-id", res.rumahsakit.id)
                            .attr("data-nama_rs", res.rumahsakit.nama_rs)
                            .attr("data-alamat", res.rumahsakit.alamat)
                            .attr("data-email", res.rumahsakit.email)
                            .attr("data-telepon", res.rumahsakit.telepon);
                    }
                },
                error: function (err) {
                    $("#rumahsakitModal").modal("hide");

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

        // Hapus Rumah Sakit
        var rumahsakitIdToDelete = null;

        $(document).on("click", ".btn-delete", function () {
            var row = $(this).closest("tr");
            rumahsakitIdToDelete = row.data("id");
            var rumahsakitName = row.data("nama_rs");

            $("#deleteRumahSakitName").text(rumahsakitName);
            $("#deleteRumahSakitModal").modal("show");
        });

        // Konfirmasi hapus
        $("#btnConfirmDelete").click(function () {
            if (!rumahsakitIdToDelete) return;

            $.ajax({
                url: `/rumahsakit/${rumahsakitIdToDelete}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (res) {
                    if (res.success) {
                        $("#deleteRumahSakitModal").modal("hide");

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
                        var row = $(`tr[data-id='${rumahsakitIdToDelete}']`);
                        $("#rumahsakitTable")
                            .DataTable()
                            .row(row)
                            .remove()
                            .draw();
                    }
                },
                error: function (err) {
                    $("#deleteRumahSakitModal").modal("hide");

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
