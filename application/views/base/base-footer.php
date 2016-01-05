<!-- Bootstrap core JavaScript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="<?= base_url('bootstrap/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('bootstrap/js/typeahead.bundle.min.js'); ?>"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?= base_url('bootstrap/js/ie10-viewport-bug-workaround.js'); ?>"></script>
<!-- Masked Input -->
<script src="<?= base_url('bootstrap/js/jquery.maskedinput.min.js'); ?>"></script>
<!-- Datatables -->
<script src="<?= base_url('bootstrap/plugins/datatables/js/jquery.dataTables.min.js'); ?>"></script>

<!-- Datetimepicker -->
<script src="<?= base_url('bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.js'); ?>"></script>

<!-- Notify -->
<script src="<?= base_url('bootstrap/js/notify.min.js'); ?>"></script>
<script src="<?= base_url('bootstrap/js/metisMenu.min.js'); ?>"></script>

<!-- Morris -->
<script src="<?= base_url('bootstrap/plugins/morris.js/morris.js'); ?>"></script>
<script src="<?= base_url('bootstrap/plugins/morris.js/raphael-min.js'); ?>"></script>


<script src="<?= base_url('bootstrap/plugins/fileinput/js/fileinput.min.js'); ?>"></script>
<script src="<?= base_url('bootstrap/plugins/fileinput/js/plugins/canvas-to-blob.js'); ?>"></script>





<div class="modal"></div>
<style>
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        2;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 250, 250, 250, 0 ) 
            url('bootstrap/img/ajax-loader.gif') 
            50% 15px 
            no-repeat;
    }body.loading {
        overflow: hidden;   
    }
    body.loading .modal {
        display: block;
    }
    a{
        text-decoration: none;
        cursor: pointer;
    }
</style>
<script>
    $body = $("body");
    $(document).on({
        ajaxStart: function() {
            $body.addClass("loading");
        },
        ajaxStop: function() {
            $body.removeClass("loading");
        }
    });
</script>


<script>
    function updateClock( )
    {
        var current = new Date( );
        var curHours = current.getHours( );
        var curMinutes = current.getMinutes( );
        var curSeconds = current.getSeconds( );
        var curDate = current.getDate()
        var curMonth = current.getMonth()
        var curYear = current.getFullYear()
        curMinutes = (curMinutes < 10 ? "0" : "") + curMinutes;
        curSeconds = (curSeconds < 10 ? "0" : "") + curSeconds;
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];
        var currentTimeString = curDate + " " + monthNames[curMonth] + " " + curYear + ", " + curHours + ":" + curMinutes + ":" + curSeconds;
        $('#jam').html(currentTimeString);
    }

    $(document).ready(function()
    {
        setInterval('updateClock()', 1000);
    });

    BukaPage('<?= @$this->input->get('page') == '' ? 'dashboard' : $this->input->get('page'); ?>');
    function BukaPage(page) //PEGAWAI_TAMPIL
    {
        $(".isi").load('<?= base_url(); ?>' + page);
    }
</script>