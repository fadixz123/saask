<div class="panel panel-primary">
    <div class="panel-heading">
        <b>User</b>
        <div class="pull-right">
            <a class="btn btn-default btn-xs" href="#" onclick="BukaPage('user/create_user')">tambah</a>
        </div>
    </div>
    <div class="panel-body">
        <table id="myDataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Grup</th>
                    <th>Nama Depan</th>
                    <th>Nama Belakang</th>
                    <th>Status</th>
                    <th width="8%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>

    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
    {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    $('#myDataTable').dataTable({"processing": true,
        "serverSide": true,
        "ajax": "<?php echo site_url('user/view_user_ajax'); ?>",
        "columns": [
            {
                "data": null,
                "class": "text-center",
                "orderable": false
            },
            {"data": "username"},
            {"data": "email"},
            {"data": "groups"},
            {"data": "first_name"},
            {"data": "last_name"},
            {"data": "active"},
            {
                "class": "text-center",
                "data": "id"
            }
        ],
        "order": [[1, 'asc']],
        "rowCallback": function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);

            var temp = $('td:eq(-1)', row).text();
            var string = '<a href="#" onclick="BukaPage(\'user/edit_user/' + temp + '\')">edit</a> | \n\
                <a href="#" onclick="ConfirmDelete(\'' + temp + '\')">delete</a>';
            $('td:eq(-1)', row).html(string);


            var active = $('td:eq(-2)', row).text();
            if (active == 1) {
                $('td:eq(-2)', row).html('<a href="#" onclick="deactivate(\'' + temp + '\')">active</a>');
            } else {
                $('td:eq(-2)', row).html('<a href="#" onclick="activate(\'' + temp + '\')">nonaktif</a>');
            }

        },
    });


    function activate(id) {
        var x = confirm('Akan diaktifkan?');
        if (x) {
            $.ajax({
                url: '<?php echo base_url('user/activate'); ?>/' + id,
                type: 'POST',
                data: {nip: id},
                success: function(result) {
                    if (result === 'success') {
                        $.notify('User berhasil diaktifkan', {
                            className: 'success',
                        });
                    } else {
                        $.notify('User gagal diaktifkan', {
                            className: 'error',
                        });
                    }
                    BukaPage('user')
                },
                error: function(responseData) {
                    window.location.href = '';
                }
            });
        }
    }
    function deactivate(id) {
        var x = confirm('Akan dinonaktifkan?');
        if (x) {
            $.ajax({
                url: '<?php echo base_url('user/deactivate'); ?>/' + id,
                type: 'POST',
                data: {nip: id},
                success: function(result) {
                    if (result === 'success') {
                        $.notify('User berhasil dinonaktifkan', {
                            className: 'success',
                        });
                    } else {
                        $.notify('User gagal dinonaktifkan', {
                            className: 'error',
                        });
                    }
                    BukaPage('user')
                },
                error: function(responseData) {
                    window.location.href = '';
                }
            });
        }
    }
    function ConfirmDelete(id) {
        var e = confirm('Apakah yakin user akan dihapus?');
        if (e) {
            $.ajax({
                url: '<?php echo base_url('user/delete_user'); ?>/' + id,
                type: 'POST',
                data: {id: id},
                success: function(result) {
                    if (result === 'success') {
                        BukaPage('user')
                    }
                }
            });
        }
    }
</script>