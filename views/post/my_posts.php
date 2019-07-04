<h1>This my posts list</h1>
<div id="my-post">
<?php
foreach ($posts as $post){
    echo '<div class="post">'
        . $post['message']
        . '<a style="float: right" href="/post/delete?id='. $post['id']
        .'">Delete</a>'
        . '</div>';
}
?>
</div>
