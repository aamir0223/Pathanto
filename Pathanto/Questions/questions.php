<title>NCERT|CBSE|Question -pathanto.com</title>
<meta name="description"
    content="Explore NCERT CBSE questions on our page for comprehensive learning. Ace exams with curated practice sets. Enhance your academic journey with us!">
<meta name="keywords" content="NCERT, CBSE, questions, practice sets, exam preparation, academic success, curated resources">

<meta name="og:title" content="NCERT|CBSE|Question -pathanto.com ">

<?php include "../header.php"; ?>


<li class="navItem more-topic">

    <button class="dropbtn ">More+</button>

</li>
</ul>
</nav>
</section>


<?php include "../more-topic.php"; ?>
<?php include "../togaleMenu.php"; ?>


  <div class="row">
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
         
      </div>
      
      <div class="col-lg-6 jumbotron">
          <h1 class="heading-question"><b>Questions for you</b></h1>
          <div class="search-bar ">
  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="m18.031 16.617l4.283 4.282l-1.415 1.415l-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9s9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617m-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.867-3.133-7-7-7s-7 3.133-7 7s3.133 7 7 7a6.977 6.977 0 0 0 4.875-1.975z"/></svg>
    <input type="text" id="search" name="search" oninput="searchQuestions()" placeholder=" Search questions here">
</div>
  <?php include "../add-container.php"; ?>
          <div id="questionsList">
    <!-- Questions will be displayed here dynamically -->
         </div>
         
           <?php include "../add-container.php"; ?>

         <div id="pagination">
    <!-- Pagination links will be displayed here dynamically -->
        </div>
      </div>
      
      <div class="col-lg-3">
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
  </div>



<script>
    var currentPage = 1;

    function searchQuestions() {
        currentPage = 1; // Reset to the first page when searching
        fetchQuestions();
    }

    function fetchQuestions() {
        var searchTerm = document.getElementById('search').value;
        var url = 'get_questions.php?search=' + encodeURIComponent(searchTerm) + '&page=' + currentPage;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                displayQuestions(data.questions);
                displayPagination(data.totalPages);
            })
            .catch(error => console.error('Error:', error));
    }

  function displayQuestions(questions) {
    var questionsList = document.getElementById('questionsList');
    questionsList.innerHTML = '';

    if (questions.length === 0) {
        // If there are no questions, display a message
        var noQuestionsMessage = document.createElement('div');
        noQuestionsMessage.classList.add('no-questions-message');
        noQuestionsMessage.innerText = 'No Questions found.';
        questionsList.appendChild(noQuestionsMessage);
    } else {
        questions.forEach(function (question) {
            var questionDiv = document.createElement('div');
            questionDiv.classList.add('question');

            // Generate a URL-friendly version of the question title
            var titleUrl = encodeURIComponent(question.question_text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/-$/, ''));

            questionDiv.innerHTML = '<h1 class="question-title"><a rel="canonical" href="answer/' + question.id + '/' + titleUrl + '">' + question.question_text + '</a></h1>';
            questionsList.appendChild(questionDiv);
        });
    }
}
   
    function displayPagination(totalPages) {
        var paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = '';

        for (var i = 1; i <= totalPages; i++) {
            var pageLink = document.createElement('span');
            pageLink.innerText = i;
            pageLink.onclick = function () {
                currentPage = parseInt(this.innerText);
                fetchQuestions();
            };

            if (i === currentPage) {
                pageLink.classList.add('current');
            }

            paginationDiv.appendChild(pageLink);
        }
    }

    // Delayed execution of the search function to avoid excessive requests while typing
    var delayTimer;
    document.getElementById('search').addEventListener('input', function () {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(searchQuestions, 500); // Adjust the delay as needed (in milliseconds)
    });

    fetchQuestions();
</script>

<?php include '../footer.php'; ?>
