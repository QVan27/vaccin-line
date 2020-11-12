<?php
session_start();
include('inc/pdo.php');
include('inc/function.php');
$title = 'Home';
// debug($_SESSION);
$succes = false;

include('inc/header-front.php');
$errors = array();

if (!empty($_POST['submitted'])) {
    $succes = false;
    $email = cleanXss($_POST['email']);
    $object = cleanXSS($_POST['object']);
    $message = cleanXss($_POST['message']);
    // VERIF EMAIL
    if (!empty($email)) {

    }else {
        $errors['email'] = 'Veuillez renseignez ce champ';
    }
    // VERIF Object
    if (!empty($object)) {
        if(mb_strlen($object) < 10) {
            $errors['object'] = '10 caracteres minimum';
        } elseif (mb_strlen($object) > 100) {
            $errors['object'] = '100 caractere maximum';
        }
    }else {
        $errors['message'] = 'Veuillez renseignez ce champ';
    }
    // VERIF MESSAGE
    if (!empty($message)) {
        if(mb_strlen($message) < 10) {
            $errors['message'] = '10 caracteres minimum';
        } elseif (mb_strlen($message) > 1000) {
            $errors['message'] = '1000 caractere maximum';
        }
    }else {
        $errors['message'] = 'Veuillez renseignez ce champ';
    }

    if(count($errors) == 0) {
        $succes = true;

        $sql = "INSERT INTO vl_contacts (email,message,created_at,lu,object,status)
        VALUES (:email,:message,NOW(),'non',:object,1)";
        $query = $pdo->prepare($sql);
        $query->bindValue(':email',$email,PDO::PARAM_STR);
        $query->bindValue(':message',$message,PDO::PARAM_STR);
        $query->bindValue(':object',$object,PDO::PARAM_STR);
        $query->execute();

        // header('Location: contact.php');

    }
}

?>
<!-- <form action="contact.php" method="post">
  <div class="w50">
    <input type="email" name="email" require=""value="" placeholder="Votre email">
  </div>
  <div class="w50">
    <input type="text" name="objet" require=""value="" placeholder="Objet">
  </div>
  <div class="w50">
    <textarea name="message" id="" cols="50" rows="8" placeholder="Votre message..."></textarea>
  </div>
  <div class="w50">
    <input type="submit" name="submitted" value="Envoyer">
  </div>
</form> -->

<!-- Non connecté -->
<?php if(empty($_SESSION) ) : ?>
    <p>Vous devez être connecté pour acceder a cette page<p>
<?php endif; ?>

<!-- Connecté -->
<?php if(!empty($_SESSION)) : ?>
    <?php if($succes == false) : ?>
<section>
  <div class="wrap-section-contact-1">
    <div class="wrap-section-contact-2">
      <h2>Contact</h2>
      <form action="contact.php" method="post">
        <div class="w50">
          <input type="email" name="email"<?php if (!empty($_SESSION)) { echo 'value="'. $_SESSION['user']['email'] .'"';}?> readonly>
          <span class="error"><?php if(!empty($errors['email'])) { echo $errors['email']; }?></span>
          <label>Email</label>
        </div>
        <div class="w50">
          <input type="text" name="object" required="">
          <span class="error"><?php if(!empty($errors['object'])) { echo $errors['object']; }?></span>
          <label>Objet</label>
        </div>
        <div class="w50">
          <textarea name="message" id="message" cols="55" rows="10" ></textarea>
          <span class="error"><?php if(!empty($errors['message'])) { echo $errors['message']; }?></span>
          <label>Message</label>
        </div>
        <div class="w50">
          <input type="submit" name="submitted" value="Envoyer">
        </div>
      </form>
    </div>
  </div>
</section>
    <?php endif; ?>
    <?php if($succes == true) : ?>
        <p>Merci, Votre message a bien etait pris en compte</p>
    <?php endif; ?>
<?php endif; ?>
<?php include('inc/footer-front.php');
