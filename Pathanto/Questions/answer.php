<?php
header('Content-Type: text/html; charset=utf-8');
include "./../config.php";
$questionId = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Check if the user has already liked this question (using session)
$Answer='';
$Questions='';

    $sql = "SELECT q.id, q.question_text, a.answer_text, GROUP_CONCAT(t.tag_name) AS tags, q.likes
            FROM questions q
            LEFT JOIN answers a ON q.id = a.question_id
            LEFT JOIN question_tags qt ON q.id = qt.question_id
            LEFT JOIN tags t ON qt.tag_id = t.id
            WHERE q.id = ?
            GROUP BY q.id, q.question_text, a.answer_text, q.likes";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();
   // Fetch the result row
if ($row = $result->fetch_assoc()) {
    // Set $Answer with the value of answer_text
    $Questions = htmlspecialchars($row['question_text']);
     $Answer = $row['answer_text'];

}
    
?>

<title><?php
$title = $_GET['title'];
$decodedTitle = str_replace('-', ' ', $title);
echo $decodedTitle;
?> pathanto.com</title>
 <meta charset="UTF-8">
<?php $Answer = strip_tags(htmlspecialchars_decode($row['answer_text']));
echo '<meta name="description" content="' . $Answer . '">';

 ?>


      
<meta name="keywords" content="<?php
$title = $_GET['title'];
$decodedTitle = str_replace('-', ' ', $title);
echo $decodedTitle;
?> ">

<meta name="og:title" content="<?php
$title = $_GET['title'];
$decodedTitle = str_replace('-', ' ', $title);
echo $decodedTitle;
?>-pathanto.com ">

<?php include "../header.php"; ?>


<li class="navItem more-topic">

    <button class="dropbtn ">More+</button>

</li>
</ul>
</nav>
</section>


<?php include "../more-topic.php"; ?>
<?php include "../togaleMenu.php"; ?>


 <div class="row jumbotron answer-row">
     <div class="col-lg-3 quize-mobile-ads">
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
<br>
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
<br>
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
     <div class="col-lg-6">
             <?php include "../add-container.php"; ?>

         
         <?php

    
    
   echo '<div id="questionsList" class="answerList">';
if ($Questions) {
    $Answer = $row['answer_text'];
    echo'<h3 class="question"> Question</h3>';
    echo '<h1 class="question-title"><b>' . $Questions. '</b></h1>';
     echo'<h3 class="answer"> answer</h3>';
    
    echo '<div>' . $Answer . '</div>';
} else {
    echo '<p>Question not found.</p>';
    $stmt->close();
    exit();
}

         echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2890698937074897"
     crossorigin="anonymous"></script>
<!-- New banner -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2890698937074897"
     data-ad-slot="7504970090"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>';
        echo '</div>';
        

    $stmt->close();

  
    $sqlRelated = "SELECT q.id, q.question_text
                   FROM questions q
                   LEFT JOIN question_tags qt ON q.id = qt.question_id
                   WHERE qt.tag_id IN (SELECT tag_id FROM question_tags WHERE question_id = ?)
                   AND q.id != ?
                   LIMIT 5";

    $stmtRelated = $conn->prepare($sqlRelated);
    $stmtRelated->bind_param("ii", $questionId, $questionId);
    $stmtRelated->execute();
    $resultRelated = $stmtRelated->get_result();
    
    include "../add-container.php";
 echo '<div class="suggested-questions">';
   echo '<h3><b>Related Questions</b></h3>';
   while ($rowRelated = $resultRelated->fetch_assoc()) {
   
    
    // Encode the question text in PHP
    $escapedQuestionText = htmlspecialchars($rowRelated['question_text']);
    
    // Output the link with JavaScript onclick event
    echo '<h1 class="question-title"><a href="#" onclick="showEscapedText(\'' . $escapedQuestionText . '\', ' . $rowRelated['id'] . '); return false;">' . $escapedQuestionText . '</a></h1>';
    
   
}
 echo '</div>';

    $stmtRelated->close();
    
    
   
    ?>
     </div>
     <div class="col-lg-3">
         <?php

    $sqlRelated = "SELECT q.id, q.question_text
                   FROM questions q
                   LEFT JOIN question_tags qt ON q.id = qt.question_id
                   WHERE qt.tag_id IN (SELECT tag_id FROM question_tags WHERE question_id = ?)
                   AND q.id != ?
                   LIMIT 5";

    $stmtRelated = $conn->prepare($sqlRelated);
    $stmtRelated->bind_param("ii", $questionId, $questionId);
    $stmtRelated->execute();
    $resultRelated = $stmtRelated->get_result();
    
    include "../add-container.php";
 echo '<div class="suggested-questions">';
   echo '<h3><b>Related Questions</b></h3>';
   while ($rowRelated = $resultRelated->fetch_assoc()) {
   
    
    // Encode the question text in PHP
    $escapedQuestionText = htmlspecialchars($rowRelated['question_text']);
    
    // Output the link with JavaScript onclick event
    echo '<h1 class="question-title"><a  rel="canonical" href="#" onclick="showEscapedText(\'' . $escapedQuestionText . '\', ' . $rowRelated['id'] . '); return false;">' . $escapedQuestionText . '</a></h1>';
    
   
}
 echo '</div>';

    $stmtRelated->close();
    
    
   
    ?>    
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



<script>
    function showEscapedText(escapedText, questionId) {
        // Generate URL-friendly version of the question text in JavaScript
        var urlFriendlyQuestionText = encodeURIComponent(escapedText.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/-$/, ""));
        
        // Redirect to the answer page with the URL-friendly question text
        window.location.href = '/Pathanto/Questions/answer/' + questionId + '/' + urlFriendlyQuestionText;
    }
</script>


<?php include '../footer.php'; ?>
