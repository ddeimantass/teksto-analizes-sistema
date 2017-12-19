<div class="content-wrapper">
    <section class="content-header">
        <h1>Users</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Users data table</h3>
                    </div>
                    <div class="box-body">
                        <table id="users" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Date</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $key => $user){
                                    echo "<tr><td class='id'>".$user->id."</td>";
                                    echo "<td>".$user->name."</td>";
                                    echo "<td>".$user->email."</td>";
                                    echo "<td>".$roles[$user->role_id]."</td>";
                                    echo "<td>".$user->date."</td>";
                                    echo "<td>";
                                    echo '<input type="hidden" name="deleted" value="'.$user->deleted.'">';
                                    echo $user->deleted == 1 ? '<i class="fa fa-toggle-off" aria-hidden="true"></i>' : '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
                                    echo "</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        $('#users').DataTable();


        $(".fa-toggle-off, .fa-toggle-on").click(function(){
            $(this).toggleClass("fa-toggle-off");
            $(this).toggleClass("fa-toggle-on");
            if($(this).hasClass("fa-toggle-off")){
                $(this).siblings("input").val("1");
            }
            else{
                $(this).siblings("input").val("0");
            }
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('admin/users'); ?>",
                data: { deleted: $(this).parents("tr").find("input[name='deleted']").val(), id: $(this).parents("tr").find(".id").text()}
            });
        });


    });
</script>
