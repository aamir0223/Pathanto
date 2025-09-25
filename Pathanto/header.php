<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <?php $currentPageUrl = $_SERVER["REQUEST_URI"]; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta property="og:url" content="https://<?php echo $_SERVER['SERVER_NAME'] ?><?php echo $currentPageUrl ?> ">
  <meta property="og:site_name" content="Pathanto">
  <meta property="og:image" content="https://<?php echo $_SERVER['SERVER_NAME'] ?>/Pathanto/image/pathanto.jpg">
  <meta property="og:image:type" content="image/png">
  <meta name="robots" content="index,follow">
  <link rel="stylesheet" href="/Pathanto/public/css/styles.css">
  <link rel="stylesheet" href="/Pathanto/public/css/blog.css">
  <link rel="stylesheet" href="/Pathanto/public/css/old-style.css">
  <link rel="stylesheet" href="/Pathanto/public/css/pages.css">
  <link rel="stylesheet" href="/Pathanto/public/css/chapterList.css">
  <link rel="stylesheet" href="/Pathanto/public/css/common.css">
   <link rel="stylesheet" href="/Pathanto/public/css/quize.css">
  <link rel="stylesheet" href="/Pathanto/public/css/question.css">
  <link rel="stylesheet" href="/Pathanto/public/css/auth.css">
  <link rel="stylesheet" href="/Pathanto/public/css/dashboard.css">
  <link rel="stylesheet" href="/Pathanto/public/css/leaderboard.css">
  
 


  <link rel="canonical"  href="https://<?php echo $_SERVER['SERVER_NAME'] ?><?php echo $currentPageUrl ?>"/>
  <link rel="shortcut icon" href="https://<?php echo $_SERVER['SERVER_NAME'] ?>/favicon.ico">

  <!-- bootstarp link-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">


  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

  <!-- costom css-->

  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
  <!-- jaquery link------->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <!-- jaquery link------->
  

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2890698937074897"
    crossorigin="anonymous"></script>


  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-B84MWDKR48"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-B84MWDKR48');
</script>

<script async src="https://fundingchoicesmessages.google.com/i/pub-2890698937074897?ers=1"></script><script>(function() {function signalGooglefcPresent() {if (!window.frames['googlefcPresent']) {if (document.body) {const iframe = document.createElement('iframe'); iframe.style = 'width: 0; height: 0; border: none; z-index: -1000; left: -1000px; top: -1000px;'; iframe.style.display = 'none'; iframe.name = 'googlefcPresent'; document.body.appendChild(iframe);} else {setTimeout(signalGooglefcPresent, 0);}}}signalGooglefcPresent();})();</script>
  


</head>

<body>

  <!--<div class="row" id="logo-header">-->
  <!--  <div class="col-lg-6 col-md-6">-->
  <!--    <div class="logo">-->
  <!--      <a class="navbar-brand title-heading" href="/"><img class="logoimage " src="/Pathanto/image/logo.jpg" width="30"-->
  <!--          height="30"> Pathanto</a>-->
  <!--    </div>-->
  <!--  </div>-->

  <!--  <div class="col-lg-6 header-contact col-md-6">-->


  <!--  </div>-->


  <!--</div>-->




  <?php include "navBar.php" ?>


  <section class="mobile-header">
    <nav>


      <ul class="navigation" style="padding-left: 5px">

        <li class="navItem home">
          <button class="menu"> <i class="fas fa-bars"></i></button>


        </li>

        <li class="navItem ">
          <a class="navlink" href="/"><abbr title="home"> <i class="fas fa-h-square"></i> </abbr> <span
              class="sr-only">ome</span></a>
        </li>
        <li class="navItem ">
          <div class="dropdown">
            <button class="dropbtn">NCERT SOLUTION</button>
            <div class="dropdown-content">

              <div class="dropdown1">
                <button class="dropbtn">Class 6</button>
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
        <li class="navItem ">
          <div class="dropdown">
             <button class="dropbtn" onclick="location.href='https://pathanto.com/blogs';">Blogs</button>
             </div>
        </li>
      </ul>
    </nav>
  </section>
