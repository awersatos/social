<div id="user-page">
<?php
echo '<h1>' . $user->name . '</h1>';

echo '<a class="btn btn-primary follow-btn" href="/site/user-follow?id='
. $user->id
. '&action='
. (($status == 'f') ? 'u' : 'f')
. '">'
. (($status == 'f') ? 'Unfollow' : 'Follow')
    . '</a>';

foreach ($posts as $post){
    echo '<div class="post">'
        . $post['message']
        . '</div>';
}

?>
</div>
