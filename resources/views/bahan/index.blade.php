@extends('layouts.master')

@section('tittle')
    Data Item
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data</li>
    <li class="breadcrumb-item active">Bahan</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="btn-group">
                                    <button onclick="addForm('{{ route('bahan.store') }}')" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus-circle"></i> Tambah Data
                                    </button>
                                </div>
                            </div>


                            <div class="card-body table-responsive">
                                <form action="" class="form-produk" method="post">
                                    @csrf
                                    <table class="table text-center table-bordered">
                                        <thead>
                                            <th style="width: 20px;">No</th>
                                            <th>Bahan</th>
                                            <th>Warna</th>
                                            <th style="width: 220px;">Aksi</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </div>

        @include('bahan.form')
    @endsection

    @push('js')
        <script>
            let table;
            $(function() {
                table = $('.table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    autoWidth: false,
                    responsive: true,
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
                        url: '{{ route('bahan.data') }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'kode'
                        },
                        {
                            data: 'aksi',
                            "searchable": false,
                            "orderable": false
                        },
                    ]
                });



                $('#modal-form').validator().on('submit', function(e) {
                    if (!e.preventDefault()) {
                        $.ajax({
                                enctype: 'multipart/form-data',
                                url: $('#modal-form form').attr('action'),
                                type: $('#modal-form form').attr('method'),
                                data: new FormData($('#modal-form form')[0]),
                                async: false,
                                processData: false,
                                contentType: false
                            })
                            .done((response) => {
                                $('#modal-form').modal('hide');
                                table.ajax.reload();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data berhasil disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            })
                            .fail((errors) => {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: 'Data gagal disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            });
                    }
                })

            });

            function addForm(url) {
                $('#modal-form').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
                $('#modal-form .modal-title').text('Tambah Data');

                $('#modal-form form')[0].reset();
                $('#modal-form form').attr('action', url);
                $('#modal-form [name=_method]').val('post');
                $('#modal-form [name=nama]').focus();
            }

            function editForm(url) {
                $('#modal-form').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
                $('#modal-form .modal-title').text('Edit Data');
                $('#modal-form form')[0].reset();
                $('#modal-form form').attr('action', url);
                $('#modal-form [name=_method]').val('put');
                $('#modal-form [name=nama]').focus();

                $.get(url)
                    .done((response) => {
                        $('#modal-form [name=nama]').val(response.nama);
                        $('#modal-form [name=kode]').val(response.kode);
                    })
                    .fail((errors) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Data gagal ditampilkan',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    });
            }

            function deleteData(url) {
                Swal.fire({
                    title: 'Yakin?',
                    text: "Data akan dihapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(url, {
                                '_token': $('[name=csrf-token]').attr('content'),
                                '_method': 'delete'
                            })
                            .done((response) => {
                                table.ajax.reload();
                                $('.alertdelete').fadeIn();

                                setTimeout(() => {
                                    $('.alertdelete').fadeOut();
                                }, 3000);
                            })
                            .fail((errors) => {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: 'Data gagal dihapus',
                                })
                            });
                    }
                })
            }
        </script>
    @endpush
