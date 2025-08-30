<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Happy Birthday</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Great Vibes', cursive;
    background-color: #ffccbc; /* Changed background color to a peachy shade */
    text-align: center;
    margin: 0;
    padding: 0;
    position: relative;
  }
  
  #header {
    background-color: #ff80ab;
    color: #fff;
    padding: 20px 0;
  }
  
  #header h1 {
    font-size: 48px;
    margin-bottom: 10px;
  }
  
  #container {
    max-width: 800px;
    margin: 50px auto;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    padding: 30px;
    position: relative;
    overflow: hidden;
  }
  
  .section {
    margin-bottom: 40px;
  }
  
  .section h2 {
    color: #ff80ab;
    font-size: 32px;
    margin-bottom: 10px;
  }
  
  .section p {
    color: #333;
    font-size: 20px;
    line-height: 1.6;
    margin-bottom: 20px;
  }

  .balloon {
    position: absolute;
    font-size: 40px;
    animation: float 6s ease-in-out infinite;
  }

  .cake {
    position: absolute;
    font-size: 40px;
    animation: bounce 3s ease-in-out infinite;
  }

  .present {
    position: absolute;
    font-size: 40px;
    animation: rotate 4s ease-in-out infinite;
    cursor: pointer;
  }

  .confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #f06292;
    border-radius: 50%;
    animation: fall 2s ease-out infinite;
  }

  @keyframes float {
    0% {
      transform: translateY(0) rotate(0);
    }
    50% {
      transform: translateY(-100px) rotate(45deg);
    }
    100% {
      transform: translateY(0) rotate(0);
    }
  }
  
  @keyframes bounce {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-20px);
    }
  }

  @keyframes rotate {
    0% {
      transform: rotate(0);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes fall {
    0% {
      transform: translateY(-100%);
      opacity: 0;
    }
    100% {
      transform: translateY(100vh);
      opacity: 1;
    }
  }

  /* Styles for photo gallery */
  .photo-gallery {
    margin-top: 30px;
  }

  .photo-gallery img {
    width: 200px;
    border-radius: 10px;
    margin: 10px;
    border: 2px solid #ff80ab;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }

  /* Styles for virtual gift */
  .gift-box {
    font-size: 40px;
    cursor: pointer;
    position: absolute;
    bottom: 10%;
    left: 50%;
    transform: translateX(-50%);
    animation: bounce 3s ease-in-out infinite;
  }

</style>
</head>
<body>

<!--<div id="header">-->
<!--  <h1>Happy Birthday Neha!</h1>-->
<!--</div>-->

<div id="container">
  <div class="section">
      <img src="/Pathanto/public/images/missing2.jpeg" alt="pathntu pathanto" style="width:300px;height:300px">
    <h2>We are  missing you !!!</h2>
    <!--<p>Wishing you a day filled with joy, laughter!</p>-->
    <!--<p>May your special day be as wonderful as you are. Happy Birthday!</p>-->
  </div>
  
  <!--<div class="section">-->
  <!--  <h2>by,</h2>-->
  <!--  <p>The only piece on the earth</p>-->
  <!--</div>-->

  <!-- Photo Gallery -->
  <!--<div class="section photo-gallery">-->
  <!--  <h2>Memorable Moments</h2>-->
  <!--   I still don't have any photo-->
    <!-- Add more photos as needed -->
  <!--</div>-->

  <!-- Virtual Gift -->
  <!--<div class="gift-box" onclick="showMessage('Happy Birthday! 🎁')">🎁</div>-->
</div>

<!-- Balloons -->
<!--<div class="balloon" style="top: 10%; left: 10%; animation-duration: 8s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 20%; left: 20%; animation-duration: 7s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 30%; left: 30%; animation-duration: 6s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 40%; left: 40%; animation-duration: 5.5s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 50%; left: 50%; animation-duration: 7.5s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 60%; left: 60%; animation-duration: 6s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 70%; left: 70%; animation-duration: 7s;">&#127880;</div>-->
<!--<div class="balloon" style="top: 80%; left: 80%; animation-duration: 6.5s;">&#127880;</div>-->

<!-- Cakes -->
<!--<div class="cake" style="bottom: 10%; left: 10%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 20%; left: 20%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 30%; left: 30%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 40%; left: 40%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 50%; left: 50%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 60%; left: 60%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 70%; left: 70%;">&#127874;</div>-->
<!--<div class="cake" style="bottom: 80%; left: 80%;">&#127874;</div>-->

<!-- Presents -->
<!--<div class="present" style="top: 20%; right: 10%;" onclick="showMessage('Happy Birthday Neha!')">&#127873;</div>-->
<!--<div class="present" style="top: 40%; right: 20%;" onclick="showMessage('Wishing you all the best!')">&#127873;</div>-->
<!--<div class="present" style="top: 60%; right: 30%;" onclick="showMessage('Hope your day is filled with surprises!')">&#127873;</div>-->
<!--<div class="present" style="top: 80%; right: 40%;" onclick="showMessage('Sending you lots of love and happiness!')">&#127873;</div>-->

<!-- Confetti -->
<!--<div class="confetti" style="top: 0; left: 5%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 15%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 25%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 35%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 45%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 55%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 65%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 75%;"></div>-->
<!--<div class="confetti" style="top: 0; left: 85%;"></div>-->

<script>
function showMessage(message) {
  var messageElement = document.createElement('div');
  messageElement.innerHTML = message;
  messageElement.style.position = 'fixed';
  messageElement.style.top = '50%';
  messageElement.style.left = '50%';
  messageElement.style.transform = 'translate(-50%, -50%)';
  messageElement.style.background = '#fff';
  messageElement.style.padding = '20px';
  messageElement.style.borderRadius = '10px';
  messageElement.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
  messageElement.style.zIndex = '9999';
  document.body.appendChild(messageElement);
  setTimeout(function() {
    messageElement.remove();
  }, 3000);
}
</script>

</body>
</html>
