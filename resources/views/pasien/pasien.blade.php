@extends('app') @section('content')
<div class="py-4">
    <h1 class="h3 mb-2">{{ $title }}</h1>

    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div
                    class="card-header d-flex justify-content-between align-items-center"
                >
                    <button class="btn btn-success" id="btnAdd">Tambah</button>
                    <select
                        name="filter_nama_rs"
                        id="filter_nama_rs"
                        class="w-auto"
                        style="
                            border-radius: 0.5rem;
                            border: 1px solid #ced4da;
                            padding: 0.25rem 0.5rem;
                        "
                    >
                        <option value="">--Semua--</option>
                        @foreach($rumahsakit as $rs)
                        <option value="{{ $rs['id'] }}">
                            {{ $rs["nama_rs"] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="card-body">
                    <table
                        id="pasienTable"
                        class="display table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Rumah Sakit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $pasien)
                            <tr
                                data-id="{{ $pasien['id'] }}"
                                data-nama_pasien="{{ $pasien['nama_pasien'] }}"
                                data-alamat="{{ $pasien['alamat'] }}"
                                data-telepon="{{ $pasien['telepon'] }}"
                                data-rs="{{ $pasien['rs_id'] }}"
                            >
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pasien->nama_pasien }}</td>
                                <td>{{ $pasien->alamat }}</td>
                                <td>{{ $pasien->telepon }}</td>
                                <td>{{ $pasien->rumahsakit->nama_rs }}</td>
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
    id="pasienModal"
    tabindex="-1"
    aria-labelledby="pasienModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <form id="pasienForm">
            @csrf
            <input type="hidden" id="pasienId" name="pasienId" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pasienModalLabel">
                        Tambah Pasien
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
                        <label for="nama_pasien" class="form-label">
                            Nama Pasien
                        </label>
                        <input
                            type="text"
                            name="nama_pasien"
                            id="nama_pasien"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label"> Alamat </label>
                        <input
                            type="text"
                            name="alamat"
                            id="alamat"
                            class="form-control"
                            required
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
                    <div class="mb-3">
                        <label for="rumahsakit" class="form-label">
                            Rumah Sakit
                        </label>
                        <select name="rs_id" id="rs_id" class="form-control">
                            <option value="">--Pilih Rumah Sakit--</option>
                            @foreach($rumahsakit as $rs)
                            <option value="{{ $rs['id'] }}">
                                {{ $rs["nama_rs"] }}
                            </option>
                            @endforeach
                        </select>
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
    id="deletePasienModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deletePasienModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Pasien</h5>
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
                Apakah Anda yakin ingin menghapus pasien
                <strong id="deletePasienName"></strong>?
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
        var table = $("#pasienTable").DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
        });

        // Custom filter by Rumah Sakit
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var selectedRS = $("#filter_nama_rs").val();
            var rsId = $("#pasienTable")
                .find("tr")
                .eq(dataIndex + 1)
                .data("rs");

            if (!selectedRS || selectedRS == rsId) {
                return true;
            }
            return false;
        });

        // Trigger filter saat select berubah
        $("#filter_nama_rs").on("change", function () {
            table.draw();
        });

        // Tambah Pasien
        $("#btnAdd").click(function () {
            $("#pasienModalLabel").text("Tambah Pasien");
            $("#pasienForm")[0].reset();
            $("#pasienId").val("");
            $("#pasienModal").modal("show");
        });

        // Edit Pasien
        $(document).on("click", ".btn-edit", function () {
            var row = $(this).closest("tr");
            var id = row.data("id");
            var nama_pasien = row.data("nama_pasien");
            var alamat = row.data("alamat");
            var telepon = row.data("telepon");
            var rs_id = row.data("rs");

            $("#pasienModalLabel").text("Edit Pasien");
            $("#pasienId").val(id);
            $("#nama_pasien").val(nama_pasien);
            $("#alamat").val(alamat);
            $("#telepon").val(telepon);
            $("#rs_id").val(rs_id);
            $("#pasienModal").modal("show");
        });

        // Submit form (AJAX)
        $("#pasienForm").submit(function (e) {
            e.preventDefault();

            var pasienId = $("#pasienId").val();
            var url = pasienId
                ? `/pasien/${pasienId}`
                : "{{ route('pasien.store') }}";
            var type = pasienId ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function (res) {
                    $("#pasienModal").modal("hide");

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
                    if (pasienId) {
                        // Edit row
                        var row = table.row($(`tr[data-id='${pasienId}']`));
                        row.data([
                            row.index() + 1,
                            res.pasien.nama_pasien,
                            res.pasien.alamat,
                            res.pasien.telepon,
                            res.pasien.rumahsakit.nama_rs,
                            row.data()[5],
                        ]).draw(false);
                    } else {
                        // Tambah row baru
                        var newRow = table.row
                            .add([
                                table.rows().count() + 1,
                                res.pasien.nama_pasien,
                                res.pasien.alamat,
                                res.pasien.telepon,
                                res.pasien.rumahsakit.nama_rs,
                                `<button class="btn btn-sm my-2 btn-primary btn-edit">Edit</button>
                         <button class="btn btn-sm btn-danger btn-delete">Hapus</button>`,
                            ])
                            .draw(false)
                            .node();
                        $(newRow)
                            .attr("data-id", res.pasien.id)
                            .attr("data-nama_pasien", res.pasien.nama_pasien)
                            .attr("data-alamat", res.pasien.alamat)
                            .attr("data-telepon", res.pasien.telepon)
                            .attr("data-rs", res.pasien.rs_id);
                    }
                },
                error: function (err) {
                    $("#pasienModal").modal("hide");

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

        // Hapus Pasien
        var pasienIdToDelete = null;

        $(document).on("click", ".btn-delete", function () {
            var row = $(this).closest("tr");
            pasienIdToDelete = row.data("id");
            var pasienName = row.data("nama_pasien");

            $("#deletePasienName").text(pasienName);
            $("#deletePasienModal").modal("show");
        });

        // Konfirmasi hapus
        $("#btnConfirmDelete").click(function () {
            if (!pasienIdToDelete) return;

            $.ajax({
                url: `/pasien/${pasienIdToDelete}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (res) {
                    if (res.success) {
                        $("#deletePasienModal").modal("hide");

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
                        var row = $(`tr[data-id='${pasienIdToDelete}']`);
                        $("#pasienTable").DataTable().row(row).remove().draw();
                    }
                },
                error: function (err) {
                    $("#deletePasienModal").modal("hide");

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
