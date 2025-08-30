<?php
// Include config file
require_once "./../config.php";

// Fetch subject, class, and chapters from the URL parameters
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$class = isset($_GET['class']) ? $_GET['class'] : '';


// Fetch all classes and chapters for the specified subject and class from the database
$sql = "SELECT DISTINCT class, chapter, chapter_name FROM quiz_table WHERE subject = '$subject' AND class = '$class'";
$result = $conn->query($sql);

// Store classes and chapters in arrays
$classes = array();
$chapters = array();
while ($row = $result->fetch_assoc()) {
    $classes[$row['class']][] = array('chapter' => $row['chapter'], 'chapter_name' => $row['chapter_name']);
    $chapters[$row['chapter']] = true; // Using an associative array to ensure unique chapters
}

$title = ''; // Set a default title
$descriptionTitle = ''; // Set a default title
$metaDesc = ''; // Set a default title
$metaKeyword = ''; // Set a default title

if (!empty($subject)) {
 
    $sqlTitle = "SELECT  description_title, meta_desc, meta_keyword, heading  FROM quiz_table WHERE subject = '$subject'AND class = '$class' LIMIT 1";
   
    $resultTitle = $conn->query($sqlTitle);
     
    if ($resultTitle->num_rows > 0) {
        $rowTitle = $resultTitle->fetch_assoc();
        $title = $rowTitle['heading'];
         $descriptionTitle = $rowTitle['description_title'];
          $metaDesc = $rowTitle['meta_desc'];
           $metaKeyword = $rowTitle['meta_keyword'];
    }
}


$conn->close();

?>
    <title> <?php echo $title ?> - pathanto.com</title>
    <meta name="description"
        content="<?php echo $metaDesc ?>">
    <meta name="keywords" content="<?php echo $metaKeyword ?> ">
    <meta name="og:title" content="<?php echo $title ?> - pathanto.com ">
  

<?php include "../header.php"; ?>

<li class="navItem more-topic">

    <button class="dropbtn ">More+</button>

</li>
</ul>
</nav>
</section>
<?php include "../more-topic.php"; ?>

<div class="panel">
<p class="chapters"> <a href="../quize/chapters.php?class=class6&subject=science">Mcq questions for Class 6 Science</a></p>
<p class="chapters"> <a href="../quize/chapters.php?class=class7&subject=science">Mcq questions for Class 7 Science</a></p>
<p class="chapters"> <a href="../quize/chapters.php?class=class8&subject=science">Mcq questions for Class 8 Science</a></p>
<p class="chapters"> <a href="../quize/chapters.php?class=class9&subject=science">Mcq questions for Class 9 Science</a></p>
<p class="chapters"> <a href="../quize/chapters.php?class=class10&subject=science">Mcq questions for Class 10 Science</a></p>
  </div>



    <div class="quize-question-main-container">
        
         <section class="ads" style="margin-bottom:40px">
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
    
        <h1 class="quize-question-main-container-h1"><?php echo $title; ?></h1>
        <h1 style="margin:20px 30px; font-size:13px;"><?php echo $descriptionTitle; ?></h1>

        <div class="quize-question-main-container-wraper">
            <div class="quize-question-main-container-wraper-left">

                <?php foreach ($classes as $class => $classChapters): ?>
                    <?php foreach ($classChapters as $chapterInfo): ?>

                        <a href="/Pathanto/quize/quize.php?class=<?php echo rawurlencode($class); ?>&chapter=<?php echo $chapterInfo['chapter']; ?>&chapter_name=<?php echo "{$chapterInfo['chapter_name']}"; ?>&subject=<?php echo "$subject"; ?>"
                            class="quize-question-main-container-wraper-left-class-link card">
                            <img src="../public/images/science.png" alt="<?php echo "{$chapterInfo['chapter_name']}"; ?>"
                                style="max-width:50px;margin:auto">
                            <?php echo "{$chapterInfo['chapter_name']}"; ?>
                            
                        </a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <div class="quize-question-main-container-wraper-right">
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
<br><br>
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
    </div>

   <?php include '../footer.php'; ?>

