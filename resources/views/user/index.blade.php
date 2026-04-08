@extends('layouts.master')

@section('tittle')
    Data User
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"> <a href="{{ url('/') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data</li>
    <li class="breadcrumb-item active">User</li>
@endsection

<style>
    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 35px;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }
</style>

@section('content')
    <div class="container-fluid">

        <div class="row">

            <section class="col-lg-12 connectedSortable">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="btn-group">
                                    <button onclick="addForm('{{ route('user.store') }}')" class="btn btn-primary btn-sm">
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
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Role</th>
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

        @include('user.form')
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
                        url: '{{ route('user.data') }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'username'
                        },
                        {
                            data: 'role'
                        },
                        {
                            data: 'aksi',
                            "searchable": false,
                            "orderable": false
                        },
                    ]
                });



                $('#modal-form form').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                            url: $(this).attr('action'),
                            type: $(this).attr('method'),
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                        })
                        .done(function(response) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Data berhasil disimpan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Data gagal disimpan'
                            });
                        });
                });


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
                $('#modal-form [name=name]').focus();
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
                $('#modal-form [name=name]').focus();

                $.get(url)
                    .done((response) => {
                        $('#modal-form [name=name]').val(response.name);
                        $('#modal-form [name=email]').val(response.email);
                        $('#modal-form [name=username]').val(response.username);
                        $('#modal-form [name=role]').val(response.role);
                    })
                    .fail((errors) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Data gagal ditampilkan',
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

            $('.toggle-password').click(function() {

                let input = $('#password');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                }

            });
        </script>
    @endpush
