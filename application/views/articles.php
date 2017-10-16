<div class="content-wrapper">
    <section class="content-header">
        <h1>Articles</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Articles data table</h3>
                    </div>
                    <div class="box-body">
                        <table id="articles" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Summary</th>
<!--                                <th>Content</th>-->
                                <th>Portal</th>
<!--                                <th>Category</th>-->
<!--                                <th>Author</th>-->
<!--                                <th>Image link</th>-->
                                <th>Link</th>
                                <th>Date</th>
                                <th>Comment link</th>
<!--                                <th>Source</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($articles as $key => $article){
                                echo "<tr><td>".$article->id."</td>";
                                echo "<td>".$article->title."</td>";
                                echo "<td>".$article->summary."</td>";
//                                echo "<td>".$article->content."</td>";
                                echo "<td>".$portals[$article->portal_id]->name."</td>";
                                //echo "<td>".isset($categories[$article->category_id]) ? $categories[$article->category_id]->name : ''."</td>";
                                //echo "<td>".isset($authors[$article->author_id]) ? $authors[$article->author_id]->name : ''."</td>";
                                //echo "<td>".$article->image_url."</td>";
                                echo "<td>".$article->link."</td>";
                                echo "<td>".$article->date."</td>";
                                echo "<td>".$article->comment_link."</td>";
                                //echo "<td>".isset($sources[$article->source_id]) ? $sources[$article->source_id]->name : ''."</td>";
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
        $('#articles').DataTable();
    });
</script>