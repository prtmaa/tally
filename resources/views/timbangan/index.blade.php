@extends('layouts.master')

@section('tittle')
    @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
    @else
        DO :
    @endif {{ $tujuan->nama_tujuan }}
@endsection
@section('info')
    <div class="mt-2">
        Tanggal : {{ formatTanggalIndo($tujuan->tanggal->tanggal) }}<br>
        Prod. Date : {{ $tujuan->prod_date_1 }}
        {{ $tujuan->prod_date_2 ? ' , ' . $tujuan->prod_date_2 : '' }}{{ $tujuan->prod_date_3 ? ' , ' . $tujuan->prod_date_3 : '' }}
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/tally') }}">Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/tujuan/tanggal/' . $tujuan->tanggal_kiriman_id) }}">
            @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
                Rak
            @else
                DO
            @endif
        </a></li>
    <li class="breadcrumb-item active">Tally</li>
@endsection

@section('content')
    <style>
        /* base */
        .container-fluid,
        .produk-panel {
            font-size: 0.8rem;
            font-weight: 400;
        }

        /* panel utama */
        .produk-scroll {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 4px 2px 10px;
        }

        .produk-panel {
            width: 100%;
            max-width: 100%;
        }

        .produk-panel .card {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .produk-panel .card-body {
            padding: 6px;
        }

        /* judul */
        .produk-panel h6 {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            line-height: 1.3;
            color: #2c2c2c;
        }

        /* icon umum */
        .produk-panel .fa {
            font-size: 11px;
            cursor: pointer;
            opacity: 0.8;
            transition: 0.15s;
        }

        .produk-panel .fa:hover {
            opacity: 1;
            transform: scale(1.03);
        }

        .produk-panel input:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        /* input + tombol + total */
        .form-inline-box {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .form-inline-box input {
            width: 85px !important;
            height: 28px;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: 400;
            border-radius: 4px;
        }

        .form-inline-box .rak {
            width: 65px !important;
        }

        .form-inline-box .btn {
            height: 28px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 400;
            border-radius: 4px;
            white-space: nowrap;
        }

        /* total */
        .total-inline {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .total-inline .badge {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 8px;
        }

        /* wrapper tabel */
        .table-wrapper {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 8px;
            margin-top: 8px;
        }

        .table-wrapper::-webkit-scrollbar {
            height: 5px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: #d0d0d0;
            border-radius: 10px;
        }

        .table-wrapper .mini-table {
            flex: 0 0 auto;
            width: auto;
        }

        /* tabel */
        .produk-panel table {
            width: auto;
            border-collapse: collapse;
            margin-bottom: 0;
            table-layout: auto;
            font-size: 11px;
        }

        .produk-panel table th {
            background: #f8f9fa;
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

        /* kolom NO */
        .produk-panel table th:first-child,
        .produk-panel table td:first-child {
            min-width: 40px;
            width: 40px;
            max-width: 40px;
            font-size: 11px;
            /* disamakan */
            font-weight: 400;
            padding: 3px 4px;
        }

        /* kolom OPSI */
        /* kolom opsi agar pas sesuai isi icon */
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


        /* angka */
        .produk-panel td.pcs,
        .produk-panel td.berat {
            font-weight: 500;
        }

        .warna-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            max-width: 240px;
            /* batasi lebar */
            margin: 10px auto;
            /* auto = center */
        }

        .warna-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            max-width: 240px;
            /* batasi lebar */
            margin: 10px auto;
            /* auto = center */
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

    <div class="modal fade" id="modalPrint" style="overflow:hidden;" role="dialog" aria-labelledby="modal-form"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content rounded-3 shadow">

                <div class="modal-header">
                    <h6 class="modal-title">Pilih Halaman</h6>
                </div>

                <div class="modal-body">
                    <select id="rangeSelect" class="form-control"></select>

                    <small class="text-muted d-block mt-2" id="infoTotal"></small>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm w-100" onclick="submitPrint()">Print</button>
                </div>

            </div>
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

                                <a href="javascript:void(0)" onclick="openPrintModal(${data.id})" class="ms-2 text-info">
                                    <i class="fa fa-print"></i>
                                </a>

                            </div>
                        </div>

                        @if ($tujuan->isOwner())
                    <div class="form-inline-box">

                        <input type="number"
                            class="form-control pcs"
                            placeholder="PCS"
                            min="0">

                        <input type="number"
                            step="0.01"
                            class="form-control berat"
                            placeholder="Berat"
                            min="0">

                        @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
                            <input type="number"
                                class="form-control rak"
                                placeholder="Rak"
                                min="0">
                        @else
                            <input type="hidden"
                                class="form-control rak">
                        @endif

                        <button class="btn btn-primary btn-sm simpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    @endif

                        <div class="total-inline">
                            <span class="badge bg-info">
                                PCS : <span class="total-pcs">0</span>
                            </span>

                            <span class="badge bg-info">
                                KG : <span class="total-berat">0</span>
                            </span>
                        </div>

                    </div>

                     <div class="table-wrapper"></div>

                    </div>
                </div>

            </div>
            `;
        }

        function tambahRow(panel_id, data) {
            let panel = $(`.produk-panel[data-id=${panel_id}]`);
            let wrapper = panel.find('.table-wrapper');

            let warnaClass = data.warna ? `tr-${data.warna}` : '';

            // total row dalam item tsb
            let totalRows = wrapper.find('tr.data-row').length;

            // setiap 10 row → buat tabel baru
            if (totalRows % 10 === 0) {
                let table = `
            <div class="mini-table">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PCS</th>
                            <th>Berat</th>

                            @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
                                <th>Rak</th>
                            @endif

                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        `;

                wrapper.append(table);
            }

            let lastTable = wrapper.find('table').last();

            let row = `
        <tr class="data-row ${warnaClass}"
            data-id="${data.id}"
            data-pcs="${data.pcs}"
            data-berat="${data.berat}"
            data-rak="${data.rak ?? ''}"
            data-seri="${data.seri}">

            <td>
                <a class="showSeri text-info">
                    ${data.urutan}
                </a>
            </td>

            <td class="pcs">${data.pcs ?? ''}</td>
            <td class="berat">${data.berat ?? ''}</td>

            @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
                <td class="rak">${data.rak ?? ''}</td>
            @endif

            <td>
                @if ($tujuan->isOwner())
                    <a class="updateTimbangan text-info">
                        <i class="fa fa-pen"></i>
                    </a>

                    <a class="hapusTimbangan text-danger ms-1">
                        <i class="fa fa-trash"></i>
                    </a>

                    <a class="pilihWarnaBtn text-info ms-1">
                        <i class="fa fa-palette"></i>
                    </a>
                @endif

                <a href="javascript:void(0)"
                   onclick="printTimbangan(${data.id})"
                   class="text-info ms-1">
                    <i class="fa fa-print"></i>
                </a>
            </td>
        </tr>
    `;

            // append ke tabel terakhir
            lastTable.find('tbody').append(row);

            hitungTotal(panel);
        }

        function rapikanTable(panel) {
            let semuaData = [];

            // ambil semua row lama
            panel.find('tr.data-row').each(function() {
                let row = $(this);

                semuaData.push({
                    id: row.data('id'),
                    pcs: row.data('pcs'),
                    berat: row.data('berat'),
                    rak: row.data('rak'),
                    seri: row.data('seri'),
                    warna: getWarnaClass(row)
                });
            });

            // hapus semua tabel lama
            panel.find('.table-wrapper').html('');

            // render ulang semua row
            semuaData.forEach(function(item, index) {
                item.urutan = index + 1;
                tambahRow(panel.data('id'), item);
            });
        }

        function getWarnaClass(row) {
            let warnaList = [
                'merah',
                'hijau',
                'kuning',
                'biru',
                'ungu',
                'abu',
                'orange',
                'coklat'
            ];

            for (let warna of warnaList) {
                if (row.hasClass('tr-' + warna)) {
                    return warna;
                }
            }

            return '';
        }



        $(document).on('click', '.pilihWarnaBtn', function() {

            let row = $(this).closest('tr');
            let id = row.data('id');

            Swal.fire({
                title: 'Pilih Warna',
                icon: 'info',
                html: `
                    <div class="warna-container">
                        <button class="btn-warna" data-warna="merah" style="background:#dc3545;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="hijau" style="background:#28a745;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="kuning" style="background:#ffc107;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="biru" style="background:#17a2b8;width:50px;height:50px;border:none;"></button>
                        
                        <button class="btn-warna" data-warna="ungu" style="background:#6f42c1;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="abu" style="background:#6c757d;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="orange" style="background:#fd7e14;width:50px;height:50px;border:none;"></button>
                        <button class="btn-warna" data-warna="coklat" style="background:#8b5e3c;width:50px;height:50px;border:none;"></button>
                    </div>

                    <button id="resetWarna" class="btn btn-secondary btn-sm mt-3">Reset</button>
                `,
                showConfirmButton: false,
                didOpen: () => {

                    $('.btn-warna').click(function() {

                        let warna = $(this).data('warna');

                        row.removeClass(
                            'tr-merah tr-hijau tr-kuning tr-biru tr-ungu tr-abu tr-orange tr-coklat'
                        );

                        row.addClass(`tr-${warna}`);

                        simpanWarna(id, warna);

                        Swal.close();
                    });

                    $('#resetWarna').click(function() {

                        row.removeClass(
                            'tr-merah tr-hijau tr-kuning tr-biru tr-ungu tr-abu tr-orange tr-coklat'
                        );

                        simpanWarna(id, '');

                        Swal.close();
                    });

                }
            });

        });

        function simpanWarna(id, warna) {
            $.ajax({
                url: `/timbangan/update-warna/${id}`,
                method: 'POST',
                data: {
                    warna: warna, // '' untuk reset
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
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

        let currentId = null;

        function openPrintModal(id) {
            currentId = id;

            let select = document.getElementById('rangeSelect');
            let info = document.getElementById('infoTotal');

            select.innerHTML = '<option>Loading...</option>';

            fetch(`/timbangan/rak-list/${id}`)
                .then(res => res.json())
                .then(res => {
                    let rakList = res.rak;

                    select.innerHTML = '';

                    rakList.forEach(r => {
                        select.innerHTML += `<option value="${r}">Rak ${r}</option>`;
                    });

                    info.innerHTML = `Total Rak: <b>${rakList.length}</b>`;
                });

            new bootstrap.Modal(document.getElementById('modalPrint')).show();
        }


        function submitPrint() {
            let rak = document.getElementById('rangeSelect').value;

            let iframe = document.getElementById('print-iframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'print-iframe';
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
            }

            iframe.src = `/print-struk/${currentId}?rak=${rak}`;

            iframe.onload = function() {
                setTimeout(() => {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500);
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

            let btn = $(this);

            if (btn.data('loading')) return;
            btn.data('loading', true);

            let panel = btn.closest('.produk-panel');

            let produk_id = panel.find('.pilihProduk').val();
            let prod_date = panel.find('.prodDate').val();
            let note = panel.find('.note').val();

            btn.prop('disabled', true).text('Loading...');

            $.post('/tujuan-produk', {
                    _token: "{{ csrf_token() }}",
                    tujuan_id: tujuan_id,
                    produk_id: produk_id,
                    prod_date: prod_date,
                    note: note
                })
                .done(function() {
                    loadData();
                })
                .always(function() {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i>');
                    btn.data('loading', false);
                });

        });

        $(document).on('click', '.simpan', function() {

            let btn = $(this);

            // 🔥 cegah double klik
            if (btn.data('loading')) return;
            btn.data('loading', true);

            let panel = btn.closest('.produk-panel');

            let tujuan_produk_id = panel.data('id');
            let pcs = panel.find('.pcs').val();
            let berat = panel.find('.berat').val();
            let rak = panel.find('.rak').val();

            if (pcs == '' || berat == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Tidak boleh ada field yang kosong',
                    confirmButtonColor: '#3085d6',
                });

                btn.data('loading', false);
                return;
            }

            // 🔥 loading UI
            btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: '/timbangan',
                method: 'POST',
                timeout: 10000, // 🔥 10 detik

                data: {
                    _token: "{{ csrf_token() }}",
                    tujuan_produk_id: tujuan_produk_id,
                    pcs: pcs,
                    berat: berat,
                    rak: rak
                },

                success: function(res) {

                    tambahRow(tujuan_produk_id, res.data);

                    panel.find('.berat').val('').focus();

                },

                error: function(xhr, status) {

                    if (status === 'timeout') {
                        Swal.fire('Timeout', 'Koneksi lambat, coba lagi', 'warning');
                    } else {
                        Swal.fire('Error', 'Gagal simpan data', 'error');
                    }

                },

                complete: function() {
                    // 🔥 pasti jalan (success / error / timeout)
                    btn.prop('disabled', false)
                        .html('<i class="fa fa-save"></i> Simpan');

                    btn.data('loading', false);
                }
            });

            // 🔥 failsafe kalau request mati total
            setTimeout(() => {
                if (btn.data('loading')) {
                    btn.prop('disabled', false)
                        .html('<i class="fa fa-save"></i> Simpan');

                    btn.data('loading', false);
                }
            }, 15000);

        });

        $(document).ready(function() {

            loadData();

        });

        $(document).on('keypress', '.pcs, .berat, .rak', function(e) {

            if (e.which == 13) {

                e.preventDefault();

                let panel = $(this).closest('.produk-panel');
                let btn = panel.find('.simpan');

                // 🔥 kalau sedang loading → stop
                if (btn.data('loading')) return;

                btn.click();
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
            let rak = tr.data('rak');

            Swal.fire({
                title: 'Edit Timbangan',
                html: `
                <input id="edit_pcs" class="swal2-input" type="number" min="0" value="${pcs}" placeholder="PCS">
                <input id="edit_berat" class="swal2-input" type="number" step="0.01" min="0" value="${berat}"
                    placeholder="Berat">
                @if ($tujuan->tanggal->jenis == 'Frozen' || $tujuan->tanggal->jenis == 'Frozen Tulang')
                <input id="edit_rak" class="swal2-input" type="number" min="0" value="${rak}" placeholder="Rak">
                @else
                @endif
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
                    let rak_baru = $('#edit_rak').val();

                    $.ajax({

                        url: '/timbangan/' + id,
                        type: 'PUT',

                        data: {
                            _token: "{{ csrf_token() }}",
                            pcs: pcs_baru,
                            berat: berat_baru,
                            rak: rak_baru
                        },

                        success: function(res) {

                            tr.find('.pcs').text(pcs_baru);
                            tr.find('.berat').text(berat_baru);
                            tr.find('.rak').text(rak_baru);

                            tr.attr('data-pcs', pcs_baru);
                            tr.attr('data-berat', berat_baru);
                            tr.attr('data-rak', rak_baru);

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

                            rapikanTable(panel);

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
