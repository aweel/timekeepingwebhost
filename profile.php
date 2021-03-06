<?php include 'layout/header.php';?>

<style type="text/css">
    $black: #071011;
    $white: #fff;
    $red: tomato;
    $standard: 10px;
    $card-height: 450px;
    $card-width: 300px;
  
    .background {
      position: absolute;
      top: 0;
      left: 0;
      height: 100vh;
      width: 50vw;
    
      background: url("https://images.unsplash.com/photo-1447433589675-4aaa569f3e05?ixlib=rb-0.3.5&s=4222852e25e0f57d9485f7889957e99a&auto=format&fit=crop&w=2000&q=80");
      background-size: cover;
      background: #ccc;
      background-position: 0 50%;
      background: #DEB493;
      font-family: "Quicksand";
  
    &:after {
       content: "";
       position: absolute;
       top: 0;
       left: 0;
       height: 100%;
       width: 100%;
       background: rgba(0, 0, 0, 0);
     }
    }
  
    .outer-div,
    .inner-div {
      height: $card-height;
      max-width: $card-width;
      margin: 0 auto;
      position: relative;
      text-align: center;
    }
  
    .outer-div {
      perspective: 900px;
      perspective-origin: 50% calc(50% - 18em);
    }
  
    .inner-div {
      margin: $standard * 5 auto;
    
      border-radius: 5px;
      font-weight: 400;
      color: $black;
      font-size: 1rem;
      text-align: center;
      transition: all 0.6s cubic-bezier(0.8, -0.4, 0.2, 1.7);
      transform-style: preserve-3d;
  
    &:hover {
       transform: rotateY(180deg);
       background-color: transparent;
     }
  
    &:hover .social-icon {
       opacity: 1;
       top: 0;
     }
  
    &:hover .front__face-photo,
    &:hover .front__footer {
       opacity: 0;
     }
    }
  
    .front,
    .back {
      position: relative;
      top: 0;
      left: 0;
      backface-visibility: hidden;
    }
  
    .front {
      cursor: pointer;
      height: 100%;
      background: $white;
      backface-visibility: hidden;
      border-radius: 5px;
      box-shadow: 0 15px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3),
      0 0 40px rgba(0, 0, 0, 0.1) inset;
    }
  
    .front__bkg-photo {
      position: relative;
      height: 150px;
      width: $card-width;
      background: url("https://images.unsplash.com/photo-1511207538754-e8555f2bc187?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=88672068827eaeeab540f584b883cc66&auto=format&fit=crop&w=1164&q=80")
      no-repeat;
      background-size: cover;
      backface-visibility: hidden;
      overflow: hidden;
      border-top-right-radius: 5px;
      border-top-left-radius: 5px;
  
    &:after {
       content: "";
       position: absolute;
       top: 0;
       left: 0;
       height: 100%;
       width: 100%;
     }
    }
  
    .front__face-photo {
      position: relative;
      top: -60px;
      height: 120px;
      width: 120px;
      margin: 0 auto;
      border-radius: 50%;
      border: 5px solid $white;
      background: url("https://www.gravatar.com/avatar/00000000000000000000000000000000?d=retro&f=y")
      no-repeat;
      background-size: contain;
      backface-visibility: hidden;
      overflow: hidden;
      transition: all 0.6s cubic-bezier(0.8, -0.4, 0.2, 1.7);
      z-index: 3;
    }
  
    .front__text {
      position: relative;
      top: -55px;
      margin: 0 auto;
      font-family: "Quicksand"; /*font moserrat*/
      font-size: 18px;
      backface-visibility: hidden;
  
    .front__text-header {
      font-weight: 700;
      font-family: "Quicksand"; /*font oswald*/
      text-transform: uppercase;
      font-size: 20px;
    }
  
    .front__text-para {
      position: relative;
      top: -5px;
    
      color: #000;
      font-size: 14px;
      letter-spacing: 0.4px;
      font-weight: 400;
      font-family: "Montserrat", sans-serif;
    }
  
    .front-icons {
      position: relative;
      top: 0;
      font-size: 14px;
      margin-right: 6px;
      color: gray;
    }
  
    .front__text-hover {
      position: relative;
      top: 10px;
      font-size: 10px;
      color: $red;
      backface-visibility: hidden;
    
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .4px;
    
      border: 2px solid $red;
      padding: 8px 15px;
      border-radius: 30px;
    
      background: $red;
      color: $white;
    }
    }
  
    .back {
      transform: rotateY(180deg);
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: $black;
      border-radius: 5px;
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
    }
  
    .social-media-wrapper {
      font-size: 36px;
  
    .social-icon {
      position: relative;
      top: 20px;
      margin-left: 5px;
      margin-right: 5px;
      opacity: 0;
      color: #fff;
      transition: all 0.4s cubic-bezier(0.3, 0.7, 0.1, 1.9);
    }
  
    .social-icon:nth-child(1) {
      transition-delay: 0.6s;
    }
  
    .social-icon:nth-child(2) {
      transition-delay: 0.7s;
    }
  
    .social-icon:nth-child(3) {
      transition-delay: 0.8s;
    }
  
    .social-icon:nth-child(4) {
      transition-delay: 0.9s;
    }
    }
  
    .fab {
      position: relative;
      top: 0;
      left: 0;
      transition: all 200ms ease-in-out;
    }
  
    .fab:hover {
      top: -5px;
    }

  </style>

<main id="wrapper" style="padding-left: 350px; padding-right: 350px">
<div class="background"></div>

<div class="outer-div">
  <div class="inner-div">
    <div class="front">
      <div class="front__bkg-photo"></div>
      <div class="front__face-photo"></div>
      <div class="front__text">
        <h3 class="front__text-header"><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"] ?></h3>
        <p class="front__text-para"><i class="fas fa-map-marker-alt front-icons"></i><?php echo $_SESSION["position"] ?></p>
        
        <span class="front__text-hover">Last log in/out: </span>
      </div>
    </div>
    <div class="back">
      <div class="social-media-wrapper">
        <a href="#" class="social-icon"><i class="fab fa-codepen" aria-hidden="true"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-github-square" aria-hidden="true"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-linkedin-square" aria-hidden="true"></i></a>
         <a href="#" class="social-icon"><i class="fab fa-instagram" aria-hidden="true"></i></a>
      </div>
    </div>

  </div>
</div>
</main>
<?php include 'layout/footer.php';?>