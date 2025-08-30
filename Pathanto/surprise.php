<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--  <meta charset="UTF-8">-->
<!--  <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--  <title>Surprise</title>-->
<!--  <style>-->
<!--    body {-->
<!--      margin: 0;-->
<!--      padding: 40px 0 0;-->
<!--      font-family: 'Segoe UI', sans-serif;-->
<!--      background-color: #ffe6ef;-->
<!--      background-image: url('https://www.transparenttextures.com/patterns/stardust.png');-->
<!--      text-align: center;-->
<!--      overflow: hidden;-->
<!--    }-->

<!--    .message {-->
<!--      font-size: 1.6rem;-->
<!--      font-weight: 600;-->
<!--      color: #d63384;-->
<!--      text-shadow: 0 0 10px rgba(255, 102, 178, 0.7);-->
<!--      margin-bottom: 10px;-->
<!--      padding: 0 20px;-->
<!--      animation: floatMsg 4s ease-in-out infinite;-->
<!--    }-->

<!--    @keyframes floatMsg {-->
<!--      0%, 100% { transform: translateY(0); }-->
<!--      50% { transform: translateY(-6px); }-->
<!--    }-->

<!--    .carousel-container {-->
<!--      width: 100%;-->
<!--      max-width: 100%;-->
<!--      height: auto;-->
<!--      perspective: 1200px;-->
<!--      display: flex;-->
<!--      align-items: center;-->
<!--      justify-content: center;-->
<!--    }-->

<!--    .carousel {-->
<!--      width: 90vw;-->
<!--      max-width: 400px;-->
<!--      height: 300px;-->
<!--      position: relative;-->
<!--      transform-style: preserve-3d;-->
<!--      animation: rotate 20s linear infinite;-->
<!--    }-->

<!--    .carousel img {-->
<!--      width: 130px;-->
<!--      height: 130px;-->
<!--      position: absolute;-->
<!--      top: 50%;-->
<!--      left: 50%;-->
<!--      transform: translate(-50%, -50%);-->
<!--      border-radius: 20px;-->
<!--      box-shadow: 0 6px 12px rgba(0,0,0,0.2);-->
<!--      object-fit: cover;-->
<!--    }-->

<!--    @keyframes rotate {-->
<!--      from { transform: rotateY(0deg); }-->
<!--      to { transform: rotateY(360deg); }-->
<!--    }-->

<!--    .carousel img:nth-child(1)  { transform: rotateY(0deg)   translateZ(200px); }-->
<!--    .carousel img:nth-child(2)  { transform: rotateY(60deg)  translateZ(200px); }-->
<!--    .carousel img:nth-child(3)  { transform: rotateY(120deg) translateZ(200px); }-->
<!--    .carousel img:nth-child(4)  { transform: rotateY(180deg) translateZ(200px); }-->
<!--    .carousel img:nth-child(5)  { transform: rotateY(240deg) translateZ(200px); }-->
<!--    .carousel img:nth-child(6)  { transform: rotateY(300deg) translateZ(200px); }-->

<!--    .floating-balloon {-->
<!--      position: absolute;-->
<!--      width: 40px;-->
<!--      animation: floatUp 10s linear infinite;-->
<!--    }-->

<!--    @keyframes floatUp {-->
<!--      0% { transform: translateY(100vh); opacity: 1; }-->
<!--      100% { transform: translateY(-150vh); opacity: 0; }-->
<!--    }-->

<!--    @media (min-width: 768px) {-->
<!--      .carousel img {-->
<!--        width: 200px;-->
<!--        height: 200px;-->
<!--      }-->
<!--      .carousel img:nth-child(n) {-->
<!--        transform: translate(-50%, -50%) rotateY(calc(var(--i) * 60deg)) translateZ(250px);-->
<!--      }-->
<!--    }-->
<!--  </style>-->
<!--</head>-->
<!--<body>-->

<!--  <div class="message">You are the light that makes everything beautiful ✨❤️</div>-->

<!--  <div class="carousel-container">-->
<!--    <div class="carousel">-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.10 AM.jpeg" alt="Cat" />-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.13 AM.jpeg" alt="Rose" />-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.17 AM.jpeg" alt="Balloon" />-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.19 AM.jpeg" alt="Heart" />-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.13 AM.jpeg" alt="Star" />-->
<!--      <img src="https://pathanto.com/Pathanto/image/WhatsApp Image 2025-07-17 at 12.19.10 AM.jpeg" alt="Letter" />-->
<!--    </div>-->
<!--  </div>-->

<!--  <script>-->
<!--    function spawnBalloon() {-->
<!--      const balloon = document.createElement('img');-->
<!--      balloon.src = 'https://img.icons8.com/color/96/heart-balloon.png';-->
<!--      balloon.className = 'floating-balloon';-->
<!--      balloon.style.left = Math.random() * 100 + 'vw';-->
<!--      balloon.style.animationDuration = 10 + Math.random() * 4 + 's';-->
<!--      document.body.appendChild(balloon);-->
<!--      setTimeout(() => balloon.remove(), 10000);-->
<!--    }-->
<!--    setInterval(spawnBalloon, 300);-->
<!--  </script>-->

<!--</body>-->
<!--</html>-->