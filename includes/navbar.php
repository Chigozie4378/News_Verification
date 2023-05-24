<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top justify-content-center">
    <a class="navbar-brand" href="#">Logo</a>
    <ul class="navbar-nav mx-auto">
        <li class="nav-item  <?php if ($_SERVER['PHP_SELF'] == '/news_verification/views/index.php'){ echo "active"; } ?> ">
            <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item  <?php if ($_SERVER['PHP_SELF'] == '/news_verification/views/train_logistic.php' OR $_SERVER['PHP_SELF'] == '/news_verification/views/train_decision_tree.php'  OR $_SERVER['PHP_SELF'] == '/news_verification/views/train_random_forest.php'  OR $_SERVER['PHP_SELF'] == '/news_verification/views/train_support_vector.php'){ echo "active"; } ?> ">
            <a class="nav-link" href="train_logistic.php">Train Model</a>
        </li>
        <li class="nav-item <?php if ($_SERVER['PHP_SELF'] == '/news_verification/views/paste_news.php' OR $_SERVER['PHP_SELF'] == '/news_verification/views/upload.php'){ echo "active"; } ?> ">
            <a class="nav-link" href="paste_news.php">Verify News</a>
        </li>
    </ul>
</nav>
