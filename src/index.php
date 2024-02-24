<?php
//require_once 'admin/dbh.inc.php';
$content = '';

include('./includes/header.php');
$content .= '
<div class="hero col-xs-12 col-md-12 col-lg-12 ">
    <div class="rw">
        <div class="col-sm-12 col-lg-12">
            <div class="row"> 
                <div class="owl-carousel">
                    <div> <img src="img/slide01.jpg" alt=""> </div>
                    <div> <img src="img/slide02.jpg" alt=""> </div>
                    <div> <img src="img/slide03.jpg" alt=""> </div>
                    <div> <img src="img/slide04.jpg" alt=""> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    
    <div class="content-wrap new-player-wrap col-xs-12 col-md-12">
        <div class="row">
            hallo
        </div>
    </div>

</div>';

echo $content;

include('./includes/footer.php');
