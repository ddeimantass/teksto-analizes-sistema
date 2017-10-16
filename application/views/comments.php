<div class="content-wrapper">
    <section class="content-header">
        <h1>Comments</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Comments data table</h3>
                    </div>
                    <div class="box-body">
                        <table id="comments" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Article</th>
                                <th>Portal</th>
                                <th>Likes</th>
                                <th>Dislikes</th>
                                <th>Date</th>
                                <th>IP</th>
                                <th>Content</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($comments as $key => $comment){
                                echo "<tr><td>".$comment->id."</td>";
                                echo "<td>".$articles[$comment->article_id]->title."</td>";
                                echo "<td>".$portals[$comment->portal_id]->name."</td>";
                                echo "<td>".$comment->likes."</td>";
                                echo "<td>".$comment->dislikes."</td>";
                                echo "<td>".$comment->date."</td>";
                                echo "<td>".$comment->ip."</td>";
                                echo "<td>".$comment->content."</td>";
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
        $('#comments').DataTable();
    });
</script>
