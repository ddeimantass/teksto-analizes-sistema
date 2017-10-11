<div class="content-wrapper">
    <section class="content-header">
        <h1>Users</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
        </ol>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Articles data table</h3>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $key => $user){
                                    echo "<tr><td>".$user->id."</td>";
                                    echo "<td>".$user->name."</td>";
                                    echo "<td>".$user->email."</td>";
                                    echo "<td>".$roles[$user->role_id]."</td>";
                                    echo "<td>".$user->date."</td></tr>";
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
    });

</script>
