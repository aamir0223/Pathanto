<title>
    ContactUs
</title>

<?php include "header.php";?>


<li class="navItem more-topic" >
      
               <button class="dropbtn ">More+</button>
              
</li>
</ul>
</nav>
</section>
<?php include "more-topic.php";?>
<?php include "togaleMenu.php";?>
  
            
      <div class="container"> 
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- top-add -->
<ins class="adsbygoogle top-ads"
     style="display:block"
     data-ad-client="ca-pub-2890698937074897"
     data-ad-slot="9088704451"
   
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<div class="row">

<div class="col-lg-6">


<h4 class="sc-chapter-name">Send Us a Massage</h4>
<p style="text-align:center;">We are here to help you</p>
<div style="padding:10px;">

<form   method="post">
  <div class="form-group">
    
    Name: <input type="text" class="form-control" name="name"  placeholder="Name" required>
  </div>
  
  <div class="form-group">
   Email:<input type="email" class="form-control" name="email" id="exampleFormControlInput1" placeholder="Email" required>
  </div>
  <div class="form-group green-border-focus">
  <label for="exampleFormControlTextarea5">Massage</label>
  <textarea class="form-control"  name="massage"  id="exampleFormControlTextarea5" rows="3" required ></textarea>
</div>
    <button type="submit" name= "submit" class="btn btn-primary">Submit</button>
</form>
</div>
</div>
<div class="col-lg-6" >
    <center>
    <h2> Contact Us</h2>
    <!--<p>  <a  style="font-size:30px; text-decoration:none; color:#fb3640; " href="https://www.instagram.com/pathanto381/"> <i class="fab fa-instagram"></i></a></p>-->

    <p>Email Us: info@pathanto.com</p>
    </center>
</div>
</div>
</div>
<?php include "footer.php";?>
<?php  
if(isset($_POST['submit']))
{
$servername = "localhost";
$database = "u285245875_CommentData";
$username = "u285245875_pathanto_soni";
$password = "Arman23@31";;

$Name= $_POST['name'];
$Email= $_POST['email'];
$Massage= $_POST['massage'];

$conn = mysqli_connect($servername, $username, $password, $database); 
 $sqlInsert = "INSERT INTO Studentlogin VALUES ('$Name' ,'$Massage' , '$Email')";  
                 if(mysqli_query($conn, $sqlInsert)==true)
                  {  
                     
                  header("Location: /Pathanto/contact-Us");

                    exit;
  
                  }
                  
}
                  
                  
?>                  
  