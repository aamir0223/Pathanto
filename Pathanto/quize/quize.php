<?php
require_once "./../config.php";

// Fetch quizzes based on the selected class and chapter from the database
$class = isset($_GET['class']) ? $_GET['class'] : '';
$chapter = isset($_GET['chapter']) ? $_GET['chapter'] : '';
$chapter_name = isset($_GET['chapter']) ? $_GET['chapter_name'] : '';
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$classes = array();
$chapters = array();
if (!empty($class) && is_numeric($chapter)) {
    $sql = "SELECT * FROM quiz_table WHERE class = ? AND chapter = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $class, $chapter);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store quiz data in an array
    $quizData = array();
    while ($row = $result->fetch_assoc()) {
        $quizData[] = $row;
    }
    
    $stmt->close();
    
    // Fetch subject, class, and chapters from the URL parameters



// Fetch all classes and chapters for the specified subject and class from the database
$sql = "SELECT DISTINCT class, chapter, chapter_name FROM quiz_table WHERE subject = '$subject' AND class = '$class'";
$result = $conn->query($sql);

// Store classes and chapters in arrays

while ($row = $result->fetch_assoc()) {
    $classes[$row['class']][] = array('chapter' => $row['chapter'], 'chapter_name' => $row['chapter_name']);
    $chapters[$row['chapter']] = true; // Using an associative array to ensure unique chapters
}

}
// Close the database connection
$conn->close();
?>



<?php
function getChapterInfo($quizData, $chapterName) {
    $filteredData = array_filter($quizData, function ($question) use ($chapterName) {
        return $question['chapter_name'] === $chapterName;
    });

    // Extract subject, meta_desc, and title
    $subject = !empty($filteredData) ? $filteredData[0]['subject'] : ' ';
    $metaDesc = !empty($filteredData) ? $filteredData[0]['meta_desc'] : '';
    $title = !empty($filteredData) ? $filteredData[0]['title'] : '';
    $descriptionTitle = !empty($filteredData) ? $filteredData[0]['description_title'] : '';
    $metaKeyword = !empty($filteredData) ? $filteredData[0]['meta_keyword'] : '';

    return [
        'subject' => $subject,
        'meta_desc' => $metaDesc,
        'title' => $title,
        'meta_keyword'=>$metaKeyword,
        'description_title'=>$descriptionTitle
    ];
}


$chapterInfo = getChapterInfo($quizData, $chapter_name);

// Access the information:
$subject = $chapterInfo['subject'];
$metaDesc = $chapterInfo['meta_desc'];
$title = $chapterInfo['title'];
$metaKeyword = $chapterInfo['meta_keyword'];
$descriptionTitle = $chapterInfo['description_title'];


?>

<title>MCQ questions for <?php
            $formattedClass = substr_replace($class, ' ', 5, 0);
            echo $formattedClass;
        ?><?php echo $subject ?>  Chapter <?php echo $chapter; ?> <?php echo $chapter_name; ?> -pathanto.com</title>
<meta name="description"
    content="<?php echo $metaDesc ?>">
<meta name="keywords" content="<?php echo $metaKeyword ?>">

<meta name="og:title" content="MCQ questions for <?php
            $formattedClass = substr_replace($class, ' ', 5, 0);
            echo $formattedClass;
        ?><?php echo $subject ?>  Chapter <?php echo $chapter; ?> <?php echo $chapter_name; ?> -pathanto.com  ">

<?php include "../header.php"; ?>


<li class="navItem more-topic">

    <button class="dropbtn ">More+</button>

</li>
</ul>
</nav>
</section>


<?php include "../more-topic.php"; ?>

<div class="panel">

   <?php foreach ($classes as $class => $classChapters): ?>
                    <?php foreach ($classChapters as $chapterInfo): ?>
                        
                         <p class="chapters"><a href="/Pathanto/quize/quize.php?class=<?php echo rawurlencode($class); ?>&chapter=<?php echo $chapterInfo['chapter']; ?>&chapter_name=<?php echo "{$chapterInfo['chapter_name']}"; ?>&subject=<?php echo "$subject"; ?>">
                         <?php echo "{$chapterInfo['chapter']}"; ?> <?php echo "{$chapterInfo['chapter_name']}"; ?>
                            
                        </a>
                        </p>
                    <?php endforeach; ?>
                <?php endforeach; ?>

    
</div>

<script>
    let currentQuestion = 0;

    function showQuestion(questionNumber) {
        $('.mcq-question').hide();
        $('.options').hide();

        $(`#question-${questionNumber}`).show();
        $(`#options-${questionNumber}`).show();

        // Show or hide next/previous buttons based on the current question
        if (questionNumber === 0) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }

        if (questionNumber === <?php echo count($quizData) - 1; ?>) {
            $('#nextBtn').hide();
            $('#submitBtn').show();
        } else {
            $('#nextBtn').show();
            $('#submitBtn').hide();
        }
    }
    
      function showQuestion2(questionNumber) {
        $('.mcq-question').hide();
        $('.options').hide();

        $(`#question-${questionNumber}`).show();
        $(`#options-${questionNumber}`).show();
          $('#downloadAllBtn').show();
             $('#viewBtn').hide();
          

        // Show or hide next/previous buttons based on the current question
        if (questionNumber === 0) {
            $('#prevBtn1').hide();
        } else {
            $('#prevBtn1').show();
        }

        if (questionNumber === <?php echo count($quizData) - 1; ?>) {
            $('#nextBtn1').hide();
        
        } else {
            $('#nextBtn1').show();
          
        }
    }

    function nextQuestion() {
        if (currentQuestion < <?php echo count($quizData) - 1; ?>) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    }

    function prevQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    }
    
    function nextQuestion1() {
        if (currentQuestion < <?php echo count($quizData) - 1; ?>) {
            currentQuestion++;
            showQuestion2(currentQuestion);
        }
    }

    function prevQuestion1() {
        console.log("ffff")
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion2(currentQuestion);
        }
    }

    $(document).ready(function () {
        showQuestion(currentQuestion);
         $('#prevBtn1').hide();
        $('#nextBtn1').hide();
          $('#downloadAllBtn').hide();
        
        
    });

    function submitQuiz() {
        let score = 0;
        let allQuestionsCorrect = true;

        // Reset styling for all questions
        $('.mcq-question').css('color', '');

        <?php foreach ($quizData as $questionData): ?>
            const selectedOption<?php echo $questionData['question_number']; ?> = $('input[name="question<?php echo $questionData['quiz_number']; ?>"]:checked').val();
            const correctOption<?php echo $questionData['question_number']; ?> = '<?php echo $questionData['correct_answer']; ?>';

            // Check if an option is selected and compare with the correct option
            if (
                selectedOption<?php echo $questionData['question_number']; ?> !== undefined &&
                selectedOption<?php echo $questionData['question_number']; ?> === correctOption<?php echo $questionData['question_number']; ?>
            ) {
                score++;
                indicateAnsweredQuestion(<?php echo $questionData['question_number']; ?>);
            } else {
                allQuestionsCorrect = false;
                indicateUnansweredQuestion(<?php echo $questionData['question_number']; ?>);
            }

            $('input[name="question<?php echo $questionData['quiz_number']; ?>"]').attr('disabled', true);
        <?php endforeach; ?>

        const resultElement = $('#result');
        resultElement.text(`Your Score: ${score}/${<?php echo count($quizData); ?>}`);

        // Hide next and previous buttons, show only result and retry/check buttons
        $('#prevBtn').hide();
        $('#nextBtn').hide();
        $('#submitBtn').hide();
        $('#result').show();
        $('#retryBtn').show();
          $('#viewBtn').show();
            $('.mcq-question').hide();
        $('.options').hide();
        $('#downloadAllBtn').show();
       
        
        console.log("$quizData",<?php echo json_encode($quizData); ?>)
    }

function retryQuiz() {
        // Reload the page to start the quiz again
        location.reload();
    }
    
    function view(){
          $('#prevBtn1').show();
        $('#nextBtn1').show();
        showQuestion2(currentQuestion);
    }
    
function indicateAnsweredQuestion(questionNumber) {
    const questionElement = $(`.mcq-question:contains("Question ${questionNumber}:")`);
    if (questionElement.length > 0) {
        // Indicate the answered question (you can customize this based on your needs)
        questionElement.css('color', 'green');
    }
}

function indicateUnansweredQuestion(questionNumber) {
    const questionElement = $(`.mcq-question:contains("Question ${questionNumber}:")`);
    if (questionElement.length > 0) {
        // Indicate the unanswered question (you can customize this based on your needs)
        questionElement.css('color', 'red');
    }
}


function downloadAllQuestions() {
    const quizData = <?php echo json_encode($quizData); ?>;
    const chapterName = "Chapter 1"; // Replace with your chapter name
    const className = "Class 10"; // Replace with your class name
    const websiteLink = "pathanto.com"; // Your website link
    
    const pages = document.createElement('div');
    let questionsContainer = document.createElement('div');
    questionsContainer.style.padding = '20px';
    pages.appendChild(questionsContainer);

    const maxPageHeight = 250; // Maximum height for each page
    let usedHeight = 0; // Used height on the current page

    for (let i = 0; i < quizData.length; i++) {
        const question = quizData[i];

        const estimatedQuestionHeight = 100; // Rough estimate of the height of one question with options

        // If the estimated height of the question would exceed the maximum height, start a new page
        if (usedHeight + estimatedQuestionHeight > maxPageHeight) {
            questionsContainer = document.createElement('div'); 
            questionsContainer.style.padding = '20px'; 
            pages.appendChild(questionsContainer);
            usedHeight = 0;
        }

        const questionDiv = document.createElement('div');
        questionDiv.style.padding = '10px';

        const questionText = document.createElement('p');
        questionText.innerHTML = `<strong>Question ${question['question_number']}:</strong> ${question['question']}`;
        questionDiv.appendChild(questionText);

        for (let j = 1; j <= 4; j++) {
            const optionText = document.createElement('p');
            optionText.innerHTML = `${j}. ${question['option' + j]}`;
            questionDiv.appendChild(optionText);
        }

        const correctAnswerText = document.createElement('p');
        correctAnswerText.innerHTML = `<strong>Correct Answer:</strong> ${question['correct_answer']}`;
        questionDiv.appendChild(correctAnswerText);

        questionsContainer.appendChild(questionDiv);
        usedHeight += estimatedQuestionHeight; // Increase the used height by the estimated height of the question
    }
    
    const watermark = document.createElement('div');
    watermark.innerHTML = 'pathanto.com';
    watermark.style.position = 'fixed';
    watermark.style.opacity = '0.6';
    watermark.style.fontSize = '3em';
    watermark.style.width = '100%';
    watermark.style.textAlign = 'center';
    watermark.style.transform = 'rotate(-45deg)';
    watermark.style.transformOrigin = '50% 50%';
    watermark.style.zIndex = '1000';
    watermark.style.top = '50%'; // Add this
    watermark.style.left = '50%'; // And this
    watermark.style.transform = 'translate(-50%, -50%) rotate(-45deg)'; // Adjust this

    // Add watermark to each page
    for (let i = 0; i < pages.childNodes.length; i++) {
        const page = pages.childNodes[i];
        const pageWatermark = watermark.cloneNode(true);
        page.appendChild(pageWatermark);
    }
    
     for (let i = 0; i < pages.childNodes.length; i++) {
        const page = pages.childNodes[i];

        // Create header and footer
        const header = document.createElement('div');
        const footer = document.createElement('div');

        // Set text
        header.innerHTML = chapterName;
        footer.innerHTML = `${className} | ${websiteLink}`;

        // Style header and footer
        header.style.width = '100%';
        header.style.textAlign = 'center';
        header.style.position = 'absolute';
        header.style.top = '0';

        footer.style.width = '100%';
        footer.style.textAlign = 'center';
        footer.style.position = 'absolute';
        footer.style.bottom = '0';

        // Add header and footer to page
        page.appendChild(header);
        page.appendChild(footer);

        const pageWatermark = watermark.cloneNode(true);
        page.appendChild(pageWatermark);
    }

    html2pdf(pages);
}

</script>



 <div class="row" style="margin:0px !important">


                 <div class="col-lg-3 quize-mobile-ads"> 
                 
                 
                 <div class="card" style="margin:50px 0px;padding:20px">
                 
                 <?php foreach ($classes as $class => $classChapters): ?>
                    <?php foreach ($classChapters as $chapterInfo): ?>
                        
                        <p style="margin-bottom:3px"><a href="/Pathanto/quize/quize.php?class=<?php echo rawurlencode($class); ?>&chapter=<?php echo $chapterInfo['chapter']; ?>&chapter_name=<?php echo "{$chapterInfo['chapter_name']}"; ?>&subject=<?php echo "$subject"; ?>"
                            style="font-size: 13px;font-weight: 500;color: #000;text-decoration: none;cursor: pointer; margin-bottom:5px">
                            Chapter:<?php echo "{$chapterInfo['chapter']}"; ?> <?php echo "{$chapterInfo['chapter_name']}"; ?>
                            
                        </a>
                        </p>
                    <?php endforeach; ?>
                <?php endforeach; ?>
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
<br>
</br>


                 </div>
                 <div class="col-lg-7 ">
                     
                     <section class="ads" style="margin-bottom:60px">
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
    
    
<div class="quiz-container">
    <?php if (!empty($quizData)): ?>
        <h1 class="mcq-heading">MCQ questions for  <?php
            $formattedClass = substr_replace($class, ' ', 5, 0);
            echo $formattedClass;
        ?> <?php echo $subject ?> Chapter <?php echo $chapter; ?> <?php echo $chapter_name; ?>  </h1>
         
         <div class="body-add-container">
 <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2890698937074897"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:inline-block;"
     data-ad-client="ca-pub-2890698937074897"
     data-ad-slot="1956626337"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

  </div>
         
         
     
  
        <div class="mcq-quiz-container-wraper">
            <?php foreach ($quizData as $index => $questionData): ?>
                <div class="mcq-question" id="question-<?php echo $index; ?>" style="display: none;">
                    <?php echo "Question {$questionData['question_number']}: {$questionData['question']}"; ?>
                </div>
                <div class="options" id="options-<?php echo $index; ?>" style="display: none;">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="option">
                            <input type="radio" name="question<?php echo $questionData['quiz_number']; ?>" value="<?php echo $questionData['option' . $i]; ?>">
                            <?php echo $questionData['option' . $i]; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endforeach; ?>

            <button id="prevBtn" onclick="prevQuestion()">Previous</button>
            <button id="nextBtn" onclick="nextQuestion()">Next</button>
           
            <button id="submitBtn" style="display: none;" onclick="submitQuiz()">Submit</button>
            
            
            
            <div id="result" style="display: none;"></div>
            
            <div class="quize-button">
             <button id="prevBtn1" onclick="prevQuestion1()">Previous</button>
            <button id="nextBtn1" onclick="nextQuestion1()">Next</button>
            <button id="retryBtn" style="display: none;" onclick="retryQuiz()">Retry</button>
             <button id="viewBtn" style="display: none;" onclick="view()">Check your answer </button>
            </div>
           
             <button id="downloadAllBtn"  onclick="downloadAllQuestions()">Download All Questions with answer</button>
         
        </div>
    <?php else: ?>
        <p>No quizzes found for the selected class and chapter.</p>
    <?php endif; ?>
    
 
  
  
   <section class="ads" style="margin-bottom:60px">
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
    
    
           <h1 class="mcq-description"> <?php echo $descriptionTitle ?></h1>
</div>
</div>


 <div class="col-lg-2 ">
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
    
<?php include '../footer.php'; ?>
