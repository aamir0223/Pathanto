
<!-- <div class="commentlist"> 



<div class="comment">
<p class="arrow"> Leave a Comment </p>



<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $comment_text="";
$name_err = $comment_text_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_text = trim($_POST["text"]);
    if(empty($input_text)){
        $comment_text_err = "Please enter an address.";     
    } else{
        $comment_text = $input_text;
    }
    
    // Validate salary
   $dt =date("Y-m-d") ;
   
    $currentPageUrl =  $_SERVER["REQUEST_URI"];
        $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  
   
    // Check input errors before inserting in database
    if(empty($name_err) && empty($comment_text_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO commentBox (pageUrl,name,text,date) VALUES (?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss",$param_pageUrl, $param_name, $param_Text, $param_date);
            
            // Set parameters
            $param_pageUrl= $curPageName;
            $param_name = $name;
            $param_Text = $comment_text;
            $param_date = $dt;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location:  $currentPageUrl ");
                $sql = "SELECT * FROM commentBox";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                           
                                while($row = mysqli_fetch_array($result)){
                                  if($row['pageUrl']== $curPageName){
                                   
                                        echo "<p class='comment-name'> {$row['name']}</p>
                                            <p class='comment-text'>{$row['text']}</p> ";
                                            
                                  }
                                        
                                }

                           
                            // Free result set
                            mysqli_free_result($result);
                        } 
                    } 
                        } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
}

?>
 <?php
 require_once "config.php";
  $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
 $sql = "SELECT * FROM commentBox";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                           
                                while($row = mysqli_fetch_array($result)){
                                  if($row['pageUrl']== $curPageName){
                                   
                                        echo "<p class='comment-name'> {$row['name']}</p>
                                            <p class='comment-text'>{$row['text']}</p>  ";
                                            
                                  }
                                        
                                }

                           
                            // Free result set
                            mysqli_free_result($result);
                        } 
                    } 

                    // Close connection
                    mysqli_close($conn);
                    ?>
                    


<form  method="post">

                         <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                         <div class="form-group">
                            <label>Comment</label>
                            <textarea name="text" class="form-control <?php echo (!empty($comment_text_err)) ? 'is-invalid' : ''; ?>"></textarea>
                            <span class="invalid-feedback"><?php echo $comment_text_err;?></span>
                        </div>

         <button type="Submit" class="btn btn-primary" name="page" value="Submit"  >Submit</button>
</form>
 
</div>

</div> -->