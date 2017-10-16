<div class="content-wrapper">
    <section class="content-header">
        <h1>Main</h1>
    </section>

    <?php if(isset($portal)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal">
                    <p class="col-xs-12 mainTitle" ><a class="back" href="<?php echo base_url("admin/main"); ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><?php echo $portal->name; ?></p>
                    <?php foreach($portal as $key => $attr){
                        if( $key == 'id'){continue;}
                        ?>
                        <div class="col-xs-12 form-group">
                            <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                            <div class="col-sm-6" >
                                <?php if($key == '2017-10-09 19:26:38'){ ?>
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                <?php }else{ ?>
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php foreach($template as $key => $attr){
                            if( $key == 'id' || $key == 'portal_id'){continue;}
                        ?>
                        <div class="col-xs-12 form-group">
                            <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                            <div class="col-sm-6" >
                                <?php if($key == '2017-10-09 19:26:38'){ ?>
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                <?php }else{ ?>
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </section>
    <?php } ?>
    <?php if(isset($cron)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal">
                    <?php foreach($cron as $key => $attr){ ?>
                        <div class="col-xs-12 form-group">
                            <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </section>
    <?php } ?>
    <?php if(isset($portals)){ ?>
        <section class="content">
            <?php if(isset($error)){ ?>
                <div class="row">
                    <p class="alert alert-danger col-xs-12"><?php echo $error ; ?></p>
                </div>
            <?php } ?>
            <div class="row">
                <?php foreach($portals as $key => $portal){ ?>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <a class="portal" href="<?php echo base_url("admin/main?portal=".$portal->name); ?>">
                            <div class="portal">
                                <img class="portalImg" src="<?php echo $portal->image; ?>" >
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>

</div>