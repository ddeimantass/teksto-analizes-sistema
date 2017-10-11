<div class="register-box-body">
    <h2 class="login-box-msg">Login</h2>
    <?php if (isset($reg) && !empty($reg)){?>
        <div class="alert alert-success"> <?php echo $reg; ?></div>
    <?php } ?>
    <?php if (isset($prierr) && !empty($prierr)){?>
        <div class="alert alert-danger"> <?php echo $prierr; ?></div>
    <?php } ?>
    <form action="<?php echo base_url()."user/login";?>" method="post">
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Log in</button>
            </div>
        </div>
    </form>
    <br/>
    <a href="<?php echo base_url()."user/forgot";?>">I forgot my password</a><br>
    <a href="<?php echo base_url()."user/register";?>" >Sign up</a>
</div>

