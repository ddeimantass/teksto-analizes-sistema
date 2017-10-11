<div class="register-box-body">
    <h2 class="login-box-msg">Registration</h2>

    <?php if (isset($regerr) && !empty($regerr)){?>
        <div class="alert alert-danger"> <?php echo $regerr; ?></div>
    <?php } ?>
    <form action="<?php echo base_url()."user/register";?>" method="post">
        <div class="form-group has-feedback">
            <input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>" placeholder="Full name">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="cpassword" class="form-control" placeholder="Retype password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign up</button>
            </div>
        </div>
    </form>
    <br/>
    <a href="<?php echo base_url();?>" class="text-center">Log in</a>
</div>
