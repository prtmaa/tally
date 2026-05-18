@extends('layouts.master')

@section('tittle')
    Tally Bahan
@endsection

@section('info')
    <div class="mt-2">
        Tanggal : {{ formatTanggalIndo($tanggalBahan->tanggal) }}
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tanggalbahan.index') }}">Tanggal Bahan</a></li>
    <li class="breadcrumb-item active">Tally Bahan</li>
@endsection

@section('content')
    <style>
        .container-fluid,
        .produk-panel {
            font-size: 0.8rem;
            font-weight: 400;
        }

        /* judul produk */
        .produk-panel h6 {
            font-size: 14px;
            font-weight: 500;
            line-height: 1.3;
        }

        /* icon */
        .produk-panel .fa {
            font-size: 11px;
        }

        /* input */
        .produk-panel input {
            font-size: 12px;
            height: 28px;
        }

        /* tombol */
        .produk-panel .btn {
            font-size: 12px;
            height: 28px;
        }

        /* badge total */
        .badge {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 8px;
        }

        /* tabel */
        .produk-panel table {
            font-size: 11px;
            margin-bottom: 0;
        }

        .produk-panel table th {
            font-size: 11px;
            font-weight: 500;
            padding: 4px 6px;
            text-align: center;
            white-space: nowrap;
            line-height: 1.2;
        }

        .produk-panel table td {
            font-size: 11px;
            font-weight: 400;
            padding: 3px 6px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            line-height: 1.2;
        }

        /* kolom no */
        .produk-panel table th:first-child,
        .produk-panel table td:first-child {
            width: 40px;
            min-width: 40px;
            max-width: 40px;
            font-size: 11px;
            padding: 3px 4px;
        }

        /* kolom opsi */
        .produk-panel table th:last-child,
        .produk-panel table td:last-child {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
            font-size: 11px;
            padding: 2px 4px;
        }

        /* angka */
        .produk-panel td.pcs,
        .produk-panel td.berat {
            font-weight: 500;
        }

        .produk-panel table th:last-child,
        .produk-panel table td:last-child {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
            padding: 2px 4px;
            text-align: center;
            white-space: nowrap;
        }

        /* warna icon opsi */
        .produk-panel td:last-child a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        /* icon normal */
        .produk-panel td:last-child a i {
            color: #17a2b8 !important;
            font-size: 12px;
        }

        /* icon hapus */
        .produk-panel td:last-child a.text-danger i {
            color: #dc3545 !important;
        }

        /* warna row */
        .tr-merah {
            background-color: #f5c2c7;
        }

        .tr-hijau {
            background-color: #badbcc;
        }

        .tr-kuning {
            background-color: #ffe69c;
        }

        .tr-biru {
            background-color: #b6effb;
        }

        .tr-ungu {
            background-color: #d6c2f0;
        }

        .tr-abu {
            background-color: #d3d6d8;
        }

        .tr-orange {
            background-color: #ffd8a8;
        }

        .tr-coklat {
            background-color: #d2b48c;
        }

        .swal2-popup .form-control {
            width: 100% !important;
            max-width: 300px;
            /* atur sesuai selera */
            margin: 0 auto 10px auto;
            /* biar center */
            display: block;
        }
    </style>

    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="row">

                    <div class="col-md-2">
                        <input type="number" class="form-control pcs" min="0" placeholder="PCS">
                    </div>

                    <div class="col-md-2">
                        <input type="number" step="0.01" min="0" class="form-control berat" placeholder="Berat">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm simpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>

                        <a href="{{ route('timbanganbahan.export', $tanggalBahan->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel"></i> Excel
                        </a>
                    </div>

                    <div class="col-md-6 text-end">
                        <span class="badge bg-info">
                            Total Pcs : <span class="total-pcs">0</span>
                        </span>

                        <span class="badge bg-info">
                            Total Kg : <span class="total-berat">0.00</span>
                        </span>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div id="containerTable" class="d-flex flex-wrap gap-3"></div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        let tanggal_bahan_id = {{ $tanggalBahan->id }};

        function loadData() {
            $.get('/timbangan-bahan/load/' + tanggal_bahan_id, function(res) {

                let html = '';
                let totalPcs = 0;
                let totalBerat = 0;
                let chunkSize = 15;

                for (let i = 0; i < res.length; i += chunkSize) {
                    let chunk = res.slice(i, i + chunkSize);
                    let rows = '';

                    chunk.forEach(function(item) {

                        totalPcs += parseInt(item.pcs) || 0;
                        totalBerat += parseFloat(item.berat) || 0;

                        // ambil warna dari kode bahan
                        let warnaClass = item.bahan && item.bahan.kode ?
                            `tr-${item.bahan.kode}` :
                            '';

                        rows += `
                    <tr 
                        class="${warnaClass}" 
                        data-id="${item.id}"
                        data-bahan-id="${item.bahan_id}"
                    >

                        <td>${item.urutan}</td>
                        <td class="pcs">
                            ${item.pcs ?? 0}
                        </td>

                        <td class="berat">
                            ${parseFloat(item.berat ?? 0).toFixed(2)}
                        </td>

                        <td>
                            <a href="javascript:void(0)" class="edit text-info">
                                <i class="fa fa-pen"></i>
                            </a>

                            <a href="javascript:void(0)" class="hapus text-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `;
                    });

                    html += `
                <div class="produk-panel">

                    <div class="card card-outline card-primary">
                        <div class="card-body">

                            <table class="table table-sm table-bordered mt-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>PCS</th>
                                        <th>Berat</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            `;
                }

                $('#containerTable').html(html);
                $('.total-pcs').text(totalPcs);
                $('.total-berat').text(totalBerat.toFixed(2));

            });
        }


        $(document).on('click', '.simpan', function() {

            let btn = $(this);

            if (btn.data('loading')) return;
            btn.data('loading', true);

            let pcs = $('.pcs').val();
            let berat = $('.berat').val();

            if (pcs === '' || berat === '') {
                Swal.fire({
                    icon: 'info',
                    title: 'Error',
                    text: 'PCS dan Berat wajib diisi',
                    confirmButtonColor: '#3085d6',
                });

                btn.data('loading', false);
                return;
            }

            btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: '/timbangan-bahan',
                method: 'POST',
                timeout: 10000,

                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal_bahan_id: tanggal_bahan_id,
                    pcs: pcs,
                    berat: berat
                },

                success: function() {
                    $('.berat').val('').focus();

                    loadData();
                },

                error: function(xhr, status) {
                    if (status === 'timeout') {
                        Swal.fire('Timeout', 'Koneksi lambat, coba lagi', 'warning');
                    } else {
                        Swal.fire('Error', 'Gagal simpan data', 'error');
                    }
                },

                complete: function() {
                    btn.prop('disabled', false)
                        .html('<i class="fa fa-save"></i> Simpan');

                    btn.data('loading', false);
                }
            });
        });

        let bahanList = @json($bahans);

        $(document).on('click', '.edit', function() {

            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let bahan_id = tr.data('bahan-id');

            let pcs = tr.find('.pcs').text().trim();
            let berat = tr.find('.berat').text().trim();

            let bahanOption = '<option value="">Pilih Bahan</option>';

            bahanList.forEach(function(b) {

                let selected = b.id == bahan_id ? 'selected' : '';

                bahanOption += `
            <option value="${b.id}" ${selected}>
                ${b.nama}
            </option>
        `;
            });

            Swal.fire({
                title: 'Edit Timbangan Bahan',
                icon: 'info',
                html: `
                <div style="width:100%; padding:0 1em; box-sizing:border-box;">
            <select id="edit_bahan" class="form-control mt-2 mb-2">
                ${bahanOption}
            </select>

            <input
                id="edit_pcs"
                class="form-control"
                type="number"
                value="${pcs}"
                placeholder="PCS"
            >

            <input
                id="edit_berat"
                class="form-control"
                type="number"
                step="0.01"
                value="${berat}"
                placeholder="Berat"
            >
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                confirmButtonColor: '#3085d6'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '/timbangan-bahan/' + id,
                        type: 'PUT',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bahan_id: $('#edit_bahan').val(),
                            pcs: $('#edit_pcs').val(),
                            berat: $('#edit_berat').val()
                        },

                        success: function(res) {

                            tr.find('.pcs').text(res.data.pcs);

                            tr.find('.berat').text(
                                parseFloat(res.data.berat).toFixed(2)
                            );

                            tr.attr('data-bahan-id', res.data.bahan_id);

                            // hapus semua warna lama
                            tr.removeClass(
                                'tr-merah tr-hijau tr-kuning tr-biru tr-ungu tr-abu tr-orange tr-coklat tr-putih'
                            );

                            // pasang warna baru
                            let warnaBaru = res.kode ? res.kode.toLowerCase() : 'putih';

                            tr.addClass('tr-' + warnaBaru);
                            loadData();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil diupdate',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },

                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal update data'
                            });
                        }
                    });

                }

            });

        });

        $(document).on('click', '.hapus', function() {

            let tr = $(this).closest('tr');
            let id = tr.data('id');

            Swal.fire({
                title: 'Hapus data?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                confirmButtonColor: 'red',
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '/timbangan-bahan/' + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function() {
                            loadData();
                        }
                    });

                }
            });
        });

        $(document).on('keypress', '.pcs, .berat', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $('.simpan').click();
            }
        });

        $(document).ready(function() {
            loadData();
        });
    </script>
@endpush
