<div class="content-wrapper">
    <section class="content-header">
        <h1>Templates settings</h1>
    </section>


    <?php if(isset($template)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal" id="template">
                    <p class="col-xs-12 mainTitle" >
                        <a data-toggle="tooltip" title="back" class="back" href="<?php echo base_url("admin/templates"); ?>">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <?php echo $portal->name; ?> template
                        <a data-toggle="tooltip" title="delete template" class="trash" href="#">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <div id="deletePopUp">
                            <div id="delete">
                                <p>Are you sure you want to delete this template?</p>
                                <div>
                                    <p class="delete">Detete</p>
                                    <p class="cancel">Cancel</p>
                                </div>
                            </div>
                        </div>
                    </p>

                    <?php foreach($template as $key => $attr){
                            if($key == 'date_modified'){continue;}
                        ?>
                        <div class="col-xs-12 form-group">
                            <?php if($key == 'id' ){ ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                            <?php } elseif($key == 'portal_id') { ?>
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
                                </div>
                            <?php }else{ ?>
                                <label for="<?php echo $key; ?>" class="col-sm-3 control-label" ><?php echo $key; ?></label>
                                <div class="col-sm-6" >
                                    <?php if($key == "status"){
                                        echo $attr == 0 ? '<i class="fa fa-toggle-off" aria-hidden="true"></i>' : '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
                                        echo '<input type="hidden" name="'.$key.'" value="'.$attr.'">';
                                    }
                                    else{ ?>
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
    <?php if(isset($templates)){ ?>
        <section class="content" id="mainAll">
            <?php if(isset($error)){ ?>
                <div class="row">
                    <p class="alert alert-danger col-xs-12"><?php echo $error ; ?></p>
                </div>
            <?php } ?>
            <div class="row blockWrap">
                <?php foreach($templates as $key => $template){ ?>
                <div class="col-xs-6 col-md-4 col-lg-3 block">
                    <a class="portal" href="<?php echo base_url("admin/templates?template=" . $template->id); ?>">
                        <div class="portal">
                            <?php if (!empty($portals[$template->portal_id]->logo)) {
                                    echo '<img class="portalImg" src="' . $portals[$template->portal_id]->logo . '" >';
                                } else {
                                    echo '<i class="fa fa-file-o" aria-hidden="true"></i><p>Empty</p>';
                                }
                            ?>
                            </div>
                        </a>
                    </div>
                <?php } ?>
                <div class="col-xs-6 col-md-4 col-lg-3 block">
                    <div id="addTemplate" class="addMain">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <p>Add</p>
                        <p>Template</p>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#template input, #template select").change(function(){
            saveData();
        });
        $("#addTemplate").click(function(){
            $.post("<?php echo base_url('admin/templates'); ?>", {add: "template"}).done(function() {
                $('section.content').load('<?php echo base_url('admin/templates'); ?> section.content > *');
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
                url: "<?php echo base_url('admin/templates'); ?>",
                data: $('#template').serialize(),
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
                url: "<?php echo base_url('admin/templates'); ?>",
                data: {"delete": "delete", "id": "<?php echo $template->id; ?>"},
                success: function(data) {
                    window.location.href = "<?php echo base_url('admin/templates'); ?>";
                }
            });
        });

    });

</script>