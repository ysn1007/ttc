                
            </div>
        </div>
    </div>
</body>
<footer class="footer">
    <div><span>TTC RAMSHARDE © 2021</span></div>
</footer>

<!--BS5 script-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
 <!--   js sources    -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../js/main.min.js?v=<?php echo time(); ?>"></script>
<?php 
    
    if (in_array($currentPage, ['article-edit', 'article-add'])) {
        echo '<script src="../js/article-sm-handler.min.js"></script>';
    }

    if($currentPage == 'article-edit') {
        $articleId = $articleId ?? '';
        $currentPage = $currentPage.'.php?id='.$articleId;
    }

    
?>

</html>