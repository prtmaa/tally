@extends('layouts.master')

@section('tittle')
    DO : {{ $tujuan->nama_tujuan }}
@endsection
@section('info')
    <div class="mt-2">
        Tanggal : {{ formatTanggalIndo($tujuan->tanggal->tanggal) }}<br>
        Prod. Date : {{ $tujuan->prod_date_1 }} {{ $tujuan->prod_date_2 ? ' , ' . $tujuan->prod_date_2 : '' }}
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/tally') }}">Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/tujuan/tanggal/' . $tujuan->tanggal_kiriman_id) }}">DO</a></li>
    <li class="breadcrumb-item active">Tally</li>
@endsection

@section('content')
    <style>
        .container-fluid,
        .produk-panel,
        .produk-panel * {
            font-size: 0.83rem;
        }

        /* scroll horizontal */
        .produk-scroll {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 12px;
            padding: 5px 2px 12px 2px;
        }

        .produk-scroll::-webkit-scrollbar {
            height: 7px;
        }

        .produk-scroll::-webkit-scrollbar-thumb {
            background: #cfcfcf;
            border-radius: 10px;
        }

        /* panel produk */
        .produk-panel {
            min-width: 260px;
            max-width: 260px;
            flex: 0 0 auto;
        }

        .produk-panel .card {
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .produk-panel .card-body {
            padding: 10px;
        }

        /* header produk */
        .produk-panel h6 {
            font-size: 0.8rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
        }

        /* icon aksi */
        .produk-panel .fa {
            font-size: 0.75rem;
            cursor: pointer;
            opacity: .75;
            transition: .15s;
        }

        .produk-panel .fa:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        /* input */
        .produk-panel input {
            height: 28px;
            padding: 3px 6px;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        /* tombol simpan */
        .produk-panel .btn {
            padding: 3px 7px;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        /* badge total */
        .produk-panel .badge {
            font-size: 0.7rem;
            padding: 5px 7px;
            font-weight: 500;
        }

        /* table */
        .produk-panel table {
            font-size: 0.72rem;
            margin-bottom: 0;
        }

        .produk-panel table th {
            background: #f8f9fa;
            font-weight: 600;
            text-align: center;
            padding: 4px;
        }

        .produk-panel table td {
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }

        /* kolom angka */
        .produk-panel td.pcs,
        .produk-panel td.berat {
            font-weight: 500;
        }

        /* hover table */
        .produk-panel tbody tr:hover {
            background: #fafafa;
        }

        .produk-panel input:focus {
            box-shadow: none;
            border-color: #007bff;
        }
    </style>

    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <div class="btn-group">
                                    @if ($tujuan->isOwner())
                                        <button class="btn btn-primary btn-sm" id="tambahProduk">
                                            <i class="fa fa-plus-circle"></i> Tambah
                                        </button>
                                    @endif

                                    <button class="btn btn-info btn-sm" id="btnRekap">
                                        <i class="fa fa-clipboard-list"></i> Rekap
                                    </button>

                                    <a href="{{ route('timbangan.export', $tujuan->id) }}" class="btn btn-success btn-sm">

                                        <i class="fa fa-file-excel"></i>
                                        Excel

                                    </a>

                                </div>
                            </div>

                            <div class="card-body table-responsive">
                                <div id="containerProduk" class="produk-scroll"></div>
                            </div>

                        </div>
                    </div>

                </div>

            </section>
        </div>
    </div>

    @include('timbangan.rekap')
@endsection



@push('js')
    <script>
        let tujuan_id = {{ $tujuan->id }};
        let produkList = @json($produk);
        let prodDates = @json($prodDates);

        function loadData() {

            $.get('/timbangan/load/' + tujuan_id, function(res) {

                $('#containerProduk').html('');

                res.forEach(function(item) {

                    let html = buatPanel(item);

                    $('#containerProduk').append(html);

                    item.timbangans.forEach(function(t) {

                        tambahRow(item.id, t);

                    });

                });

            });

        }

        function buatPanel(data) {

            return `
            <div class="produk-panel" data-id="${data.id}">

                <div class="card card-outline card-primary">
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <h6>
                                ${data.produk.nama_produk}${data.note ? ` (${data.note})` : ''}${data.prod_date ? ` - ${data.prod_date}` : ''}
                            </h6>

                            <div>
                                @if ($tujuan->isOwner())
                                <a class="editProduk me-2 text-info" title="Edit">
                                    <i class="fa fa-pen"></i>
                                </a>

                                <a class="hapusProduk text-danger" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </a>
                                @endif

                                <a href="javascript:void(0)" onclick="printStruk(${data.id})" class="ms-2 text-info">
                                    <i class="fa fa-print"></i>
                                </a>

                            </div>
                        </div>

                        @if ($tujuan->isOwner())
                        <input type="number" class="form-control pcs mb-2" placeholder="PCS" min="0">
                        <input type="number" step="0.01" class="form-control berat mb-2" placeholder="Berat" min="0">

                        <button class="btn btn-primary btn-sm simpan"><i class="fa fa-save"></i> Simpan</button>
                        @endif

                      <div class="mt-2 mb-2">
                        <span class="badge bg-info">
                            Total Pcs : <span class="total-pcs">0</span>
                        </span>

                        <span class="badge bg-info">
                            Total Kg : <span class="total-berat">0</span>
                        </span>
                     </div>

                        <table class="table table-sm table-bordered mt-1">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>PCS</th>
                                    <th>Berat</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>
                </div>

            </div>
            `;
        }

        function tambahRow(panel_id, data) {

            let panel = $(`.produk-panel[data-id=${panel_id}]`);

            let row = `
                <tr data-id="${data.id}" data-pcs="${data.pcs}" data-berat="${data.berat}" data-seri="${data.seri}">
                    <td><a class="showSeri text-info" title="Lihat Seri">${data.urutan}</a></td>
                    <td class="pcs">${data.pcs ?? ''}</td>
                    <td class="berat">${data.berat ?? ''}</td>
                    <td>
                        @if ($tujuan->isOwner())
                        <a class="ms-1 updateTimbangan text-info" title="Edit">
                            <i class="fa fa-pen"></i>
                        </a>
                        <a class="ms-1 hapusTimbangan text-danger" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </a>
                        @endif

                     <a href="javascript:void(0)" onclick="printTimbangan(${data.id})" class="text-info">
                        <i class="fa fa-print"></i>
                    </a>
                    </td>
                </tr>
                `;

            panel.find('tbody').prepend(row);

            hitungTotal(panel);

        }

        $(document).on('click', '.showSeri', function() {

            let seri = $(this).closest('tr').data('seri') ?? '-';

            Swal.fire({
                title: 'No Seri',
                text: seri,
                icon: 'info',
                showConfirmButton: false,
            });

        });

        function printStruk(id) {
            // 1. Buat elemen iframe tersembunyi jika belum ada
            let iframe = document.getElementById('print-iframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'print-iframe';
                iframe.style.display = 'none'; // Sembunyikan dari user
                document.body.appendChild(iframe);
            }

            // 2. Set URL PDF ke iframe (Sesuaikan dengan route struk Anda)
            iframe.src = '/print-struk/' + id;

            // 3. Tunggu sampai PDF dimuat, lalu cetak
            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500); // Jeda 500ms seperti di printTimbangan
            };
        }


        function printTimbangan(id) {
            // 1. Buat elemen iframe tersembunyi jika belum ada
            let iframe = document.getElementById('print-iframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'print-iframe';
                iframe.style.display = 'none'; // Sembunyikan dari user
                document.body.appendChild(iframe);
            }

            // 2. Set URL PDF ke iframe
            iframe.src = '/print-timbangan/' + id;

            // 3. Tunggu sampai PDF dimuat, lalu cetak
            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500); // Beri jeda sedikit agar rendering selesai
            };
        }


        function hitungTotal(panel) {

            let totalPcs = 0;
            let totalBerat = 0;

            panel.find('tbody tr').each(function() {

                totalPcs += parseInt($(this).find('.pcs').text()) || 0;
                totalBerat += parseFloat($(this).find('.berat').text()) || 0;

            });

            panel.find('.total-pcs').text(totalPcs);
            panel.find('.total-berat').text(totalBerat.toFixed(2));

        }

        function initSelect2() {
            $('.select2').select2({
                width: '100%'
            });
        }

        $(document).ready(function() {
            initSelect2();
        });

        $('#tambahProduk').click(function() {

            let option = '';

            produkList.forEach(function(p) {
                option += `<option value="${p.id}">${p.nama_produk}</option>`;
            });

            let prodDateOption = '';

            prodDates.forEach(function(d) {
                if (d) {
                    prodDateOption += `<option value="${d}">${d}</option>`;
                }
            });

            let html = `
    <div class="produk-panel">

        <div class="card card-outline card-primary">
            <div class="card-body">

                <div class='mb-2'>
                    <select class="form-control pilihProduk select2">
                        <option value="">Pilih Item</option>
                        ${option}
                    </select>
                </div>

                <div class='mb-2'>
                    <select class="form-control prodDate select2" data-placeholder="Prod. Date">
                        ${prodDateOption}
                    </select>
                </div>

                <input type="text" class="form-control note mb-2" placeholder="Deskripsi (Opsional)">

                <button class="btn btn-primary btn-sm simpanProduk">
                    <i class="fa fa-plus"></i>
                </button>

            </div>
        </div>

    </div>
    `;

            $('#containerProduk').append(html);
            initSelect2();
        });


        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });

        $(document).on('click', '.simpanProduk', function() {

            let panel = $(this).closest('.produk-panel');

            let produk_id = panel.find('.pilihProduk').val();
            let prod_date = panel.find('.prodDate').val();
            let note = panel.find('.note').val();

            $.post('/tujuan-produk', {

                _token: "{{ csrf_token() }}",
                tujuan_id: tujuan_id,
                produk_id: produk_id,
                prod_date: prod_date,
                note: note

            }, function() {

                loadData();

            });

        });


        $(document).on('click', '.simpan', function() {

            let panel = $(this).closest('.produk-panel');

            let tujuan_produk_id = panel.data('id');

            let pcs = panel.find('.pcs').val();
            let berat = panel.find('.berat').val();

            if (pcs == '' || berat == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Tidak boleh ada field yang kosong',
                    confirmButtonColor: '#3085d6',
                })
                return;
            }

            $.post('/timbangan', {

                _token: "{{ csrf_token() }}",
                tujuan_produk_id: tujuan_produk_id,
                pcs: pcs,
                berat: berat

            }, function(res) {

                tambahRow(tujuan_produk_id, res.data);

                panel.find('.berat').val('');
                panel.find('.berat').focus();

                // let id = res.data.id;

                // window.open('/print-timbangan/' + id, '_blank');

            });

        });

        $(document).ready(function() {

            loadData();

        });

        $(document).on('keypress', '.pcs, .berat', function(e) {

            if (e.which == 13) {

                e.preventDefault();

                $(this).closest('.produk-panel').find('.simpan').click();

            }

        });

        $(document).on('click', '.editProduk', function() {

            let panel = $(this).closest('.produk-panel');
            let id = panel.data('id');

            let option = '';

            produkList.forEach(function(p) {
                option += `<option value="${p.id}">${p.nama_produk}</option>`;
            });

            let prodDateOption = '';

            prodDates.forEach(function(d) {
                if (d) {
                    prodDateOption += `<option value="${d}">${d}</option>`;
                }
            });

            Swal.fire({
                title: 'Edit Produk',
                html: `
    <div class="mb-2">
        <select id="edit_produk" class="form-control">
            ${option}
        </select>
    </div>

    <div class="mb-2">
        <select id="edit_prod_date" class="form-control">
            ${prodDateOption}
        </select>
    </div>

    <div>
        <input id="edit_note" class="form-control" placeholder="  Deskripsi (Opsional)">
    </div>
    `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',

                preConfirm: () => {
                    return {
                        produk_id: $('#edit_produk').val(),
                        prod_date: $('#edit_prod_date').val(),
                        note: $('#edit_note').val()
                    }
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: '/tujuan-produk/' + id,
                        type: 'PUT',

                        data: {
                            _token: "{{ csrf_token() }}",
                            produk_id: result.value.produk_id,
                            prod_date: result.value.prod_date,
                            note: result.value.note
                        },

                        success: function() {

                            loadData();

                        }

                    });

                }

            });

        });


        $(document).on('click', '.hapusProduk', function() {

            let panel = $(this).closest('.produk-panel');

            let id = panel.data('id');

            Swal.fire({

                title: 'Hapus Item?',
                text: 'Semua data timbangan akan ikut terhapus',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showCancelButton: true,
                confirmButtonText: 'Hapus'

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: '/tujuan-produk/' + id,
                        type: 'DELETE',

                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function() {

                            panel.remove();

                        }

                    });

                }

            });

        });

        $(document).on('click', '.updateTimbangan', function() {

            let tr = $(this).closest('tr');

            let id = tr.data('id');
            let pcs = tr.data('pcs');
            let berat = tr.data('berat');

            Swal.fire({
                title: 'Edit Timbangan',
                html: `
    <input id="edit_pcs" class="swal2-input" type="number" min="0" value="${pcs}" placeholder="PCS">
    <input id="edit_berat" class="swal2-input" type="number" step="0.01" min="0" value="${berat}"
        placeholder="Berat">
    `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {

                if (result.isConfirmed) {

                    let pcs_baru = $('#edit_pcs').val();
                    let berat_baru = $('#edit_berat').val();

                    $.ajax({

                        url: '/timbangan/' + id,
                        type: 'PUT',

                        data: {
                            _token: "{{ csrf_token() }}",
                            pcs: pcs_baru,
                            berat: berat_baru
                        },

                        success: function(res) {

                            tr.find('.pcs').text(pcs_baru);
                            tr.find('.berat').text(berat_baru);

                            tr.attr('data-pcs', pcs_baru);
                            tr.attr('data-berat', berat_baru);

                            hitungTotal(tr.closest('.produk-panel'));

                        }

                    });

                }

            });

        });

        $(document).on('click', '.hapusTimbangan', function() {

            let tr = $(this).closest('tr');
            let panel = tr.closest('.produk-panel');
            let id = tr.data('id');

            Swal.fire({

                title: 'Hapus data?',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showCancelButton: true,
                confirmButtonText: 'Hapus'

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: '/timbangan/' + id,
                        type: 'DELETE',

                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function() {

                            tr.remove();

                            hitungTotal(panel);
                        }

                    });

                }

            });

        });

        $('#btnRekap').click(function() {

            $.get('/timbangan/rekap/' + tujuan_id, function(res) {

                let html = '';
                let totalPcs = 0;
                let totalBerat = 0;

                res.forEach(function(item, i) {

                    let pcs = item.total_pcs ?? 0;
                    let berat = item.total_berat ?? 0;

                    totalPcs += parseInt(pcs);
                    totalBerat += parseFloat(berat);

                    html += `
    <tr>
        <td>${i+1}</td>
        <td>${item.nama_produk}</td>
        <td>${pcs}</td>
        <td>${parseFloat(berat).toFixed(2)}</td>
    </tr>
    `;

                });

                $('#tableRekap').html(html);

                $('#rekapTotalPcs').text(totalPcs);
                $('#rekapTotalBerat').text(totalBerat.toFixed(2));

                $('#modalRekap').modal('show');

            });

        });
    </script>
@endpush
