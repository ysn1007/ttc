<?php 

$errMsg = '';
if(isset($_POST['submit'])) {
    
    // email data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $betreff = htmlspecialchars($_POST['betreff']);
    $message = htmlspecialchars($_POST['message']);
    $checkBox = isset($_POST['checkBox']);
    
    if(!empty($name) && !empty($betreff) && !empty($email) && !empty($message) && $checkBox === true ){
    
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $errMsg = 'Bitte geben Sie eine valide eMail ein.';
            $validMailErr = 'validMail-error';
        } 
        
        //reCaptcha data
        /*if(isset($_POST['g-recaptcha-response'])){
            $responseKey = $_POST['g-recaptcha-response'];
        }*/

        /*$secretKey = "6LdIP-IZAAAAAPEM9cJ4T6BmZ2hOhv8O-w5LvGUQ";
        $userIp = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIp";
        $response = file_get_contents($url);*/

        if(!empty($responseKey)) {
            // passed
            $toMail = 'yasin.benammar@yahoo.de';
            $header = array ( 
                "FROM" => $email,
                "Replay-To" => $toMail,
                'X-Mailer' => 'PHP/' . phpversion()
            );
            
            if( mail($toMail, $betreff, $message, $header)){
                // Mail sent
                $name = '';
                $email = '';
                $betreff = '';
                $message= '';

                $mailSendMsg = 'Ihre Nachricht wurde erfolgreich gesendet. <br /> Vielen Dank für Ihre Anfrage, wir melden uns so bald wie möglich bei Ihnen.';
                $mailSendClass = 'mailSend';

            } else {
                // failed
                $mailSendMsg = 'Ihre Nachricht war leider nicht erfolgreich, bitte versuchen Sie es etwas später erneut.';
                $mailSendClass = 'mailFailed';
            }
        } /*else {
            $reCaptMsg = 'Bitte "Ich bin kein Roboter" Feld ausfüllen.';
            $recaptErr = 'reCaptcha';
        }*/   

    } else {
        
        if( empty($name) ){
            $errMsg = 'Die mit Stern versehene Fleder sind Pflicht.';
            $nameErr = 'name-error';
        } 
        if( empty($email) ){
            $errMsg = 'Die mit Stern versehene Fleder sind Pflicht.';
            $mailErr = 'mail-error';

        } 
        if( empty($betreff) ){
            $errMsg = 'Die mit Stern versehene Fleder sind Pflicht.';
            $betreffErr  = 'betreff-error';

        } 
        if( empty($message) ) {
            $errMsg = 'Die mit Stern versehene Fleder sind Pflicht.';
            $msgErr = 'msg-error';

        }
        if( $checkBox  === false ) {
            $errCheckBox = 'Stimmen Sie bitte unseren Datenschutzrichtlinen zu.';
            $checkErr = 'err-chkbox';
        } 
    }
    
}
?>

<?php include('./includes/header.php');?>
<div class="site-wrap col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="content-wrap col-xs-12 col-md-12">

        <div class="col-xs-12 col-md-6 col-md-offset-3 mailAlert <?php echo $mailSendClass ?>"><?php echo $mailSendMsg ?></div>
        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 hc-mail">
            <h2>Kontaktformular</h2>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 hc-form-content">
            <div class="form-user" id="form-user">
                <form id="kontaktFormular" class="kontakt-formular" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <div class="form-box" id="form-box-name">
                        <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_name" type="text" placeholder="Name *" name="name" value="<?php echo isset($name) ? $name : ''  ?>">
                        <div class="error err-name <?php echo $nameErr; ?>"><?php echo $errMsg ?></div>
                    </div>
                    <div class="form-box" id="form-box-eMail">
                        <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_absender" type="text" placeholder="eMail *" name="email" value="<?php echo isset($email) ? $email : ''  ?>">
                        <div class="error err-mail <?php echo $mailErr; ?>"><?php echo $errMsg ?></div>
                        <div class="error err-mail <?php echo $validMailErr; ?>"><?php echo $errMsg ?></div>
                    </div>
                    <div class="form-box" id="form-box-betreff">
                        <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_betreff" type="text" placeholder="Betreff *" name="betreff" value="<?php echo isset($betreff) ? $betreff : ''  ?>">
                        <div class="error err-betreff <?php echo $betreffErr; ?>"><?php echo $errMsg ?></div>
                    </div>
                    <div class="form-box" id="form-box-nachricht">
                        <textarea class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_nachricht" rows="6" cols="40" placeholder="Geben Sie Ihre Nachricht ein *" name="message"><?php echo isset($message) ? $message : ''  ?></textarea>
                        <div class="error err-nachricht <?php echo $msgErr; ?>"><?php echo $errMsg ?></div>
                    </div>
                    <br/>
                    <div class="form-box" id="form-box-checkbox">
                        <input type="checkbox" id="check-box" name="checkBox"> Stimmen Sie unsere <a href="impressum.html#kontakt">Datenschutzerklärung zum Kontaktformular</a> zu.
                        <div class="error <?php echo $checkErr; ?>"><?php echo $errCheckBox ?></div>
                    </div>
                    <br/>
                    <!-- reCAPTCHA -->
                    <!--div class="g-recaptcha" id="recaptcha" data-sitekey="6LdIP-IZAAAAAGhs1N9UTZ7I0SaOI1qjpsMWh2GZ" data-callback="recaptcha_verify"></div>
                    <div class="error err-chkbox <?php echo $recaptErr; ?>"><?php echo $reCaptMsg ?></div-->
                    <br/>

                    <button type="submit" id="submit" class="btn hc-btn-small" value="submit" name="submit" >Nachricht Senden</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include('./includes/footer.php');?>

<script>
    $(function(){
        $('.mailSend').delay(5000).fadeOut();
        $('.mailFailed').delay(5000).fadeOut();
    });
</script>
