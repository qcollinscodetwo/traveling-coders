<?php $root = "/traveling-coders/src/";?>

<div class="search hideSearch container-fluid">
    <div class="form-container row">
        <form autocomplete="off" class="search-form col-lg-12" action="search.php" method="GET">
            <div><input autocomplete="off" type="text" name="search" placeholder="Search"><span class="fa fa-search"></span></div>
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
            <a class="navbar-brand" href=<?php echo $root; ?>>Traveling Coders</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
            <li><a><?php if(isset($_SESSION['userId'])) echo $_SESSION['userId'];?></a></li>
            <li><a href=<?php echo $root; ?>>Homepage</a></li>
            <li><a href="#">Travel</a></li>
            <li><a href="post.php">Life</a></li>
            <li><a href="post.php">Money</a></li>
            <?php
                if(!isset($_SESSION['userId'])) {
                echo "<li><a href='{$root}sw?p=login'><i class='fa fa-sign-in'></i>Login</a></li>";
                } else {
                echo "<li><a href='{$root}sw?p=logout'><i class='fa fa-sign-out'></i>Logout</a></li>";
                echo "<li><a href='{$root}user'><i class='fa fa-cog'></i>Account</a></li>";
                }
            ?>
            <li><a href="<?php echo $root; ?>forums?p=threads" class="search-icon-lnk"><i class="fa fa-newspaper-o"></i> Forums</a></li>
            <li><a href="#" class="search-icon-lnk"><i class="fa fa-search"></i></a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>