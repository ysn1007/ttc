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

            $mailSendMsg = 'Ihre Nachricht wurde erfolgreich gesendet. <br /> Vielen Dank für Ihre Nachricht, wir melden uns so bald wie möglich bei Ihnen.';
            $mailSendClass = 'mailSend';

        } else {
            // failed
            $mailSendMsg = 'Ihre Nachricht war leider nicht erfolgreich, bitte versuchen Sie es etwas später erneut.';
            $mailSendClass = 'mailFailed';
        }
        

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
<div class="site-wrap">
    <div class="content-wrap">

        <div class="form-wrap container">
            <div class="row">
                <div class="galery-header">
                    <img src="img/tt-icon.svg" alt="">
                    <h2>Kontaktformular</h2>
                </div>

                <div class="col-12 align-self-center mailAlert <?php echo $mailSendClass ?>"><?php echo $mailSendMsg ?></div>
                <div class="col-8 g-2 align-self-center hc-form-content">
                    <div class="form-user" id="form-user">
                        <form id="kontaktFormular" class="kontakt-formular" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="form-box" id="form-box-name">
                                <label for="name">Name *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_name" type="text" placeholder="Max Mustermann" name="name" value="<?php echo isset($name) ? $name : ''  ?>">
                                <div class="error err-name <?php echo $nameErr; ?>"><?php echo $errMsg ?></div>
                            </div>
                            <div class="form-box" id="form-box-eMail">
                                <label for="name">E-Mail *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_absender" type="text" placeholder="m.mustermann@mail.de" name="email" value="<?php echo isset($email) ? $email : ''  ?>">
                                <div class="error err-mail <?php echo $mailErr; ?>"><?php echo $errMsg ?></div>
                                <!--div class="error err-mail <?php echo $validMailErr; ?>"><?php echo $errMsg ?></div-->
                            </div>
                            <div class="form-box" id="form-box-betreff">
                                <label for="name">Betreff *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_betreff" type="text" placeholder="Training" name="betreff" value="<?php echo isset($betreff) ? $betreff : ''  ?>">
                                <div class="error err-betreff <?php echo $betreffErr; ?>"><?php echo $errMsg ?></div>
                            </div>
                            <div class="form-box" id="form-box-nachricht">
                                <label for="name">Nachricht *</label>
                                <textarea class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_nachricht" rows="6" cols="40" placeholder="Hallo Andreas, ..." name="message"><?php echo isset($message) ? $message : ''  ?></textarea>
                                <div class="error err-nachricht <?php echo $msgErr; ?>"><?php echo $errMsg ?></div>
                            </div>
                            <br/>
                            <div class="form-box" id="form-box-checkbox">

                                <input type="checkbox" id="check-box" name="checkBox"> Stimmen Sie unsere <a href="impressum.php#datenschutz">Datenschutzerklärung zum Kontaktformular</a> zu.
                                <div class="error <?php echo $checkErr; ?>"><?php echo $errCheckBox ?></div>
                            </div>
                            <br/>
                            
                            <br/>

                            <button type="submit" id="submit" class="btn btn-default hc-btn-small" value="submit" name="submit" >Nachricht Senden</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<?php include('./includes/footer.php');?>

