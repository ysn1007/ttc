
<?php 
include('./includes/header.php');

$content = '';

$content .= '
<div class="site-wrap col-xs-12 col-sm-12 col-md-12">
    <div class="content-wrap col-xs-12 col-md-12">
        <section class="article-wrap col-xs-12 col-md-12 col-lg-12" id="impress">
            <div class="row">
                <div class="section-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Impressum</h2>
                </div>

                <div class="content">
                    <section class="section-item">
                        <h4 class="impress-header">Angaben gemäß § 5 TMG:</h4>
                        <p>TTC Ramsharde  </p>
                        <p>Am Moorbach 3</p>
                        <p>24939 Flensburg</p>
                        <p>E-Mail: amueller123@web.de </p> 
                        <p>Telefon: 0152/53917291</p>
                        <p>Internet: ttc-ramsharde.de</p>
                    </section>

                    <section class="section-item">
                        <h4 class="impress-header">Vertreten durch:</h4>
                        <p>Andreas Müller</p> 
                    </section>

                    <section class="section-item">
                        <h4 class="impress-header">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</h4> 
                        <p>Yasin Ben Ammar</p>  
                        <p>Steinkamp 35</p>  
                        <p>24955 Harrislee</p>  
                        <p>yasin.benammar@yahoo.de</p>
                    </section>

                    <section class="section-item">  
                        <h4 class="impress-header">Haftungsausschluss:</h4> 
                        <p>Die Inhalte dieser Website wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte kann jedoch keine Gewähr übernommen werden. Als Diensteanbieter sind wir gemäß § 7 Abs. 1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p>
                    </section>

                    <section class="section-item">
                        <h4 class="impress-header">Urheberrecht:</h4>
                        <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.</p>
                    </section>

                    <section class="section-item" id="datenschutz">
                        <h4 class="impress-header">Datenschutz:</h4>
                        <p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (z.B. Name, Anschrift oder E-Mail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.</p> 
                    </section>

                    <section class="section-item">
                        <h4 class="impress-header">Hinweis:</h4> 
                        <p>Die Inhalte dieser Website sind ausschließlich für die Nutzung durch Mitglieder und Interessierte des [Name des Tischtennisvereins] bestimmt. Eine Verwendung durch Dritte, die nicht berechtigt sind, ist untersagt.</p>
                    </section>

                    <section class="section-item">
                        <h4 class="impress-header">Schlussbestimmungen:</h4> 
                        <p>Sollten einzelne Bestimmungen dieses Impressums unwirksam sein oder werden, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt.</p>
                    </section>

                </div>
            </div>
        </section>
    </div>
</div>';


echo $content;

include('./includes/footer.php');