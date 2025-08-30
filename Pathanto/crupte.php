<?php
$servername = "localhost";
$database = "u285245875_CommentData";
$username = "u285245875_pathanto_soni";
$password = "Arman23@31";
$CommentTable= $_POST['page'];
$Name= $_POST['name'];
$commentArea= $_POST['contant'];
$CommentPageUrl= $_POST['delete'];  

// Create connection
 $NewCommentTable= str_replace("-","", $CommentTable); 
 $NewCommentTable2= str_replace(".","", $NewCommentTable); 


$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection

if (!$conn) {

    die("Connection failed: " . mysqli_connect_error());

}


$sql = "SELECT table_name FROM information_schema.tables WHERE table_type = 'base table' AND table_schema='u285245875_CommentData'";
$result = mysqli_query($conn ,$sql);


 while($row1 = mysqli_fetch_assoc($result)){

         foreach ( $row1 as $row) {
                   if($row==$NewCommentTable2)
              { 
        	     $sqlInsert = "INSERT INTO $NewCommentTable2 (name ,  commentText) VALUES ('$Name', '$commentArea')";  
                 if(mysqli_query($conn, $sqlInsert))
                  {  

                    header("Location: $CommentPageUrl");  
                    exit;
  
                  }
                 else
                {  
                    header("Location: $CommentPageUrl");  
                    exit;
                 } 

             }
            else
             {
        	$sql = "create table $NewCommentTable2 (name VARCHAR(20) NOT NULL, commentText VARCHAR(250) NOT NULL)";  

               if(mysqli_query($conn, $sql))
               {
               	$sqlInsert = "INSERT INTO $NewCommentTable2 VALUES ('$Name', '$commentArea')";  
                  if(mysqli_query($conn, $sqlInsert))
                   {  
                  header("Location: $CommentPageUrl");  
                    exit;
                  }
                 else
                  {  
             header("Location: $CommentPageUrl");  
                    exit;
                  } 

              }
        }
 
}
}

                                

?>  

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
        
<script src="/Pathanto/javascript.js" type='text/javascript'></script>
<link  href=" https://pathanto.com">

<link rel="shortcut icon" href="https://pathanto.com/favicon.ico">



    <!-- bootstarp link-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

<!-- costom css-->
  <link rel="stylesheet" href="https://pathanto.com/Pathanto/css/styles.css">

  <!-- jaquery link-------> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- jaquery link------->
<!-- font link -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Castoro&display=swap" rel="stylesheet">
 <!-- font link -->
 <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6HYCEV74PD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-6HYCEV74PD');
</script>
  </head>
  <body>

    <div class="row" id="logo-header">
            <div class="col-lg-6 col-md-6">
                  <div class="logo">       
                         <a class="navbar-brand title-heading" href="/" > <h3> Pathanto </h3></a>                 
                    </div>
           </div>

      <div class="col-lg-6 header-contact col-md-6" >
                   


      </div>
            

   </div>


  
    <section id="header"  >
      
      <nav class="navbar navbar-expand-lg  navbar-light bg-light " data-toggle="sticky-onscroll">

  

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav ">
       <li class="nav-item ">
         <a class="nav-link navbar-brand dropbtn " href="/">Home </a>
       </li>
       
       
         
              
     <li class="nav-item " >
        <div class="dropdown">
               <button class="dropbtn">CBSE</button>
         <div class="dropdown-content">

            <a href="/Pathanto/Class 6/class 6-cbse">Class 6</a>
            <a href="/Pathanto/Class 7/class 7-cbse">Class 7</a>
            <a href="/Pathanto/Class 8/class 8-cbse">Class 8</a>
            <a href="/Pathanto/Class 9/class 9-cbse">Class 9</a>
            <a href="/Pathanto/Class 10/class 10-cbse">Class 10</a>

        </div>
      </div>
    </li>
     </li>
         
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">LOGIN</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-user-plus"></i>&nbsp;&nbsp;SignUp</a>
      </li>-->
                 

        </ul>

            </div>
         </nav>

  </section>
  


  <section class="mobile-header" >
    <nav >
          
   <ul class="navigation" style="padding-left: 5px">
    
       <li class="navItem home">
       <button class="menu"> <i class="fas fa-bars"></i></button>
       

      </li> 
      
      <li class="navItem ">
        <a class="navlink" href="/"><abbr title="home"> <i class="fas fa-h-square"></i> </abbr> <span class="sr-only">(current)</span></a>
      </li>
       
    
      
      

  </ul>
</nav>

</section>
<div class="panel" >
<center>
<a class="home-menu-item" href="/Pathanto/ncert-notes">Notes</a>
<a class="home-menu-item" href="/Pathanto/note-book">Books</a>
<a class="home-menu-item" href="/Pathanto/Worksheet.php">Worksheets</a>
</center>

</div>


<div class="container" >
    
    
    
   
</div>


<div class="footer" >
 <p>	&copy;2021,Pathanto.All rights Reserved</p>
 <p style="font-size:14px;  position: absolute;
  right:0px; ">If you have any issue with image email us on <b>studywithaamir@pathanto.com</b> we will remove it</p>
</div>

<script src="/Pathanto/javascript.js" type='text/javascript'></script>

</body>
</html


