@extends('layouts.master')

@section('tittle')
    Dashboard
@endsection

@section('content')
    <style>
        #tabel-rekap th,
        #tabel-rekap td {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <input type="date" id="tanggal" class="form-control form-control-sm tanggal"
                                        style="width: 160px;">
                                </div>

                                <table id="tabel-rekap" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="35%">Produk</th>
                                            <th width="20%">Total PCS</th>
                                            <th width="20%">Total Berat</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </div>
    @endsection
    @push('js')
        <script>
            let table = $('#tabel-rekap').DataTable({
                processing: false,
                serverSide: true,
                deferRender: true,
                autoWidth: false,
                responsive: true,
                searching: false,
                paging: false,
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sSearch": "Pencarian:",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    },
                },
                ajax: {
                    url: "{{ route('rekap.data') }}",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val(); // 🔥 kirim 1 tanggal
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'total_pcs'
                    },
                    {
                        data: 'total_berat'
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }]
            });

            setInterval(function() {
                table.ajax.reload(null, false);
            }, 1000);

            flatpickr(".tanggal", {
                dateFormat: "Y-m-d",
                defaultDate: "today",
                locale: "id",
                onReady: function(selectedDates, dateStr, instance) {
                    instance.input.style.backgroundColor = "#fff";
                    instance.input.style.color = "#000";
                    instance.input.style.border = "1px solid #ced4da";
                }
            });
        </script>
    @endpush
