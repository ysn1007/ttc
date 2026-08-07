        </div>

        <footer class="footer">
            <div class="footer-section">
                <div class="row">
                    <div class="col col-sm-12 col-md-6 col-lg-3 footer-section-item mb-3 club">
                        <h4>TTC RAMSHARDE</h4>
                        <p>Vorstand: Andreas Müller</p>
                        <!--p>Telefon: 0152/53917291</p>
                        <p>E-Mail: amueller123@web.de</p-->
                        <a href="kontakt.php">Andreas kontaktieren</a>
                    </div>

                    <div class="col col-sm-12 col-md-6 col-lg-3 footer-section-item mb-3 training">
                        <h4>Hallenzeiten</h4>
                        <p>Mo: Nach Absprache</p>
                        <p>Di: 17:00 - 18:00 Schüler / Jugend </p>
                        <p>Di: 18:00 - 22:00 Herren</p>
                        <p>Mi: Nach Absprache</p>
                        <p>Fr: 18:00 - 22:00 Herren</p>
                    </div>
                    
                    <div class="col col-sm-12 col-md-6 col-lg-3 footer-section-item mb-3 halle">
                        <h4>Anfahrt</h4>
                        <p>Petrihalle Halle</p>
                        <p>Apenrader Str. 164</p>
                        <p>24939 Flensburg</p>
                    </div>
                    
                    <div class="col col-sm-12 col-md-6 col-lg-3 footer-section-item mb-3 social">
                        <h4>Social Media</h4>
                        <p>Facebook</p>
                        <p><a href="https://www.instagram.com/ttc_ramsharde/?igsh=cWg3dmxzM3U5bTNt" target="blank">Instagramm</a></p>
                    </div>
                </div>
            </div>

            <div class="footer-links">
                <div class="row">	
                    <div class="col col-sm-12 col-md-9 link-wrap">
                        <?= (($cfg['header']['kontakt'] == 1) ? '<div class="link-item"><a href="kontakt.php" class="link">Kontakt</a></div>' : ''); ?>
                        <div class="link-item"><a href="impressum.php" class="link">Impressum</a></div>
                    </div>
                    <div class="col col-sm-12 col-md-3 copyright">
                        <div class="link-item">
                            <span>TTC RAMSHARDE © 2026</span>
                        </div>          
                    </div>
                </div>
                
            </div>
        </footer>
    </body>

    <!--   third party sources    -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!--owl slider js-->
    <script src="./owlcarousel/owl.carousel.min.js"></script>
    <!--   js sources    -->
    <script src="./js/main.min.js"></script>

 </html>