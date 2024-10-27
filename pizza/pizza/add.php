<?php
include('config/db_connect.php');
$title = $email = $ingredients ='';
$errors = array('email'=>'', 'title'=>'', 'ingredient'=>'');

  if(isset($_POST['submit'])){
      //Check email
      if(empty($_POST['email'])){
        $errors['email'] ='an email is required <br />';
      } else{
        $email = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          $errors['email'] = 'Email must be a valid email address';
        }
      }
      //Check title
      if(empty($_POST['title'])){
        $errors['title'] ='a title is required <br />';
      } else{
        $title = $_POST['title'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $title)){
          $errors['title'] = 'Title must be letters and spaces only';
        }
      }
      //Check ingredients
      if(empty($_POST['ingredients'])){
        $errors['ingredient'] = 'at least one ingredent is required <br />';
      } else{
        $ingredients = $_POST['ingredients'];
        if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)){
          $errors['ingredient'] = 'ingredients must be a comma separated list';
        }
      }

      if(array_filter($errors)){
        //echo 'errors in the form';
      }else{

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

        //create SQL
        $sql = "INSERT INTO pizzas(title,ingredients,email) VALUES('$title', '$ingredients', '$email')";
        //save to db and check
        if(mysqli_query($conn, $sql)){
          //sucess
          header('Location: index.php');
        }else{
          //errors
          echo 'query error =' .mysqli_error($conn);
        }

      }

  }// End of post check
?>

<!DOCTYPE html>
<html>

  <?php include('templates/header.php'); ?>

  <section class="container grey-text">
    <h4 class="center">Add a Pizza</h4>
    <form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
      <label >Your Email:</label>
      <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
      <div class="red-text"><?php echo $errors['email']; ?></div>
      <label >Pizza Title:</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars($title) ?>">
      <div class="red-text"><?php echo $errors['title']; ?></div>
      <label >ingredients(comma separated):</label>
      <input type="text" name="ingredients" value="<?php echo htmlspecialchars($ingredients) ?>">
      <div class="red-text"><?php echo $errors['ingredient']; ?></div>
      <div class="center">
        <input type="submit" name="submit" value="Add Pizza" class="btn brand z-depth-0">
      </div>
    </form>
  </section>

  <?php include('templates/footer.php');?>

</html>
