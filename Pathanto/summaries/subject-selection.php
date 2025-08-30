<title>NCERT Summaries and Notes -pathanto.com</title>
<meta name="description" content="Hey there, welcome to our chapter summary page! Here, you'll find condensed summaries of all the chapters covered in our science curriculum. Whether you're in class 6, 7, 8, 9, or 10, these summaries are designed to give you a quick overview of each chapter's key concepts and main points  Chapter wise summaries for Class 6 to 10 ">

         
      <meta name="og:title" content="NCERT Summaries and Notes -pathanto.com ">
     
<?php include "../header.php"; ?>

<li class="navItem more-topic">

     <button class="dropbtn ">More+</button>

</li>
</ul>
</nav>
</section>
<?php include "../more-topic.php"; ?>
<?php include "../togaleMenu.php"; ?>


<section class="pages">
    <section class="ads">
        <div class="ads-content">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2890698937074897"
     crossorigin="anonymous"></script>
<!-- Top new add -->
<ins class="adsbygoogle"
     style="display:inline-block;width:100%;height:90px"
     data-ad-client="ca-pub-2890698937074897"
     data-ad-slot="9285549497"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
        </div>
    </section>
    <div class="circle">

    </div>
    <div class="wrapper-h1">
        <h1>NCERT chapter wise  summaries</h1>
    </div>
     <p class="description-page" style="margin: 20px 30px">Hey there, welcome to our chapter summary page! Here, you'll find condensed summaries of all the chapters covered in our science curriculum. Whether you're in class 6, 7, 8, 9, or 10, these summaries are designed to give you a quick overview of each chapter's key concepts and main points  Chapter wise summaries for Class 6 to 10 
!</p>

 <div class="wrapper">

        <div class="left">
            
            
              <?php
    // Include database connection
 include "./../config.php";

    // Fetch all distinct subjects from the database
    $sql = "SELECT DISTINCT subject FROM Chapters";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display list of subjects
     
        while ($row = $result->fetch_assoc()) {
            
            echo "
            
             <div class='card mcq-card'>
                <a href='class-selection?subject=" . $row['subject'] . "'>
                    <div class='card-body'>
                       
                        <h2> " . $row['subject'] . " </h2>
                    </div>
                </a>
            </div>";
            
            
            
         
        }
    
    } else {
        // No subjects found
        echo "No subjects found.";
    }
    ?>
            
            
            
         
            
        </div>
        <div class="right">
            
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2890698937074897"
     crossorigin="anonymous"></script>
<!-- right-verticles -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2890698937074897"
     data-ad-slot="2276453869"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

        </div>
    </div>

    

    <div class="inner-circle">
    </div>

  
</section>



<?php include '../footer.php'; ?>
