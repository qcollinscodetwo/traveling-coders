<?php
include_once "includes/db.php";

function check_query($result) {
    global $connection;
    if(!$result) {
        die("Query Failed: " . mysqli_error($connection));
    }
}

function getIdByName($table, $column, $str) {
    global $connection;
    $query = "SELECT category_id FROM {$table} WHERE {$column}='{$str}'";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getCatNameById($table, $column, $id) {
    global $connection;
    $query = "SELECT category_name FROM {$table} WHERE {$column}='{$id}'";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getById($str, $column, $id) {
    global $connection;
    $query = "SELECT * FROM {$str} WHERE {$column}={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getByIdLength($str, $column, $id) {
    global $connection;
    $query = "SELECT * FROM {$str} WHERE {$column}={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $len = mysqli_num_rows($result);
    return $len;
}

function getAllThreadsByUser($id) {
    global $connection;
    $query = "SELECT * FROM threads WHERE user_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getSingleThread($id) {
    global $connection;
    $query = "SELECT * FROM threads WHERE thread_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function getAllBlogsByUser($id) {
    global $connection;
    $query = "SELECT * FROM blogs WHERE user_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getSingleCat($id) {
    global $connection;
    $query = "SELECT * FROM categories WHERE category_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function getAllCommentsByUser($id) {
    global $connection;
    $query = "SELECT * FROM comments WHERE user_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getSingleThreadBoard($id) {
    global $connection;
    $query = "SELECT * FROM boards WHERE board_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function getSingleUser($id) {
    global $connection;
    $query = "SELECT * FROM users WHERE user_id={$id}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function getAll($str) {
    global $connection;
    $query = "SELECT * FROM {$str}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getAllSort($str, $sort, $order) {
    global $connection;
    $query = "SELECT * FROM {$str} ORDER BY {$sort} {$order}";
    $result = mysqli_query($connection, $query);
    check_query($result);
    return $result;
}

function getIdByUsername($username) {
    global $connection;
    $query = "SELECT user_id FROM users WHERE user_username = '{$username}'";
    $result = mysqli_query($connection, $query);
    check_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['user_id'];
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function limit_blog_preview_content_length($blog_content, $len) {
    $blog_content = strip_tags($blog_content);
    if(strlen($blog_content) > 100) {
        $limitStr = substr($blog_content, 0, $len);
        return $blog_content = substr($limitStr, 0, strrpos($limitStr, ' '));
    }
}

function blogs_preview($order = "ASC") {
    global $connection;
    $blogObj = new Blogs($connection);
    $userObj = new Users($connection);
    $categoriesObj = new Categories($connection);
    $blogs = $blogObj->get_all_blogs_sorted("blog_time", $order);
    while($row = mysqli_fetch_assoc($blogs)) {
        $categoriesObj->set_id($row['category_id']);
        $category = $categoriesObj->get_category_by_id();
        $user = $userObj->get_user_by_id($row['user_id']);
        $user_name_full = ucwords($user['user_name_first'])." ".ucwords($user['user_name_last']);
        $url = '... <a href="/blogs.php?category='.strtolower($category['category_name'])."&blog=".$row['blog_id'].'">Read More</a>';
        $blog_content = limit_blog_preview_content_length($row['blog_content_sect_01'], 200).$url;
        $timeSincePost = time_elapsed_string($row['blog_time']);
        $url = 'profile.php?user='.$user['user_username'];
        echo "<div class='col-xs-12 blog'>
                <div>
                    <figure>
                        <div class='title_author'>
                            <h1>{$row['blog_title']}</h1>
                            <div class='user-info'>
                                <div class='img'></div>
                                <a href={$url}><small>{$user_name_full}</small></a>
                            </div>
                        </div>
                        <div class='row'>
                            <img src='assets/img/{$row['blog_image_preview']}' alt='' class='col-sm-12'>
                            <figcaption class='col-sm-8'>
                                <p>{$blog_content}</p>
                            </figcaption>
                        </div>
                    </figure>
                    <div class='row info'>
                        <div class='col-xs-6 col-sm-6 published'><span>Published</span>: <span>{$timeSincePost}</span></div>
                        <div class='col-xs-6 col-sm-6 info-icons'><div class='comments'><i class='fa fa-comment'></i> <span>{$row['blog_comments_count']}</span></div> <div class='likes'><i class='fa fa-heart'></i> <span>{$row['blog_likes_count']}</span></div></div>
                    </div>
                </div>
            </div>";
    }
}


function bloggers_aside() {
    global $connection;
    $blogObj = new Blogs($connection);
    $categoriesObj = new Categories($connection);
    $blogs = $blogObj->get_all_blogs_sorted("blog_time", "ASC");
    $usersObj = new Users($connection);
    while($row = mysqli_fetch_assoc($blogs)) {
        $categoriesObj->set_id($row['category_id']);
        $category = $categoriesObj->get_category_by_id();
        $blog_id = $row['blog_id'];
        $usersObj->set_id($row['user_id']);
        $user = $usersObj->get_user_by_id();
        $category_name = strtolower($category['category_name']);
        $timeSincePost = time_elapsed_string($row['blog_time']);
        $user_info = ucwords($user['user_name_first'])." ".ucwords($user['user_name_last']);
        $url = 'profile.php?user='.$user['user_username'];
        echo "<li class='col-sm-6 col-md-6 col-lg-12'>
                <div class='users-list-item'>
                    <div class='thumb-wrapper'>
                        <a href={$url}>
                            <img src='assets/img/73.jpg'/>
                        </a>
                    </div>
                    <div class='content-wrapper'>
                            <h3>{$user_info}</h3>
                            <span class='user-info'><small>12 Blogs </small></span>";
                            if($timeSincePost < "5 weeks ago") {
                                    echo "<a href={$url}>
                                            <p><span class='new'>Profile</span></p>
                                          </a>";
                            }
            echo "</div>
                </div>
            </li>";
    };
}

function threads_aside() {
    global $connection;
    $threadObj = new Threads($connection);
    $threads = $threadObj->get_all_threads_sorted("thread_time", "ASC");
    while($row = mysqli_fetch_assoc($threads)) {
        $threadObj->set_id($row['thread_id']);
        $title_thread = ucfirst($row["thread_title"]);
        $timeSincePost = time_elapsed_string($row['thread_time']);
        $comment_count = $threadObj->get_thread_comments_count();
        echo "<li class='col-sm-6 col-md-6 col-lg-12'>
                    <div class='threads-list-item'>
                        <div class='content-wrapper'>
                            <a href='forums.php?board={$row['board_id']}&thread={$row['thread_id']}&comments=1'>
                                <h3>{$title_thread}</h3>
                            </a>";
                            if($timeSincePost < "5 weeks ago") {
                                echo "<div class='comment-count-wrapper'>
                                        <p><span class='new'>NEW</span></p>
                                        <span class='comment-count'><i class='fa fa-comment'></i> {$comment_count}</span>
                                    </div>";
                            } else {
                                echo "<div class='comment-count-wrapper'>
                                        <span class='comment-count'><i class='fa fa-comment'></i> {$comment_count}</span>
                                    </div>";
                            }
                    echo "</div>
                    </div>
            </li>";
    };
}


function social($user_id) {
    global $connection;
    $usersObj = new Users($connection);
    $usersObj->set_id($user_id);
    $user = $usersObj->get_user_by_id();
    if($user['twitter'] != null) {
        echo "<div class='col-lg-1'><a href='{$user['twitter']}'><i class='fa fa-twitter'></i></a></div>";
    }
    if($user['facebook']) {
        echo "<div class='col-lg-1'><a href='{$user['facebook']}'><i class='fa fa-facebook'></i></a></div>";
    }
    if($user['instagram']) {
        echo "<div class='col-lg-1'><a href='{$user['instagram']}'><i class='fa fa-instagram'></i></a></div>";
    }
}

function comments($blog_id) {
    global $connection;
    $commentsObj = new Comments($connection);
    $usersObj = new Users($connection);
    $comments = $commentsObj->get_all_comments_sorted("blog_id", $blog_id);
    while($row = mysqli_fetch_assoc($comments)) {
        $usersObj->set_id($row['user_id']);
        $user = $usersObj->get_user_by_id();
        $time = $row['comment_time'];
        $timeSincePost = time_elapsed_string($time);
        echo "<div class='comment'>
            <h1>{$user['user_username']}</h1>
            <p class='date'><span>$timeSincePost</span></p>
            <span class='ln'></span>
            <p class='comment-comment'>{$row['comment_content']}</p>
            <button>reply</button>
        </div>";
    }
}

function add_comment_like($id, $user) {
    $favoritesObj = new Favorites();
    $favoritesObj->set_comment($id);
    $favoritesObj->set_user($user);
    $favoritesObj->add_thread_comment_favorite();
}

function main_comment($threadId) {
    global $connection;
    $threadObj = new Threads($connection);
    $userObj = new Users($connection);
    $threadObj->set_id($threadId);
    $thread = $threadObj->get_thread_by_id();
    $userObj->set_id($thread['user_id']);
    $user = $userObj->get_user_by_id();
    $thread_time = time_elapsed_string($thread['thread_time']);
    $full_name = ucwords($user['user_name_first'])." ".ucwords($user['user_name_last']);
    $user_url = 'profile.php?user='.$user['user_username'];
    echo "<div class='row'>
        <div class='col-sm-12'>
            <div class='comment-container'>
                <div class='row'>
                    <div class='posted col-sm-12'>
                        <span>{$thread_time}</span>
                    </div>
                    <div class='user-info col-sm-12'>
                        <div class='img'></div>
                        <a href='{$user_url}'>{$full_name}</a>
                    </div>
                    <div class='m-comment col-sm-12'>
                        <span>{$thread['thread_content']}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>";
}

function thread_comments($threadId) {
    global $connection;
    $threadObj = new Threads($connection);
    $userObj = new Users($connection);
    $threadObj->set_id($threadId);
    $comments = $threadObj->get_all_thread_comments_sorted("ASC");
    
    while($row = mysqli_fetch_assoc($comments)) {
        $userObj->set_id($row['user_id']);
        $user = $userObj->get_user_by_id();
        $timeSincePost = time_elapsed_string($row['comment_time']);
        $full_name = ucwords($user['user_name_first'])." ".ucwords($user['user_name_last']);
        $user_url = 'profile.php?user='.$user['user_username'];
        echo "<div class='row'>
                <div class='col-sm-12'>
                    <div class='comment-container'>
                        <div class='row'>
                            <div class='posted col-sm-12'>
                                <span>{$timeSincePost}</span>
                            </div>
                            <div class='user-info col-sm-12'>
                                <div class='img'></div>
                                <a href='{$user_url}'>{$full_name}</a>
                            </div>
                            <div class='comment col-sm-12'>
                                <span>{$row['comment_content']}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
    }
}