<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Legistify | Query Form</title>
    <?php $this->load->view('../../__inc/head'); ?>

  </head>
  <body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="#"><b>Legistify</b>Query</a>
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">Query for legal documents</p>

        <?php echo ($errors) ? '<div class="alert alert-danger">'.$errors.'</div>' : '' ?>
        <?php echo ($message) ? '<div class="alert alert-info">'.$message.'</div>' : '' ?>

        <?php $attributes = array('id' => 'queryform');
          echo form_open("user/user_query",$attributes);?>

          <div class="form-group">
          <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email"/>
            <?php echo form_error('email'); ?>
          </div>

          <div class="form-group">
            <label for="document">Regarding Document</label>
            <?php $js = 'class="form-control"';
            echo form_dropdown('document',$doc_list,0,$js); ?>
            <?php echo form_error('document'); ?>
          </div>

          <div class="form-group">
          <label for="document">Query Details</label>
            <textarea class="form-control" name="details" rows="3" cols="3" placeholder="Enter the details"/></textarea>
            <?php echo form_error('details'); ?>
          </div>

          <div class="row">
          <div class="col-xs-6">
          <?php $attributes = 'class="btn btn-warning btn-block btn-flat"'; echo anchor('user/login', 'Login Form',$attributes)?>
            </div>          
            <div class="col-xs-6">
            <?php $js = 'class="btn btn-primary btn-block btn-flat"';
            echo form_submit('submit', 'Submit Query',$js);?>
            </div><!-- /.col -->
          </div>
        </form> 
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <?php $this->load->view('../../__inc/scripts'); ?>
  </body>
</html>