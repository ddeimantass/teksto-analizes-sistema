<div class="content-wrapper">
    <section class="content-header">
        <h1>Portal settings</h1>
    </section>

    <?php if(isset($portal)){ ?>
        <section class="content">
            <div class="row">
                <form class="form-horizontal" id="portal">
                    <p class="col-xs-12 mainTitle" >
                        <a data-toggle="tooltip" title="back" class="back" href="<?php echo base_url("admin/portals"); ?>">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <?php echo $portal->name; ?>
                        <a data-toggle="tooltip" title="delete portal" class="trash" href="#">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <div id="deletePopUp">
                            <div id="delete">
                                <p>Are you sure you want to delete this portal?</p>
                                <div>
                                    <p class="delete">Detete</p>
                                    <p class="cancel">Cancel</p>
                                </div>
                            </div>
                        </div>
                    </p>
                    <?php foreach($portal as $key => $attr){
                        ?>
                        <div class="col-xs-12 form-group">
                            <?php if($key == 'id' ){ ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $attr; ?>">
                            <?php } else { ?>
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
    <?php if(isset($portals)){ ?>
        <section class="content" id="mainAll">
            <?php if(isset($error)){ ?>
                <div class="row">
                    <p class="alert alert-danger col-xs-12"><?php echo $error ; ?></p>
                </div>
            <?php } ?>
            <div class="row blockWrap">
                <?php foreach($portals as $key => $portal){ if($portal->name == 'new') {continue;}?>

                    <div class="col-xs-6 col-sm-4 col-md-3 block">
                        <a class="portal" href="<?php echo base_url("admin/portals?portal=".$portal->id); ?>">
                            <div class="portal">
                                <?php if (!empty($portal->logo)) {
                                    echo '<img class="portalImg" src="' . $portal->logo . '" >';
                                } else {
                                    echo '<i class="fa fa-rss" aria-hidden="true"></i><p>Empty</p>';
                                }
                                ?>
                            </div>
                        </a>
                        <?php if(isset($templates[$portal->id])){?>
                            <a class="editTemplate" href="<?php echo base_url("admin/templates?template=").$templates[$portal->id]->id;?>" ><i class="fa fa-file-code-o" aria-hidden="true"></i> Edit template</a>
                        <?php } ?>
                    </div>

                <?php } ?>
                <div class="col-xs-6 col-sm-4 col-md-3 block">
                    <div id="addPortal" class="addMain">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <p>Add</p>
                        <p>Portal</p>
                    </div>
                </div>
            </div>

        </section>
    <?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#portal input").change(function(){
            saveData();
        });
        $("#addPortal").click(function(){
            $.post("<?php echo base_url('admin/portals'); ?>", {add: "portal"}).done(function() {
                $('section.content').load('<?php echo base_url('admin/portals'); ?> section.content > *');
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
                url: "<?php echo base_url('admin/portals'); ?>",
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
                url: "<?php echo base_url('admin/portals'); ?>",
                data: {"delete": "delete", "id": "<?php echo $portal->id; ?>"},
                success: function(data) {
                    window.location.href = "<?php echo base_url('admin/portals'); ?>";
                }
            });
        });
    });
</script>