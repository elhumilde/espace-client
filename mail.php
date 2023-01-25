<?php
if(isset($_POST['email'])) {

    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "amalskalli250@gmail.com";
    $telephone = $_POST['objet'];

    function died($error) {
        // your error code can go here
        echo "Nous sommes vraiment désolés, mais des erreurs ont été détectées avec le formulaire que vous avez envoyé. ";
        /* echo "These errors appear below.<br /><br />";
         echo $error."<br /><br />";
         echo "Please go back and fix these errors.<br /><br />";*/
        die();
    }


    // validation expected data exists
    if(!isset($_POST['nom']) ||
        !isset($_POST['prenom']) ||
        !isset($_POST['email']) ||
        !isset($_POST['objet']) ||
        !isset($_POST['message'])) {
        died('Nous sommes désolés, mais il semble y avoir un problème avec le formulaire que vous avez envoyé.');
    }



    $first_name = $_POST['nom']; // required
    $last_name = $_POST['prenom']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['objet']; // not required
    $comments = $_POST['message']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if(!preg_match($email_exp,$email_from)) {
        $error_message .= 'L adresse e-mail que vous avez saisie ne semble pas être valide.<br />';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if(!preg_match($string_exp,$first_name)) {
        $error_message .= 'The First Name you entered does not appear to be valid.<br />';
    }

    if(!preg_match($string_exp,$last_name)) {
        $error_message .= 'The Last Name you entered does not appear to be valid.<br />';
    }

    if(strlen($comments) < 2) {
        $error_message .= 'The Comments you entered do not appear to be valid.<br />';
    }

    if(strlen($error_message) > 0) {
        died($error_message);
    }

    $email_message = "Form details below.\n\n";
    function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }
    $email_message .= "nom: ".clean_string($first_name)."\n";
    $email_message .= "prenom: ".clean_string($last_name)."\n";
    $email_message .= "email: ".clean_string($email_from)."\n";
    $email_message .= "objet: ".clean_string($telephone)."\n";
    $email_message .= "message: ".clean_string($comments)."\n";

// create email headers
    $headers = 'From: '.$email_from."\r\n".
        'Reply-To: '.$email_from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $telephone, $email_message, $headers);
    ?>








    <?php



echo 'mail envoyé';
}
?>

<div class="col-lg-6 col-md-6 col-sm-6" >
    <form  method="POST" id="contactform" style="text-align: left;">
        <div class="form-group">
            <label for="raison" style="color: black;">Nom<span class="asterik">*</span></label>
            <input type="text" class="form-control" id="nom" name="nom" value="test" required>
        </div>

        <div class="form-group">
            <label for="personne" style="color: black;">Prénom <span class="asterik">*</span></label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="test" required>
        </div>
        <div class="form-group">
            <label for="email" style="color: black;">Email<span class="asterik">*</span></label>
            <input type="email" class="form-control" id="email" name="email"  required>

        </div>
        <div class="form-group">
            <label for="tel" style="color: black;">Objet<span class="asterik">*</span></label>
            <input type="text" class="form-control" id="objet" name="objet" value="test" required>
        </div>
        <div class="form-group">
            <label for="message" style="color: black;">Votre message <span class="asterik">*</span></label><br/>
            <textarea name="message" class="form-control" id="message" rows="10" cols="53" required></textarea>

        </div>

        <input class="btn btn-default" type="submit" name="envoyer" id="envoyer" value="Envoyer" style="text-align: center;background-color: #c89e3c!important;">



    </form>

</div>