<div class="content-wrapper">
    <section class="content-header">
        <h1>Main</h1>
    </section>

    <?php if(isset($portal)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal" id="portal">
                    <p class="col-xs-12 mainTitle" ><a class="back" href="<?php echo base_url("admin/main"); ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><?php echo $portal->name; ?></p>
                    <?php foreach($portal as $key => $attr){
                            if($key == 'id'){continue;}
                        ?>
                        <div class="col-xs-12 form-group">
                            <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                            <div class="col-sm-6" >
                                <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                            </div>
                        </div>
                    <?php } ?>
                    <?php foreach($template as $key => $attr){
                            if($key == 'date_modified'){continue;}
                        ?>
                        <?php if($key == 'id' || $key == 'portal_id'){ ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                        <?php }else{ ?>
                            <div class="col-xs-12 form-group">
                                <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                <div class="col-sm-6" >
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </form>
            </div>
        </section>
    <?php } ?>
    <?php if(isset($cron)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal">
                    <p class="col-xs-12 mainTitle" ><a class="back" href="<?php echo base_url("admin/main"); ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><?php echo $cron->title; ?></p>
                    <?php foreach($cron as $key => $attr){ ?>
                        <?php if($key == 'id' || $key == 'portal_id'){ ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                        <?php }else{ ?>
                            <div class="col-xs-12 form-group">
                                <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                </div>
                            </div>
                        <?php } ?>
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
                        <a class="portal" href="<?php echo base_url("admin/main?portal=".$portal->id); ?>">
                            <div class="portal">
                                <img class="portalImg" src="<?php echo $portal->logo; ?>" >
                            </div>
                        </a>
                    </div>
                <?php } ?>
                <?php foreach($crons as $key => $cron){ ?>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <a class="cron" href="<?php echo base_url("admin/main?cron=".$cron->id); ?>">
                            <div class="cron">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <p><?php echo $cron->title; ?></p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
                <div class="col-xs-12 col-sm-4 col-md-2">
                    <div id="addTemplate" class="addMain">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <p>Add</p>
                        <p>Template</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-2">
                    <div id="addCron" class="addMain">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <p>Add</p>
                        <p>Cron</p>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#portal input").change(function(){
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('admin/main'); ?>",
                data: $('#portal').serialize(),
                success: function(data) {
                    if(data) {
                        var data = JSON.parse(data);
                        if (data.error) {
                            alert(data.error.msg);
                            throw data.error.msg;
                        }
                    }
                }
            });
        });
        $("#addTemplate").click(function(){
            $.post("<?php echo base_url('admin/main'); ?>", {add: "template"})
        });
        $("#addCron").click(function(){
            $.post("<?php echo base_url('admin/main'); ?>", {add: "cron"})
        });
    });
</script>