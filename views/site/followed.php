<h1>My follows</h1>
<h2>Total: <?php echo count($followers); ?></h2>
<div id="followers">
    <?php
    foreach ($followers as $follower) {
        echo '<div class="user-item">'
            . '<a  href="/site/user?id='
            . $follower['user_id']
            . '">'
            . $follower['user']
            . '</a>'
            . '</div>';
    }
    ?>
</div>
