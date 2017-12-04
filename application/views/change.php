<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo isset($name) ? $name : "Profile" ;?></h1>
    </section>
    <section class="content">
        <div id="change" class="change-box-body">
            <h2 class="login-box-msg">Change password</h2>
            <?php if (isset($pass) && !empty($pass)){?>
                <div class="alert alert-success"> <?php echo $pass; ?></div>
            <?php } ?>
            <?php if (isset($passErr) && !empty($passErr)){?>
                <div class="alert alert-danger"> <?php echo $passErr; ?></div>
            <?php } ?>
            <form action="<?php echo base_url()."user/change";?>" method="post">
                <div class="form-group has-feedback">
                    <input type="password" name="oldPassword" class="form-control" placeholder="Current password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="New password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="cpassword" class="form-control" placeholder="Repeat new password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Change</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>