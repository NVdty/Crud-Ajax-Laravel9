<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('pegawaiAjax') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'aksi',
                    name: 'aksi'
                }
            ]
        });
    });

    //global setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //proses simpan
    $('body').on('click', '.tombol-tambah', function(e) {
        e.preventDefault();
        $('#exampleModal').modal('show');

        $('.tombol-simpan').click(function() {
            simpan();
        });
    });

    //untuk edit dan delete
    $('body').on('click', '.tombol-edit', function(e) {
        var id = $(this).data('id');
        $.ajax({
            url: 'pegawaiAjax/' + id + '/edit',
            type: 'GET',
            success: function(response) {
                $('#exampleModal').modal('show');
                $('#nama').val(response.result.nama);
                $('#email').val(response.result.email);
                console.log(response.result);
                $('.tombol-simpan').click(function() {
                    simpan(id);
                });
            }
        });
    });

    //proses delete
    $('body').on('click', '.tombol-del', function(e){
        if (confirm('apakah anda yakin ingin menghapus data ini?') ==true){
            var id = $(this).data('id');
            $.ajax({
                url: 'pegawaiAjax/' + id,
                type: 'DELETE',
            });
            $('#myTable').DataTable().ajax.reload();
        }
    });


    //function simpan dan update    
    function simpan(id = '') {

        if (id == '') {
            var var_url = 'pegawaiAjax';
            var var_type = 'POST';
        } else {
            var var_url = 'pegawaiAjax/' + id;
            var var_type = 'PUT';
        }

        $.ajax({
            url: var_url,
            type: var_type,
            data: {
                nama: $('#nama').val(),
                email: $('#email').val()
            },
            success: function(response) {

                if (response.errors) {
                    console.log(response.errors);
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').html("<ul>");
                    $.each(response.errors, function(key, value) {
                        $('.alert-danger').find('ul').append("<li>" + value + "</li>");
                    });
                    $('.alert-danger').append("</ul>");

                } else {
                    $('.alert-success').removeClass('d-none');
                    $('.alert-success').html(response.success);
                }

                $('#myTable').DataTable().ajax.reload();

            }
        });
    }

    //untuk mengosongkan form daftar setelah melakukan submit
    $('#exampleModal').on('hidden.bs.modal', function() {
        $('#nama').val('');
        $('#email').val('');

        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');

        $('.alert-success').addClass('d-none');
        $('.alert-success').html('');

    })
</script>
