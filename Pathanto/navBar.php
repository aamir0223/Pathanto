

<?php require_once __DIR__ . '/auth.php'; ?>
<section id="header">

  <nav class="navbar navbar-expand-lg  navbar-light  " data-toggle="sticky-onscroll">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ">
        <li class="nav-item ">
          <a class="navbar-brand title-heading" href="/" style="color:#ffffff"> Pathanto</a>
        </li>
        <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn " style="background:transparent">NCERT Solution</button>
            <div class="dropdown-content">

              <div class="dropdown1">
                <button class="dropbtn ">Class 6</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 6/class 6-Science.php">Science</a>
                  <a href="/Pathanto/Class 6/class 6-Maths.php">Maths</a>


                </div>
              </div>

              <div class="dropdown1">
                <button class="dropbtn">Class 7</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 7/class 7-Science.php">Science</a>
                  <a href="/Pathanto/Class 7/class 7-maths.php">Maths</a>


                </div>
              </div>
              <div class="dropdown1">
                <button class="dropbtn">Class 8</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 8/maths.php">Maths</a>


                </div>
              </div>
              <div class="dropdown1">
                <button class="dropbtn">Class 9</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 9/maths.php">Maths</a>


                </div>
              </div>

              <div class="dropdown1">
                <button class="dropbtn">Class 10</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 10/science.php">Science</a>
                  <a href="/Pathanto/Class 10/maths.php">Maths</a>


                </div>
              </div>

            </div>
          </div>
        </li>
        <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent">Notes</button>
            <div class="dropdown-content">


              <div class="dropdown1">
                <button class="dropbtn">Class 10</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 10/science-notes.php">Science</a>



                </div>
              </div>

            </div>
          </div>
        </li>

        <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent">CBSE</button>
            <div class="dropdown-content">

              <a href="/Pathanto/class6.php">Class 6</a>
              <a href="/Pathanto/class7.php">Class 7</a>
              <a href="/Pathanto/class8.php">Class 8</a>
              <a href="/Pathanto/class9.php">Class 9</a>
              <a href="/Pathanto/class10.php">Class 10</a>

            </div>
          </div>
        </li>
        
        <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent">Worksheets</button>
            <div class="dropdown-content">


              <div class="dropdown1">
                <button class="dropbtn">Class 10</button>
                <div class="dropdown-content1">

                  <a href="/Pathanto/Class 10/ScienceWorksheets/Class 10-Science-WorkSheet.php">Science</a>



                </div>
              </div>

            </div>
          </div>
        </li>
        
         <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent" onclick="window.open('/Pathanto/quize/mcqs','_self')">MCQ's Questions</button>

          </div>
        </li>
        
         <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent" onclick="window.open('https://pathanto.com/Pathanto/Questions/questions','_self')">NCERT Questions</button>

          </div>
        </li>
        
        <li class="nav-item ">
          <div class="dropdown">
            <button class="dropbtn" style="background:transparent" onclick="window.open('https://pathanto.com/Pathanto/summaries/subject-selection','_self')">Summaries</button>

          </div>
        </li>

        <?php if (current_user_id()): ?>
        <li class="nav-item">
          <a class="nav-link" href="/Pathanto/dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Pathanto/logout.php">Logout</a>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="/Pathanto/login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Pathanto/register.php">Register</a>
        </li>
        <?php endif; ?>

      </ul>

    </div>
    <div class="d-flex flex-row bd-highlight navbar-nav ">
         <div class="p-2 bd-highlight nav-item  " style="background:transparent"> <a class="nav-link   "
          href="https://pathanto.com/blogs">Blogs </a>
      </div>
      
      <div class="p-2 bd-highlight nav-item  " style="background:transparent"> <a class="nav-link   "
          href="/Pathanto/aboutus.php">About </a>
      </div>
      <div class="p-2 bd-highlight nav-item " style="background:transparent"> <a class="nav-link  dropbtn "
          href="/Pathanto/contact-Us.php" style="background:transparent">Contact Us </a></div>
          </div>
  </nav>

</section>