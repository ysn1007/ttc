<?php 
require_once 'admin/includes/dbh.inc.php';
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
            $mailSendClass = 'success';
            $mail = '<div class="col-12 align-self-center '.$mailSendClass.'">'. $mailSendMsg .'</div>';

        } else {
            // failed
            $mailSendMsg = 'Ihre Nachricht war leider nicht erfolgreich, bitte versuchen Sie es etwas später erneut.';
            $mailSendClass = 'failed';
            $mail = '<div class="col-12 align-self-center '.$mailSendClass.'">'. $mailSendMsg .'</div>';
        }
        

    } else {
        
        if( empty($name) ){
            $ErrName = '<div class="error name-error">Geben Sie Ihren Namen ein.</div>';
        } 
        if( empty($email) ){
            $ErrMail = '<div class="error mail-error">Geben Sie eine richtige E-Mail adresse ein.</div>';
        } 
        if( empty($betreff) ){
            $ErrBetreff = '<div class="error betreff-error">Geben Sie einen Betreff an.</div>';
        } 
        if( empty($message) ) {
            $ErrMsg = '<div class="error msg-error">Geben Sie eine Nachricht ein.</div>';
        }
        if( $checkBox  === false ) {
            $ErrCheckBox = '<div class="error err-chkbox">Stimmen Sie bitte unseren Datenschutzrichtlinen zu.</div>';
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
                    <h1>Kontaktformular</h1>
                </div>

                <?php echo $mail ?>
                <div class="col-8 g-2 align-self-center hc-form-content">
                    <div class="form-user" id="form-user">
                        <form id="kontaktFormular" class="kontakt-formular" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="form-box" id="form-box-name">
                                <label for="name">Name *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_name" type="text" placeholder="Max Mustermann" name="name" value="<?php echo isset($name) ? $name : ''  ?>">
                                <?php echo $ErrName ?>
                            </div>
                            <div class="form-box" id="form-box-eMail">
                                <label for="name">E-Mail *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_absender" type="text" placeholder="m.mustermann@mail.de" name="email" value="<?php echo isset($email) ? $email : ''  ?>">
                                <?php echo $ErrMail ?>
                            </div>
                            <div class="form-box" id="form-box-betreff">
                                <label for="name">Betreff *</label>
                                <input class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_betreff" type="text" placeholder="Training" name="betreff" value="<?php echo isset($betreff) ? $betreff : ''  ?>">
                                <?php echo $ErrBetreff ?>
                            </div>
                            <div class="form-box" id="form-box-nachricht">
                                <label for="name">Nachricht *</label>
                                <textarea class="form-control form-control-sm form-control-lg form-control-ms" id="kontakt_nachricht" rows="6" cols="40" placeholder="Dein Nachricht" name="message"><?php echo isset($message) ? $message : ''  ?></textarea>
                                <?php echo $ErrMsg ?>
                            </div>
                            <br/>
                            <div class="form-box" id="form-box-checkbox">
                                <input type="checkbox" id="check-box" name="checkBox"> Stimmen Sie unsere <a href="impressum.php#datenschutz">Datenschutzerklärung zum Kontaktformular</a> zu.
                                <?php echo $ErrCheckBox ?>
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

