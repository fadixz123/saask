<div class="panel panel-primary">
    <div class="panel-heading">
        <b>Group</b>
        <div class="pull-right">
            <a class="btn btn-default btn-xs" href="#" onclick="BukaPage('user/create_group')">tambah</a>
        </div>
    </div>
    <div class="panel-body">
        <table id="myDataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Aksi</th>
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
        "ajax": "<?php echo site_url('user/view_group_ajax'); ?>",
        "columns": [
            {
                "data": null,
                "class": "text-center",
                "orderable": false
            },
            {"data": "name"},
            {"data": "description"},
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
            var string = '<a href="#" onclick="BukaPage(\'user/edit_group/' + temp + '\')">edit</a> | \n\
                <a href="#" onclick="ConfirmDelete(\'' + temp + '\')">delete</a>';
            $('td:eq(-1)', row).html(string);
        },
    });

    function ConfirmDelete(id) //PEGAWAI_TAMPIL
    {
        var e = confirm('Apakah yakin group akan dihapus?');
        if (e) {
            $.ajax({
                url: '<?php echo base_url('user/delete_group'); ?>/' + id,
                type: 'POST',
                data: {id: id},
                success: function(result) {
                    if (result === 'success') {
                        BukaPage('user/view_group')
                    }
                }
            });
        }
    }
</script>