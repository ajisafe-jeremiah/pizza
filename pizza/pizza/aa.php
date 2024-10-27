<?php
require_once('config/db_connect.php');
 $title = $email = $ingredients ='';
 $title_err = $email_err = $ingredient_err = "";
 if(isset($_POST['update'])){

   // Define variables and initialize with empty values


   // Processing form data when form is submitted
   if(isset($_POST["id"]) && !empty($_POST["id"])){
       // Get hidden input value
       $id = $_POST["id"];

       // Validate email
       $input_email = trim($_POST["email"]);
       if(empty($input_email)){
           $email_err = "an email is required";
       } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
           $name_err = "Please enter a valid email.";
       } else{
           $email = $input_email;
       }

       // Validate title
       $input_title = trim($_POST["title"]);
       if(empty($input_title)){
           $title_err = "a title is required.";
       } elseif (!preg_match('/^[a-zA-Z\s]+$/', $input_title)) {
         $name_err = "Title must be letters and spaces only.";
       }else {
         $title = $input_title;
       }

       // Validate salary
       $input_ingredients = trim($_POST["ingredients"]);
       if(empty($input_ingredients)){
           $ingredient_err = "at least one ingredent is required.";
       } elseif(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/',$input_ingredients)){
           $ingredients_err = "Please ingredients must be a comma separated list.";
       } else{
           $ingredient = $input_ingredients;
       }

       // Check input errors before inserting in database
       if(empty($email_err) && empty($title_err) && empty($ingredient_err)){
           // Prepare an update statement
           $sql = "UPDATE pizzas SET title=?, ingredients=?, email=? WHERE id=?";

           if($stmt = mysqli_prepare($link, $sql)){
               // Bind variables to the prepared statement as parameters
               mysqli_stmt_bind_param($stmt, "sssi", $param_email, $param_title, $param_ingredients, $param_id);

               // Set parameters
               $param_title = $title;
               $param_ingredients = $ingredient;
               $param_email = $email;
               $param_id = $id;

               // Attempt to execute the prepared statement
               if(mysqli_stmt_execute($stmt)){
                   // Records updated successfully. Redirect to landing page
                   header("location: index.php");
                   exit();
               } else{
                   echo "Something went wrong. Please try again later.";
               }
           }

           // Close statement
           mysqli_stmt_close($stmt);
       }

       // Close connection
       mysqli_close($link);
   } else{
       // Check existence of id parameter before processing further
       if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
           // Get URL parameter
           $id =  trim($_GET["id"]);

           // Prepare a select statement
           $sql = "SELECT * FROM pizzas WHERE id = ?";
           if($stmt = mysqli_prepare($link, $sql)){
               // Bind variables to the prepared statement as parameters
               mysqli_stmt_bind_param($stmt, "i", $param_id);

               // Set parameters
               $param_id = $id;

               // Attempt to execute the prepared statement
               if(mysqli_stmt_execute($stmt)){
                   $result = mysqli_stmt_get_result($stmt);

                   if(mysqli_num_rows($result) == 1){
                       /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                       // Retrieve individual field value
                       $email = $row["email"];
                       $title = $row["title"];
                       $title = $row["ingredients"];
                   } else{
                       // URL doesn't contain valid id. Redirect to error page
                       //header("location: error.php");
                       exit();
                   }

               } else{
                   echo "Oops! Something went wrong. Please try again later.";
               }
           }

           // Close statement
           mysqli_stmt_close($stmt);

           // Close connection
           mysqli_close($link);
       }  else{
           // URL doesn't contain id parameter. Redirect to error page
           //header("location: error.php");
           exit();
       }
   }

 }

?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>
    <section class="container grey-text">
      <h4 class="center">Edit Pizza</h4>
      <form class="white" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
          <label >Your Email:</label>
          <input type="text" name="email" value="<?php echo $email; ?>">
          <span class="help-block"><?php echo $email_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
          <label >Pizza Title:</label>
          <input type="text" name="title" value="<?php echo $title?>">
          <span class="help-block"><?php echo $title_err;?></span>
        </div>
        <div class="form-group <?php echo (!empty($ingredient_err)) ? 'has-error' : ''; ?>">
          <label >ingredients(comma separated):</label>
          <input type="text" name="ingredients" value="<?php $ingredients ?>">
          <span class="help-block"><?php echo $ingredient_err;?></span>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <div class="center">
          <input type="submit" name="submit" value="update" class="btn brand z-depth-0">
          <a href="index.php" class="btn brand z-depth-0">back</a>
        </div>
      </form>
    </section>

<?php include('templates/footer.php');?>

</html>
