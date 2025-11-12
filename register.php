<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
 <script>
    start_loader()
  </script>
  <style>
    html, body{
        width:100%;
        height:100% !important;
    }
    body{
      background-image: url("<?php echo base_url . 'uploads/Cover.jpg' ?>");
      background-size:cover;
      background-repeat:no-repeat;
      backdrop-filter: contrast(1);
      overflow-x:hidden
    }
    #page-title{
      text-shadow: 6px 4px 7px black;
      font-size: 3.5em;
      color: #fff4f4 !important;
      background: #8080801c;
    }
  </style>
<body class="">
  <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100">
  <h1 class="text-center text-white px-4 py-5" id="page-title"><b><?php echo $_settings->info('name') ?></b></h1>
  <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
    <!-- /.login-logo -->
    <div class="card card-navy my-2 rounded-0">
      <div class="card-header rounded-0">
          <h4 class="card-title">Registration</h4>
      </div>
      <div class="card-body rounded-0">
        <form id="register-form" action="" method="post">
            <input type="hidden" name="id">
            <input type="hidden" name="type" value="2">
          <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                      <label for="firstname" class="control-label">First Name</label>
                      <input type="text" class="form-control form-control-sm rounded-0" required name="firstname" id="firstname">
                  </div>
                  <div class="form-group">
                      <label for="middlename" class="control-label">Middle Name</label>
                      <input type="text" class="form-control form-control-sm rounded-0" name="middlename" id="middlename">
                  </div>
                  <div class="form-group">
                      <label for="lastname" class="control-label">Last Name</label>
                      <input type="text" class="form-control form-control-sm rounded-0" required name="lastname" id="lastname">
                  </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                      <label for="username" class="control-label">Username</label>
                      <input type="text" class="form-control form-control-sm rounded-0" required name="username" id="username">
                  </div>
                  <div class="form-group">
                      <label for="password" class="control-label">Password</label>
                      <div class="input-group input-group-sm">
                          <input type="password" class="form-control form-control-sm rounded-0" required name="password" id="password">
                          <button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="cpassword" class="control-label">Confirm Password</label>
                      <div class="input-group input-group-sm">
                          <input type="password" class="form-control form-control-sm rounded-0" reqiured="" id="cpassword">
                          <button tabindex="-1" class="btn btn-outline-secondary btn-sm rounded-0 pass_view" type="button"><i class="fa fa-eye-slash"></i></button>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-8">
              <a href="./login.php">Already hava an Account</a>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <!-- /.social-auth-links -->

        <!-- <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p> -->
        
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
  </div>
  <!-- jQuery -->
  <script src="<?= base_url ?>plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url ?>dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
    $('.pass_view').click(function(){
        var input = $(this).siblings('input')
        var type = input.attr('type')
        if(type == 'password'){
            $(this).html('<i class="fa fa-eye"></i>')
            input.attr('type','text').focus()
        }else{
            $(this).html('<i class="fa fa-eye-slash"></i>')
            input.attr('type','password').focus()
        }
    })
    $('#register-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
        var el = $('<div>')
            el.addClass('alert alert-danger err_msg')
            el.hide()
        $('.err_msg').remove()
        if($('#password').val() != $('#cpassword').val()){
            el.text('Password does not match')
            _this.prepend(el)
            el.show('slow')
            $('html, body').scrollTop(0)
            return false;
        }
        if(_this[0].checkValidity() == false){
            _this[0].reportValidity();
            return false;
        }
        start_loader()
        var formData = $(this).serialize();
        $.ajax({
            url:_base_url_+"classes/Users.php?f=registration",
            method:'POST',
            type:'POST',
            data:formData,
            dataType:'json',
            cache:false,
            error:function(xhr, status, error){
                console.log('AJAX Error:', xhr, status, error)
                console.log('Response Text:', xhr.responseText)
                console.log('Status Code:', xhr.status)
                var errorMsg = 'An error occurred';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if(resp.msg) errorMsg = resp.msg;
                } catch(e) {
                    if(xhr.responseText) errorMsg = xhr.responseText.substring(0, 200);
                }
                alert('Error: ' + errorMsg);
                end_loader()
            },
            success:function(resp){
                try {
                    if(typeof resp === 'string') {
                        resp = JSON.parse(resp);
                    }
                    if(resp.status == 'success'){
                        location.href = ('./login.php')
                    }else if(!!resp.msg){
                        el.html(resp.msg)
                        el.show('slow')
                        _this.prepend(el)
                        $('html, body').scrollTop(0)
                    }else{
                        alert('An error occurred: ' + (resp.msg || 'Unknown error'))
                        console.log(resp)
                    }
                } catch(e) {
                    console.error('Response parsing error:', e, resp);
                    alert('An error occurred while processing the response')
                }
                end_loader()
            }
        })
    })
  })
</script>
</body>
</html>