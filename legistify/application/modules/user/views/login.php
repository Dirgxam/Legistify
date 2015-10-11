<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Legistify | Log in</title>
    <?php $this->load->view('../../__inc/head'); ?>

  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Legistify</b>Login</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php echo ($errors) ? '<div class="alert alert-danger">'.$errors.'</div>' : '' ?>
        <?php echo ($message) ? '<div class="alert alert-info">'.$message.'</div>' : '' ?>

        <?php $attributes = array('id' => 'loginform');
          echo form_open("user/login",$attributes);?>

          <div class="form-group has-feedback">
            <input type="text" name="identity" class="form-control" placeholder="Username"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">

            <?php $js = 'class="btn btn-primary btn-block btn-flat"';
            echo form_submit('submit', 'Sign In',$js);?>

            </div><!-- /.col -->
            <div class="col-xs-6">
            <?php $attributes = 'class="btn btn-warning btn-block btn-flat"'; echo anchor('user/user_query', 'Query Form',$attributes)?>
            </div>
          </div>

        <?php form_close(); ?>
        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <?php $this->load->view('../../__inc/scripts'); ?>
  </body>
</html>