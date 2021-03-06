

<div class="search hideSearch container-fluid">
    <div class="form-container row">
        <form autocomplete="off" class="search-form col-lg-12" action="/search.php" method="GET">
            <div><input autocomplete="off" type="text" name="q" placeholder="Search..."></div>
        </form>
    </div>
</div>
<header>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Traveling Coders</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
            <li><a href="/">Homepage</a></li>
            <li><a href="/blogs.php">Blogs</a></li>
            <li><a href="/forums.php">Forums</a></li>
            <?php

                $url_page = "forums";
                $text = "boards";

                $url = urlencode($url_page);
                $url_encoded = $url . "?p=" . urlencode($text);

                if(!isset($_SESSION['userId'])) {
                echo "<li><a href='login.php'>Login</a></li>";
                } else {
                echo "<li><a href='/sw.php?p=logout'>Logout</a></li>";
                echo "<li><a href='/user/'>Account</a></li>";
                }
            ?>
            <li><a href="" class="search-icon-lnk"><i class="fa fa-search"></i></a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>