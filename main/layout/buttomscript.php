<!-- jQuery 2.2.3 -->
<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<!-- Logout confirmation handler -->
<script>
  $(function () {
    // Attach handler once
    $('.logout-link').off('click.logout').on('click.logout', function (e) {
      e.preventDefault();
      var href = $(this).attr('href');
      $('#logoutConfirmBtn').data('href', href);
      $('#logoutConfirmModal').modal('show');
    });

    $('#logoutConfirmBtn').off('click.logout').on('click.logout', function () {
      var target = $(this).data('href');
      if (target) {
        window.location.href = target;
      }
    });
  });
</script>
<!-- SlimScroll -->
<script src="assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/dist/js/demo.js"></script>
<!-- page script -->

 
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>
<script type="text/javascript">

    var minAge = 18;

    function _calcAge() {
        var date = new Date(document.getElementById("date").value);
        var today = new Date();

        var timeDiff = Math.abs(today.getTime() - date.getTime());
        var age1 = Math.ceil(timeDiff / (1000 * 3600 * 24)) / 365;
        return age1;
        alert(age1);
    }

    //Compares calculated age with minimum age and acts according to rules//
    function _setAge() {

        var age = _calcAge();
        //alert("my age is " + age);
        if (age < minAge) {
            alert("You are not allowed into the site. The minimum age is 18!");
        } else

            alert("Welcome to my Site");
        window.open(main.htm, _self);

    }


</script>

</body>
</html>  