<?php 
require_once 'admin/dbh.inc.php';
$id =  $_GET['id'];

$content = '';

include('./includes/header.php');
$result = getArticle($con, 1);
while($article = mysqli_fetch_assoc($result)) {
    if($article["id"] == $id) {
        $content .= '
        <div class="post-content col-xs-12 col-md-10 col-md-offset-1">
            <div class="head-content">
                <div class="article-img">
                    <div class="article-bg-img" style="width: 100%; height: 500px; Background-image: url(./img/article/'. $article["imgPath"] .'); background-repeat: no-repeat; background-size: cover; background-position: top;"></div>
                </div>
                <div class="headline"><h2>'. $article["headline"] .'</h2></div>
                <div class="post-tag">
                    <div class="tag-item">'. $article["tags"] .'</div>
                </div>
            </div>

            <div class="content">
                <p>'.$article["copytext"] .' </p>
            </div>
        </div>';
        echo $content;
    }
    
} 
include('./includes/footer.php');