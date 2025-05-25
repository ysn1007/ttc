<?php 
require_once 'admin/dbh.inc.php';


include('./includes/header.php');
$content = '';

$content .='
<div class="site-wrap" id="galerie">
    <div class="content-wrap">
        <section class="container galery-group" id="galery">
            <div class="row">
                <div class="galery-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Bildergallarie</h2>
                </div>
                
                <div class="gal-content">
                    <div class="row row-cols-sm-1 row-cols-md-3 g-2">
                        <div class="galery-item item20 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=2020-2029">
                                <div class="galery-time">
                                    <p>2020 bis Jetzt</p>
                                </div>
                            </a>    
                        </div>

                        <div class="galery-item item10 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=2010-2019">
                                <div class="galery-time">
                                    <p>2010 bis 2019</p>
                                </div>
                            </a>    
                        </div>
                        
                        <div class="galery-item item00 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=2000-2009">
                                <div class="galery-time">
                                    <p>2000 bis 2009</p>
                                </div> 
                            </a>   
                        </div>
                        
                        <div class="galery-item item90 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=1990-1999">
                                <div class="galery-time">
                                    <p>1990 bis 1999</p>
                                </div>
                            </a>    
                        </div>
                        
                        <div class="galery-item item80 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=1980-1989">
                                <div class="galery-time">
                                    <p>1980 bis 1989</p>   
                                </div>
                            </a>    
                        </div>
                        
                        <div class="galery-item item70 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=1970-1979">
                                <div class="galery-time">
                                    <p>1970 bis 1979</p> 
                                </div>
                            </a>    
                        </div>
                        
                        <div class="galery-item item60 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=1960-1969">
                                <div class="galery-time">
                                    <p>1960 bis 1969</p>
                                </div> 
                            </a>   
                        </div>
                        
                        <div class="galery-item item50 col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="bildergalerie.php?dekade=1950-1959">
                                <div class="galery-time">
                                    <p>1955 bis 1959</p>   
                                </div> 
                            </a>   
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>';

echo $content;

include('./includes/footer.php');