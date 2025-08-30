<?php
// Include database connection
include "./../config.php";

// Function to decode URL-encoded chapter title
function decodeChapterTitle($url)
{
    $parts = explode('/', $url);
    $encoded_title = end($parts);
    $decoded_title = str_replace('-', ' ', $encoded_title);
    return urldecode($decoded_title);
}

// Get the chapter title from the URL
$chapter_title = decodeChapterTitle($_SERVER['REQUEST_URI']);

// Fetch chapter details and metadata from the database based on the chapter title
$sql = "SELECT Chapters.chapter_title, ChapterDetails.meta_title, ChapterDetails.meta_description, ChapterDetails.page_title, ChapterDetails.summary_en, ChapterDetails.summary_hi
        FROM Chapters
        INNER JOIN ChapterDetails ON Chapters.chapter_id = ChapterDetails.chapter_id
        WHERE Chapters.chapter_title = '$chapter_title'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $chapter_title = $row['chapter_title'];
    $meta_title = $row['meta_title'];
    $meta_description = $row['meta_description'];
    $page_title = $row['page_title'];
    $summary_en = $row['summary_en'];
    $summary_hi = $row['summary_hi'];

    // Set dynamic meta tags
    echo "<title>$meta_title</title>";
    echo "<meta name='og:title' content='$meta_title'>";
    echo "<meta name='description' content='$meta_description'>";
} else {
    // If chapter not found, set default meta tags
    $chapter_title = "Chapter Not Found";
    $meta_title = "Chapter Not Found";
    $meta_description = "Chapter Not Found";
    $page_title = "Chapter Not Found";
    $summary_en = "";
    $summary_hi = "";

    // Set default meta tags
    echo "<title>$meta_title</title>";
    echo "<meta name='description' content='$meta_description'>";
}
?>

<?php include "../header.php"; ?>

<li class="navItem more-topic">
    <button class="dropbtn ">More+</button>
</li>
</ul>
</nav>
</section>
<?php include "../more-topic.php"; ?>
<!--<?php include "../togaleMenu.php"; ?>-->

<div class="panel">

  <?php
// Include database connection
include "./../config.php";

// Get the requested URL
$current_url = $_SERVER['REQUEST_URI'];

// Extract class and subject from the URL
if (preg_match('#/subject-(.*?)/class-(.*?)/#', $current_url, $matches)) {
    $subject = str_replace('-', ' ', urldecode($matches[1]));
    $class = str_replace('-', ' ', urldecode($matches[2]));
} else {
    // If class and subject are not found in the URL, handle the error
    echo "Class and subject not found in the URL.";
    exit;
}

// Fetch chapters based on class and subject from the database
$sql = "SELECT * FROM Chapters WHERE class = '$class' AND subject = '$subject'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Replace spaces with "-" in chapter title
        $chapter_title_url = str_replace(' ', '-', $row['chapter_title']);
        $class_title_url = str_replace(' ', '-', $row['class']);
        $subject_title_url = str_replace(' ', '-', $row['subject']);

        echo "
       
        <p class='chapters'>
            <a href='https://pathanto.com/Pathanto/summaries/chapter-details/subject-" . $subject_title_url . "/class-" . $class_title_url . "/" . $chapter_title_url . "'>
                
                    " . $row['chapter_title'] . "
              
            </a>
            </p>
       ";
    }
} else {
    // No chapters found for the selected class and subject
    echo "No chapters found for the selected class and subject.";
}
?>

    
</div>


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
    

    <div class="row">
        <div class="col-lg-3 quize-mobile-ads">
            
            <div class="card" style="margin:50px 0px;padding:20px">
            
<?php
// Include database connection
include "./../config.php";

// Get the requested URL
$current_url = $_SERVER['REQUEST_URI'];

// Extract class and subject from the URL
if (preg_match('#/subject-(.*?)/class-(.*?)/#', $current_url, $matches)) {
    $subject = str_replace('-', ' ', urldecode($matches[1]));
    $class = str_replace('-', ' ', urldecode($matches[2]));
} else {
    // If class and subject are not found in the URL, handle the error
    echo "Class and subject not found in the URL.";
    exit;
}

// Fetch chapters based on class and subject from the database
$sql = "SELECT * FROM Chapters WHERE class = '$class' AND subject = '$subject'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Replace spaces with "-" in chapter title
        $chapter_title_url = str_replace(' ', '-', $row['chapter_title']);
        $class_title_url = str_replace(' ', '-', $row['class']);
        $subject_title_url = str_replace(' ', '-', $row['subject']);

        echo "
       
        <p class='chapters'>
            <a href='https://pathanto.com/Pathanto/summaries/chapter-details/subject-" . $subject_title_url . "/class-" . $class_title_url . "/" . $chapter_title_url . "'>
                
                    " . $row['chapter_title'] . "
              
            </a>
            </p>
       ";
    }
} else {
    // No chapters found for the selected class and subject
    echo "No chapters found for the selected class and subject.";
}
?>


</div>

            
            
            
            
            
            
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

            <!--<?php include "classMenu.php"; ?> -->
            <!--           </div>-->
        </div>


        <div class="col-lg-7 ">
            <?php include "../../add-container.php"; ?>
            
            <div class="container">
                <center>
                    <h1><?php echo $chapter_title; ?></h1>
                </center>

                <div id="summary">
                    <?php
                    if ($language === 'english') {
                        echo $summary_en;
                    } elseif ($language === 'hindi') {
                        echo $summary_hi;
                    }
                    ?>
                </div>

                <div>
                    <h2>Chapter Summary</h2>
                    <?php
                    // Fetch and display chapter summary from the database
                    $sql_summary = "SELECT summary_en FROM ChapterDetails WHERE page_title = '$chapter_title'";
                    $result_summary = $conn->query($sql_summary);
                    if ($result_summary->num_rows > 0) {
                        while ($row_summary = $result_summary->fetch_assoc()) {
                            echo "<p style='font-size:20px'>" . $row_summary['summary_en'] . "</p>";
                        }
                    } else {
                        echo "<p>No summary available for this chapter.</p>";
                    }
                    ?>

                    <?php
                    // Fetch and display chapter summary from the database
                    $sql_summary = "SELECT summary_hi FROM ChapterDetails WHERE page_title = '$chapter_title'";
                    $result_summary = $conn->query($sql_summary);
                    if ($result_summary->num_rows > 0) {
                        while ($row_summary = $result_summary->fetch_assoc()) {
                            echo "<p style='font-size:20px'>" . $row_summary['summary_hi'] . "</p>";
                        }
                    } else {
                        echo "<p>No summary available for this chapter.</p>";
                    }
                    ?>
                </div>

                <!--<div>-->
                <!--    <h2>Questions Related to Chapter Summary</h2>-->
                    // <?php
                    // Fetch and display questions related to chapter summary from the database
                    // $sql_questions_summary = "SELECT question, answer FROM ChapterQuestionsSummary WHERE chapter_title = '$chapter_title'";
                    // $result_questions_summary = $conn->query($sql_questions_summary);
                    // if ($result_questions_summary->num_rows > 0) {
                    //     while ($row_question_summary = $result_questions_summary->fetch_assoc()) {
                    //         echo "<div>";
                    //         echo "<strong>Question:</strong> " . $row_question_summary['question'] . "<br>";
                    //         echo "<strong>Answer:</strong> " . $row_question_summary['answer'];
                    //         echo "</div>";
                    //     }
                    // } else {
                    //     echo "<p>No questions related to summary available for this chapter.</p>";
                    // }
                    // ?>
                <!--</div>-->


            </div>

        </div>
  <div class="col-lg-2">
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




</section>
<?php include '../footer.php'; ?>
