<h1>My followers</h1>
<h2>Total: <?php echo count($followers); ?></h2>
<div id="followers">
    <?php
    foreach ($followers as $follower) {
        echo '<div class="user-item">'
            . '<a  href="/site/user?id='
            . $follower['follower_id']
            . '">'
            . $follower['user']
            . '</a>'
            . '</div>';
    }
    ?>
</div>
