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
            Update Query Details
            <small>Updation of user queries</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Queries</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <?php echo ($message) ? '<div class="alert alert-info">'.$message.'</div>' : '' ?>
          <?php echo ($errors) ? '<div class="alert alert-danger">'.$errors.'</div>' : '' ?>
          

          <?php $attributes = 'role="form"';
          echo form_open_multipart(uri_string(),$attributes);?>
          <div class="box">            
            <div class="box-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                  <label for="doc">Query Regarding</label>
                    <input type="text" value="<?php echo $query_info->doc ?>" class="form-control" readonly>
                  </div>
                  <div class="form-group">
                  <label for="email">Email</label>
                    <input type="text" value="<?php echo $query_info->email ?>" class="form-control" readonly>
                  </div>
                  <div class="form-group">
                  <label for="time">Submission Date & Time</label>
                    <input type="text" value="<?php echo $query_info->time ?>" class="form-control" readonly>
                  </div> 
                  <div class="form-group">
                  <label for="message">Message Details</label>
                    <textarea type="text" rows="3" cols="3" class="form-control" readonly><?php echo $query_info->message ?></textarea>
                  </div>                 
                </div>

                <div class="col-lg-6">
                  
                  <div class="form-group">
                  <label for="answer">Response Message</label>
                    <textarea type="text" name="answer" rows="3" cols="3" class="form-control"></textarea>
                    <?php echo form_error('answer'); ?>
                  </div>
                  <div class="form-group">
                  <label>Response Upload</label>
                    <input type="file" name="userfile" id="userfile" required/>
                    <span class="text-red"><br /> * Allowed file size is 3MB and file type is doc.</span>
                    
                  </div>
                </div>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">

              <?php $js = 'class="btn btn-primary"'; echo form_submit('submit', 'Update & Send Email',$js);?>
              <?php $attributes = 'class="btn bg-olive"'; echo anchor('user/index', 'Back',$attributes)?>
            </div>
          </div><!-- /.box -->
          <?php echo form_close();?>
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