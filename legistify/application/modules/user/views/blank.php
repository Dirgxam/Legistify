<!-- For Listing User Query Details -->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Legistify | Dashboard</title>
    <!-- include head -->
    <?php $this->load->view('../../__inc/head'); ?>
    <?php echo link_tag('assets/css/datatables/jquery.dataTables.css'); ?>
  </head>
  <body class="skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
      
      <header class="main-header">
      <!-- include header -->
      <?php $this->load->view('../../__inc/header'); ?>
      </header>

      <!-- =============================================== -->

      <!-- include Left sidebar -->
     <?php $this->load->view('../../__inc/side_nav_bar'); ?>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            User Query Details
            <small>List of user queries</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Queries</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <?php echo ($this->session->flashdata('message')) ? '<div class="alert alert-info">'.$this->session->flashdata('message').'</div>' : '' ?>
          
          <div class="box">            
            <div class="box-body">
              <table id="printview" class="display table table-bordered table-condensed">
              <thead>
              <tr>                
               <th>Query Document</th>
               <th>Message Details</th>
               <th>Email</th>
               <th>Date & Time</th>               
               <th>Action</th>
              </tr>
              </thead>
              </table>
            </div><!-- /.box-body -->
            
          </div><!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- include footer -->      
      <?php $this->load->view('../../__inc/footer'); ?>
    </div><!-- ./wrapper -->

<!-- include script -->
<?php $this->load->view('../../__inc/scripts'); ?>
<script src="<?php echo base_url(); ?>assets/js/datatable/jquery.dataTables.js"></script>
<script type="text/javascript">
'use strict';
  $(document).ready(function(){

    var oTable = $('#printview').DataTable( {
               dom: 'T<"clear">lfrtip',
               "paging":   false,
               "ordering": false,
               "filter": false,
               "info":     false    
           });

    var test_token = '<?php echo $this->security->get_csrf_hash(); ?>';
    var ajax_get_docs = function() {
        $.ajax({
              url: "user/ajax_get_querylist", 
              type: "POST", //The type which you want to use: GET/POST
              data: {csrf_test_name:test_token},
              dataType: "json", //Return data type (what we expect).
              success: function(j) {
                if (j.valid === "true") {
                  oTable.clear();
                  var msg = j.qdata;
                  console.log(j.qdata);              
               for (var i = 0; i < msg.length; i++) { 

                var up_link = '<a class="btn btn-primary btn-xs" href="<?php echo base_url("user/update_querylist")."/" ?>'+msg[i].id+'">Edit</a>';
                   oTable.row.add([msg[i].doc,msg[i].message,msg[i].email,msg[i].time,up_link]).draw();
                  }
                }else{
                  oTable.clear();
                  oTable.draw();
                }
              },
              error: function(e){
                  console.log(e.responseText);
              }
            });
    }
    ajax_get_docs();


  });
</script>
  </body>
</html>