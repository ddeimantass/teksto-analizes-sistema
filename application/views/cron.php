<?php
$checkFor = array("0" => "new", "1" => "old");
$weekDay= array("1" => "Monday", "2" => "Tuesday", "3" => "Wednesday", "4" => "Thursday", "5" => "Friday", "6" => "Saturday", "0" => "Sunday",);
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Cron settings</h1>
    </section>
    <?php if(isset($cron)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal" id="cron">
                    <p class="col-xs-12 mainTitle" >
                        <a data-toggle="tooltip" title="back" class="back" href="<?php echo base_url("admin/cron"); ?>">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <?php echo isset($portal->name) ? $portal->name : "Empty"; ?> cronjob
                        <a data-toggle="tooltip" title="delete cron" class="trash" href="#">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    <div id="deletePopUp">
                        <div id="delete">
                            <p>Are you sure you want to delete this cronjob?</p>
                            <div>
                                <p class="delete">Detete</p>
                                <p class="cancel">Cancel</p>
                            </div>
                        </div>
                    </div>
                    </p>
                    <?php foreach($cron as $key => $attr){
                        ?>
                        <div class="col-xs-12 form-group">
                            <?php if($key == 'id' ){ ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                            <?php } else { ?>

                                    <?php if($key == "status"){ ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                        <div class="col-sm-6" >
                                    <?php
                                        echo $attr == 0 ? '<i class="fa fa-toggle-off" aria-hidden="true"></i>' : '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
                                        echo '<input type="hidden" name="'.$key.'" value="'.$attr.'">';
                                    }
                                    elseif($key == "log"){ ?>
                                            <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                            <div class="col-sm-6" >
                                                <?php
                                                echo $attr == 0 ? '<i class="fa fa-toggle-off" aria-hidden="true"></i>' : '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
                                                echo '<input type="hidden" name="'.$key.'" value="'.$attr.'">';
                                                }
                                    elseif($key == 'portal_id') { ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" >portal</label>
                                        <div class="col-sm-6" >
                                            <select class="form-control" value="<?php echo $attr; ?>" name="<?php echo $key; ?>" id="<?php echo $key; ?>" >
                                                <?php
                                                if($portals[$attr]->name == "new"){
                                                    echo "<option value='".$portals[$attr]->id."'>".$portals[$attr]->name."</option>";
                                                }

                                                foreach($portals as $p){
                                                    if($p->status > 0){
                                                        if($p->name != "new"){
                                                            if($attr == $p->id){
                                                                echo "<option selected value='".$p->id."'>".$p->name."</option>";
                                                            }
                                                            else{
                                                                echo "<option value='".$p->id."'>".$p->name."</option>";
                                                            }

                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                    <?php }
                                    elseif($key == 'checkFor') { ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" >check for</label>
                                        <div class="col-sm-6" >
                                            <select class="form-control" value="<?php echo $attr; ?>" name="<?php echo $key; ?>" id="<?php echo $key; ?>" >
                                                <?php
                                                foreach($checkFor as $forKey => $for){
                                                    if($forKey == $attr){
                                                        echo "<option selected value='".$forKey."'>".$for."</option>";
                                                    }
                                                    else{
                                                        echo "<option value='".$forKey."'>".$for."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    <?php }
                                    elseif($key == 'ScrapingInterval_weekDayFrom') { ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" >weekday from</label>
                                        <div class="col-sm-6" >
                                            <select class="form-control" value="<?php echo $attr; ?>" name="<?php echo $key; ?>" id="<?php echo $key; ?>" >
                                                <?php
                                                foreach($weekDay as $weekKey => $day){
                                                    if($weekKey == $attr){
                                                        echo "<option selected value='".$weekKey."'>".$day."</option>";
                                                    }
                                                    else{
                                                        echo "<option value='".$weekKey."'>".$day."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    <?php }
                                        elseif($key == 'ScrapingInterval_weekDayTo') { ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" >weekday to</label>
                                        <div class="col-sm-6" >
                                            <select class="form-control" value="<?php echo $attr; ?>" name="<?php echo $key; ?>" id="<?php echo $key; ?>" >
                                                <?php
                                                foreach($weekDay as $weekKey => $day){
                                                    if($weekKey == $attr){
                                                        echo "<option selected value='".$weekKey."'>".$day."</option>";
                                                    }
                                                    else{
                                                        echo "<option value='".$weekKey."'>".$day."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    <?php } else{ ?>
                                        <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                        <div class="col-sm-6" >
                                        <input type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </section>
    <?php } ?>
    <?php if(isset($crons)){ ?>
        <section class="content" id="mainAll">
            <?php if(isset($error)){ ?>
                <div class="row">
                    <p class="alert alert-danger col-xs-12"><?php echo $error ; ?></p>
                </div>
            <?php } ?>
            <div class="row blockWrap">
                <?php foreach($crons as $key => $cron){?>
                    <div class="col-xs-6 col-sm-4 col-md-3 block">
                        <a class="cron" href="<?php echo base_url("admin/cron?cron=".$cron->id); ?>">
                            <div class="cron">
                                <?php if (isset($logos[$key]) && !empty($logos[$key])) {
                                    echo '<img class="portalImg" src="' . $logos[$key] . '" >';
                                } else {
                                    echo '<i class="fa fa-clock-o" aria-hidden="true"></i><p>Empty</p>';
                                }
                                ?>
                            </div>
                        </a>
                        <p class="cronP" ><?php echo $checkFor[$cron->checkFor]; ?></p>
                    </div>

                <?php } ?>
                <div class="col-xs-6 col-sm-4 col-md-3 block">
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
        $("#cron input, #cron select ").change(function(){
            saveData();
        });
        $("#addCron").click(function(){
            $.post("<?php echo base_url('admin/cron'); ?>", {add: "cron"}).done(function() {
                $('section.content').load('<?php echo base_url('admin/cron'); ?> section.content > *');
            });

        });
        $(".fa-toggle-off, .fa-toggle-on").click(function(){
            $(this).toggleClass("fa-toggle-off");
            $(this).toggleClass("fa-toggle-on");
            if($(this).hasClass("fa-toggle-off")){
                $(this).siblings("input").val("0");
            }
            else{
                $(this).siblings("input").val("1");
            }
            saveData();
        });

        function saveData(){
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('admin/cron'); ?>",
                data: $('#cron').serialize(),
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
        }
        $(".trash").click(function(){
            $("#deletePopUp").show();
        });
        $("#deletePopUp .cancel").click(function(){
            $("#deletePopUp").hide();
        });
        $("#deletePopUp .delete").click(function(){
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('admin/cron'); ?>",
                data: {"delete": "delete", "id": "<?php echo $cron->id; ?>"},
                success: function(data) {
                    window.location.href = "<?php echo base_url('admin/cron'); ?>";
                }
            });
        });
    });
</script>