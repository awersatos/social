<h1>This my posts list</h1>
<div id="my-post">
<a href="/post/create" class="btn btn-primary">Create post</a>
<?php
foreach ($posts as $post){
    echo '<div class="post">'
        . $post['message']
        . '<a style="float: right" href="/post/delete?id='. $post['id'] .'">Delete</a>'
        . '</div>';
}
?>
</div>
