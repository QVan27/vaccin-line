<?php
session_start();
include('inc/pdo.php');
include('inc/function.php');
$title = 'Contact';
$succes = false;

include('inc/header-front.php');
?>
<?php
$errors = array();

if (!empty($_POST['submitted'])) {
  $succes = false;
  $email = cleanXss($_POST['email']);
  $object = cleanXSS($_POST['object']);
  $message = cleanXss($_POST['message']);
  // VERIF EMAIL
  if (!empty($email)) {

  } else {
    $errors['email'] = 'Veuillez renseignez ce champ';
  }
  // VERIF Object
  if (!empty($object)) {
    if(mb_strlen($object) < 5) {
      $errors['object'] = '5 caracteres minimum';
    } elseif (mb_strlen($object) > 100) {
      $errors['object'] = '100 caractere maximum';
    }
  } else {
    $errors['message'] = 'Veuillez renseignez ce champ';
  }
  // VERIF MESSAGE
  if (!empty($message)) {
    if(mb_strlen($message) < 10) {
      $errors['message'] = '10 caracteres minimum';
    } elseif (mb_strlen($message) > 1000) {
      $errors['message'] = '1000 caractere maximum';
    }
  } else {
    $errors['message'] = 'Veuillez renseignez ce champ';
  }

    if(count($errors) == 0) {

    $sql = "INSERT INTO vl_contacts (email,message,created_at,lu,object,status)
    VALUES (:email,:message,NOW(),'non',:object,1)";
    $query = $pdo->prepare($sql);
    $query->bindValue(':email',$email,PDO::PARAM_STR);
    $query->bindValue(':message',$message,PDO::PARAM_STR);
    $query->bindValue(':object',$object,PDO::PARAM_STR);
    $query->execute();

        header('Location: contact.php?success=yes');
    } else {
      header('Location: contact.php?error=yes');
    }
}
?>
  <section id="contact">
    <?php if(!empty($_GET['error']) && $_GET['error'] == 'yes'){ ?>
      <p class="error-message">Erreur, veuillez réessayer</p>
    <?php  }elseif (!empty($_GET['success']) && $_GET['success'] == 'yes') {?>
      <p class="success-message">Mail envoyé avec succès</p>
    <?php  } ?>
    <div class="wrap-section-contact-1">
      <div class="form-contact">
        <h2>Contact</h2>
        <form action="contact.php" method="post">
          <div class="w50">
            <input type="email" required="" name="email"<?php if (!empty($_SESSION)) { echo 'value="'. $_SESSION['user']['email'] .'"';}?> <?php if(!empty($_SESSION)) { echo 'readonly' ;} ?> >
            <span class="error"><?php if(!empty($errors['email'])) { echo $errors['email']; }?></span>
            <label>Email</label>
          </div>
          <div class="w50">
            <input type="text" name="object" required="">
            <span class="error"><?php if(!empty($errors['object'])) { echo $errors['object']; }?></span>
            <label>Objet</label>
          </div>
          <div class="w50">
            <textarea name="message" id="message" required="" cols="65" rows="5" ></textarea>
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

<?php include('inc/footer-front.php');
