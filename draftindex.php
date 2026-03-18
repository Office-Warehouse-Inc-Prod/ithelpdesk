<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OWI Systems Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');

    :root {
      --green-primary: #204a20;
      --blue-primary: #2062ac;
      --bg-dark: #0f172a; 
      --glass-border: rgba(255, 255, 255, 0.1);
      --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      min-height: 100vh;
      background: linear-gradient( #92afd1a1, #a2bea2b4), 
                        url('images/bg6.png'); 
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      overflow-x: hidden;
      color: white;
    }

    .main-header {
      position: fixed;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      padding: 10px 25px;
      box-shadow: 0 2px 2px 2px #2062ac;
      background: linear-gradient(to bottom, #77744c, #9d9f66);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      z-index: 1000;
      border: 1px solid var(--glass-border);
      border-radius: 100px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 15px;
      text-decoration: none;
    }

    .brand img {
      height: 35px;
    }

    .brand span {
      color: white;
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .selection {
      display: flex;
      width: 100%;
      height: 100vh;
      align-items: center;
      justify-content: center;
      gap: 40px;
      padding: 100px 20px 20px;
    }

    .card-section {
      position: relative;
      flex: 0 1 450px;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      perspective: 1000px;
    }

    .template {
      position: relative;
      width: 100%;
      height: 100%;
      padding: 60px 40px;
      border-radius: 32px;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: var(--transition);
      border: 1px solid rgba(255, 255, 255, 0.1);
      overflow: hidden;
    }

    .left-card .template {
      box-shadow: 0 2px 2px 2px #419c41;
      background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #204a20);
    }
    .left-card .template::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at top right, rgba(46, 204, 113, 0.15), transparent);
    }
    .right-card .template {
      box-shadow: 0 2px 2px 2px #2062ac;
      background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #2062ac);
    }
    .right-card .template::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at top right, rgba(52, 152, 219, 0.15), transparent);
    }

    .card-section:hover .template {
      transform: translateY(-10px) scale(1.16);
      border-color: rgba(255, 255, 255, 0.2);
    }

    .left-card:hover .template { 
      box-shadow: 0 20px 50px rgba(46, 204, 113, 0.2); 
      border: 3px solid green; 
    }
    .right-card:hover .template { 
      box-shadow: 0 20px 50px rgba(52, 152, 219, 0.2);
      border: 3px solid #2062ac; 
    }

    .in { 
     position: relative;
     z-index: 2; 
    }

    .in h1 {
      color: white;
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.1;
      letter-spacing: -1px;
      text-shadow: 0 2px 8px  #828282;
    }

    .divider {
      width: 50px;
      height: 4px;
      border-radius: 2px;
      margin: 0 auto 40px;
    }

    .left-card .divider { 
      background: #355a35; 
    }
    .right-card .divider { 
      background: var(--blue-primary); 
    }
    .template .divider:hover { 
      width: 100%;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      padding: 16px 35px;
      background: rgba(255, 255, 255, 0.05);
      color: white;
      text-decoration: none;
      border-radius: 100px;
      font-weight: 600;
      font-size: 15px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: var(--transition);
    }

    .btn i { 
      margin-right: 12px; 
    }

    .left-card .btn:hover { 
      background: var(--green-primary); 
      border-color: var(--green-primary); 
      transform: scale(1.05); 
      border: 2px solid green; 
    }
    .right-card .btn:hover { 
      background: var(--blue-primary); 
      border-color: var(--blue-primary); 
      transform: scale(1.05); 
      border: 2px solid #083962;  
    }

    .bg-image {
      position: absolute;
      bottom: -20px;
      right: -20px;
      width: 190px;
      opacity: 0.2;
      pointer-events: none;
      transition: var(--transition);
    }

    .card-section:hover .bg-image {
      opacity: 0.2;
      transform: scale(1.5) rotate(-5deg);
    }

    .template img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      top: 0;
      left: 0;
      transition: 0.5s ease;
    }
    @media (max-width: 900px) {
      .selection { flex-direction: column; height: auto; padding-top: 120px; gap: 20px; }
      .card-section { width: 100%; height: 400px; flex: none; }
      .in h1 { font-size: 1.8rem; }
      .main-header{
        text-align: center;
        align-items: center;
        width: 100%;
      }
          .template {
     
      width: 100%;
      height: 80%;
     
    }


    
:root {
  --dark-bg: #83cd6a;
  --light-bg: #627bc5;
  --accent: #E1AD01;
  --text-dark: #333;
  --text-light: #fff;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body, html {
  height: 100%;
  width: 100%;
  font-family: 'Public Sans', 'Segoe UI', sans-serif;
  overflow-x: hidden;
  background: white;
  
}

/*left*/

 .left-card .template{
     background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #204a20), url('images/dts3.png'); 
       background-size: cover;
  
    width:100%;
    background-position: center;
}


.left-card, .right-card {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px 20px;
}


.left-card{
 
  box-shadow: 0 20px 20px 30px #419c41;
  background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #204a20);
}


.left-card:hover{
border: solid 5px #419c41;
}

.left-card .in h1 {
  color: #fff;
  font-size: 2.1rem;
  margin-bottom: 15px;
  text-transform: uppercase;
  align-items: center;
  text-align: left;
  margin:40px;
}
.left-card .template:hover{
border: solid 5px #419c41;
  box-shadow: 0 15px 15px rgba(127, 126, 126, 0.616);
}

.left-card .btn:hover{
   background: #419c41;
  color: #fff;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.left-card:hover{
border: solid 5px #419c41;
  box-shadow: 0 45px 15px rgba(127, 126, 126, 0.616);
}


/*right*/


 .right-card .template{
     background: linear-gradient(to bottom,rgba(220, 215, 198, 0.493), #5875cc), url('images/helpdesk3.png'); 
    background-size: cover;
  
    width:100%;
    background-position: center;
}

.right-card{
  box-shadow: 0 5px 15px rgba(236, 237, 239, 0.3);
}

.selection .right-card{

  background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #5875cc);
}
.right-card{
 
    background: linear-gradient(to bottom,rgba(220, 215, 198, 0.6), #204a20);
}

.right-card .template:hover{
border: solid 5px #627bc5;
  box-shadow: 0 15px 15px rgba(127, 126, 126, 0.616);
}
.right-card:hover{
border: solid 5px #627bc5;
  box-shadow: 0 45px 15px rgba(127, 126, 126, 0.616);
}
.right-card .in h1 {
  color: #fff;
  font-size: 2.8rem;
  margin-bottom: 15px;
  text-transform: uppercase;
  text-align: left;
  text-shadow: 2px 2px 5px #627bc5;
  margin:40px;

}
.btn {
  display: inline-block;
    margin: 30px;
  align-items: center;
  text-decoration: none;
  padding: 12px 25px;
  background: #fff;
  color: #000;
  border-radius: 30px;
  font-weight: bold;
  font-size: 14px;
  transition: 0.4s ease-in-out;
  border: 2px solid transparent;
}

.btn:hover {
  background: #627bc5;
  color: #fff;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  border: solid 5px #fff;
}


.right-card .btn:hover {
  background: #627bc5;
  color: #fff;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}






.selection {
  display: flex;
  flex-direction: row;
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(to right, var(--dark-bg) , 50%, var(--light-bg) 50%);
}

.template {
  position: relative;
  width: 100%;
  max-width: 400px;
  height: 320px;
  background: #ffffff2f;
  border-radius: 15px;
  overflow: hidden; 
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transition: 0.5s ease-in-out;
  box-shadow: 0 10px 30px rgba(139, 136, 136, 0.3);
}

.in{
  color:white;
}



.template img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  transition: 0.5s ease;
}



.info h1 {
  position: relative;
  z-index: 3;
  text-align: center;
  padding: 20px;
  opacity: 0;
  transform: translateY(30px);
  transition: 0.5s all;
}

.template:hover .info {
  opacity: 1;
  transform: translateY(0);
}



@media (max-width: 992px) {
  .template {
    max-width: 350px;
    height: 280px;
  }
}

@media (max-width: 768px) {
  .selection {
    flex-direction: column; 
    background: linear-gradient(to bottom, var(--dark-bg) 50%, var(--light-bg) 50%);
  }

  .left-card, .right-card {
    min-height: 50vh;
    padding: 20px;
  }

  .template {
    max-width: 90%;
    height: 250px;
  }

  .info {
    opacity: 1; 
    transform: translateY(0);
  }
  
  .template:before {
    opacity: 0.7; 
  }
  .right-card .in h1 {
  color: #fff;
  font-size: 2.0rem;
  margin-bottom: 15px;
  text-transform: uppercase;
  text-shadow: 2px 2px 5px #627bc5;
  margin:40px;

}
.left-card .in h1 {
  color: #fff;
  font-size: 2.0rem;
  margin-bottom: 15px;
  text-transform: uppercase;
  text-shadow: 2px 2px 5px #627bc5;
  margin:40px;

}
}



.center-card {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 10;
  pointer-events: none; 
}

.logo-wrapper img {
  width: 400px; 
  height: auto;
  filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
  pointer-events: auto; 
}

@media (max-width: 768px) {
  .selection {
    flex-direction: column;
  }
  
  .center-card {
    top: 50%;
    left: 50%;
  }

  .logo-wrapper img {
    width: 300px; 
  }
  
}






    }
  </style>
</head>
<body>

<header class="main-header">
  <a href="#" class="brand">
    <img src="images/owi.jpg" alt="OWI"> 
    <span>Office Warehouse Services Portal</span>
  </a>
</header>

<div class="selection">
  <div class="card-section left-card">
    <div class="template">
      <img src="images/dts3.png" alt="" class="bg-image">
      <div class="in">
        <h1>DOCUMENT<br>TRACKING</h1>
        <div class="divider"></div>
        <a href="#" class="btn" disabled>
          <!-- <i class="fas fa-arrow-right"></i> Soon! -->
          <i class="" aria-disabled=""></i> Launching soon!
        </a>
      </div>
    </div>  
  </div>

  <div class="card-section right-card">
    <div class="template">
      <img src="images/helpdesk3.png" alt="" class="bg-image">
      <div class="in">
        <h1>OWI<br>HELPDESK</h1>
        <div class="divider"></div>
        <a href="owihelpdesk_login.php" class="btn">
          <i class="fas fa-headset"></i> Get Support
        </a>
      </div>
    </div>
  </div>
</div>

</body>
</html>