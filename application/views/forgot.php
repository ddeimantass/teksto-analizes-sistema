<div class="register-box-body">
    <h2 class="login-box-msg">Remind password</h2>
    <?php if (isset($remerr) && !empty($remerr)){?>
        <div class="alert alert-danger"> <?php echo $remerr; ?></div>
    <?php } ?>
    <form action="<?php echo base_url()."user/forgot";?>" method="post">
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Remind</button>
            </div>
        </div>
    </form>
    <br/>
    <a href="<?php echo base_url();?>" >Log in</a><br>
    <a href="<?php echo base_url()."user/register";?>" >Sign up</a>
</div>

