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
                            <div class="card-body">

                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <input type="date" id="tanggal" class="form-control form-control-sm tanggal"
                                        style="width: 160px;">
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-12">

                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Bahan</h6>
                                            </div>

                                            <div class="card-body">

                                                <div class="table-responsive">

                                                    <table id="tabel-bahan" class="table table-bordered table-striped">

                                                        <thead>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>Bahan</th>
                                                                <th>Total PCS</th>
                                                                <th>Total Berat</th>
                                                            </tr>
                                                        </thead>

                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="2">TOTAL</th>
                                                                <th id="total_pcs_bahan"></th>
                                                                <th id="total_berat_bahan"></th>
                                                            </tr>
                                                        </tfoot>

                                                    </table>

                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <!-- 🔹 KIRI: FROZEN -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="">Frozen</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabel-frozen" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Produk</th>
                                                                <th>Total PCS</th>
                                                                <th>Total Berat</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="2">TOTAL</th>
                                                                <th id="total_pcs_frozen"></th>
                                                                <th id="total_berat_frozen"></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <!-- 🔹 KANAN: FRESH -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="">Fresh</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="tabel-fresh" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Produk</th>
                                                                <th>Total PCS</th>
                                                                <th>Total Berat</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="2">TOTAL</th>
                                                                <th id="total_pcs_fresh"></th>
                                                                <th id="total_berat_fresh"></th>
                                                            </tr>
                                                        </tfoot>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>


                    </div>

                </div>

            </section>
        </div>
    @endsection
    @push('js')
        <script>
            let tableFrozen = $('#tabel-frozen').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('rekap.frozen') }}",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false, // 🔥 penting
                        searchable: false
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'total_pcs'
                    },
                    {
                        data: 'total_berat',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    }

                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],

                footerCallback: function(row, data) {
                    let totalPcs = 0;
                    let totalBerat = 0;

                    data.forEach(function(item) {
                        totalPcs += parseFloat(item.total_pcs) || 0;
                        totalBerat += parseFloat(item.total_berat) || 0;
                    });

                    $('#total_pcs_frozen').html(totalPcs);
                    $('#total_berat_frozen').html(totalBerat.toFixed(2));
                }
            });



            let tableFresh = $('#tabel-fresh').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('rekap.fresh') }}",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false, // 🔥 penting
                        searchable: false
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'total_pcs'
                    },
                    {
                        data: 'total_berat',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    }

                ],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],
                footerCallback: function(row, data) {
                    let totalPcs = 0;
                    let totalBerat = 0;

                    data.forEach(function(item) {
                        totalPcs += parseFloat(item.total_pcs) || 0;
                        totalBerat += parseFloat(item.total_berat) || 0;
                    });

                    $('#total_pcs_fresh').html(totalPcs);
                    $('#total_berat_fresh').html(totalBerat.toFixed(2));
                }

            });

            let tableBahan = $('#tabel-bahan').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                paging: false,
                info: false,

                ajax: {
                    url: "{{ route('rekap.bahan') }}",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'total_pcs'
                    },
                    {
                        data: 'total_berat',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    }
                ],

                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }],

                footerCallback: function(row, data) {

                    let totalPcs = 0;
                    let totalBerat = 0;

                    data.forEach(function(item) {
                        totalPcs += parseFloat(item.total_pcs) || 0;
                        totalBerat += parseFloat(item.total_berat) || 0;
                    });

                    $('#total_pcs_bahan').html(totalPcs);
                    $('#total_berat_bahan').html(totalBerat.toFixed(2));
                }
            });


            setInterval(function() {
                tableBahan.ajax.reload(null, false);
                tableFrozen.ajax.reload(null, false);
                tableFresh.ajax.reload(null, false);
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
