<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="author" content="Mayuri K">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  

   <link rel="icon" href="../img/favicon.png">
      
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
    
    
</head>
<body>

     <!-- Page Preloder -->
    <div id="page"></div>
    <div id="loading"></div>
    
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container">

        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
               <td>
                  <table border="0" class="profile-container">
                     <tr>
                        <td>
                           <img src="../img/logo.jpg" alt="">
                        </td>
                       
                     </tr>
                     
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord menu-active menu-icon-dashbord-active" >

                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div>
                           <p class="menu-text">
                            <i class="material-symbols-outlined"> dashboard</i>
                        Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu ">
                            <div><p class="menu-text">
                                <i class="material-symbols-outlined">supervised_user_circle</i>Doctors</p>
                            </a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu">
               <div>
                <p class="menu-text"><i class="material-symbols-outlined">calendar_month</i>Schedule</p>
            </div></a>
                    </td>
                </tr>

                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                 <div>
                <p class="menu-text"><i class="material-symbols-outlined">description</i>Appointment</p>
                </a>
            </div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu">
                            <div>
                           <p class="menu-text"><i class="material-symbols-outlined">face</i>Patients</p></a>
                        </div>
                    </td>
                </tr>

                 <tr  class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="https://mayurik.com/source-code/P5890/best-hospital-management-system-in-php" class="non-style-link-menu" target="_blank">
                            <div>
                      <p class="menu-text"><i class="material-symbols-outlined"><span class="material-symbols-outlined">
cloud_upload
</span></i>Advance Version</p></a></div>
                    </td> 
               </tr>

                <tr  class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="https://www.youtube.com/c/MayuriK/videos" class="non-style-link-menu" target="_blank">
                            <div>
                      <p class="menu-text"><i class="material-symbols-outlined"><span class="material-symbols-outlined">
youtube_activity
</span></i>Other Projects</p></a></div>
                    </td> 
               </tr>
               
                <tr  class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="../logout.php" class="non-style-link-menu">
                            <div>
                      <p class="menu-text"><i class="material-symbols-outlined">power_settings_new</i>Logout</p></a></div>
                    </td> 
               </tr>

            </table>
        </div>
        <div class="dash-body" >
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
        <tr >
             <td>
          <marquee behavior="scroll" direction="left" scrollamount="4"><p style="color: red;">
                This Project is developed for Academic study purpose only. | Never Sell or Distribute with your Name OR Branding</p>
            </marquee> 
                                </td>

        <td colspan="2" class="nav" style="float: right;">

            <div class="header-content" >
                <nav class="navbar navbar-expand">
          <div class="collapse navbar-collapse justify-content-between">
            <div class="header-left">
                        <div class="dashboard_bar" style="text-transform: capitalize;"></div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li>
                        <i class="fa-regular fa-bell"></i>
                    </li>
                    <li> <i class="fa-regular fa-message"></i></li>
                    <li>
                      <i class="fa-solid fa-gift"></i>    
                    </li>
                       
                  
                   <li>
                    <div class="profile dropdown">
                       <img src="../img/profile.png" width="45px">

                          <div class="toggel">
                          <span style="">Mayuri K.</span><br>
                         <span style="">
                        mayuri.infospace@gmail.com
                         </span>
                      </div>

                      <div class="dropdown-content">

                      <a href="" class="drop dropdown-item">
                        <i class="fa fa-user"></i>Profile</a>
                       
                      <a href="" class="drop dropdown-item">
                       <i class="fa fa-envelope"></i>Inbox</a>

                      <a href="../logout.php" class="drop dropdown-item logout">
                        <i class="fa fa-sign-out-alt"></i>
                      Logout</a>
                      </div>
                    </div>
                        
                   </li>
                  
                    </ul>
                </div>
            </nav>
        </div>
            
           <!--  <form action="doctors.php" method="post" class="header-search">

                <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">&nbsp;&nbsp;
                
                <?php
                    echo '<datalist id="doctors">';
                    $list11 = $database->query("select  docname,docemail from  doctor;");

                    for ($y=0;$y<$list11->num_rows;$y++){
                        $row00=$list11->fetch_assoc();
                        $d=$row00["docname"];
                        $c=$row00["docemail"];
                        echo "<option value='$d'><br/>";
                        echo "<option value='$c'><br/>";
                    };

                echo ' </datalist>';
                ?>
                                    
                               
                                    <input type="Submit" value="Search" class="login-btn btn-primary-soft btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                
                                </form> -->
                                
                            </td>
                           
                                   <!--  <?php 
                                date_default_timezone_set('Asia/Kolkata');
        
                                $today = date('Y-m-d');
                                echo $today;


                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");


                                ?> -->
                                
                           
        
                        </tr>
             
<td colspan="4">

<center>
<table class="filter-container" style="border: none;" border="0">

                           
<div  class="card" >
    <div class="body">
        
            <div  class="cards" style="float:left;">
                <img  src="../img/card.png" alt="" >
            </div>

                  <div  class="head">
     <h4  class="font-20 weight-500 mb-2">
       Welcome back 

 <div class="colage">
 Mayuri K.
</div>
</h4>
<p >Mayuri K is the freelancer working on website designing and web based application development. She provide service in India and worldwide. she also provide Source code for Academic projects.
</p>
<mark>
<b>If you have any requirement related to Project Development :<a href="https://mayurik.com">Contact here</a></b>
</mark>
</div>
</div>
</div>


            <tr>
                <td style="width: 25%;">
                    <div  class="dashboard-items bg-danger"  style="padding:20px;margin:auto;width:95%;display: flex">
                        <div>
                              
                                <div class="h3-dashboard">
                                    Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>  <br>
                                <div class="h1-dashboard">
                                    <?php    echo $doctorrow->num_rows  ?>
                                </div>
                        </div>
      <div class=" dashboard-icons" >
             <i class="fa fa-user-md"></i> 
                </div>
                </td>

                <td style="width: 25%;">
                    <div  class="dashboard-items bg-success"  style="padding:20px;margin:auto;width:95%;display: flex;">
                        <div>
                            <div class="h3-dashboard">
                                    Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div><br>
                                <div class="h1-dashboard">
                                    <?php    echo $patientrow->num_rows  ?>
                                </div>
                                
                        </div>
                                <div class="dashboard-icons">
                                    <i class="fa fa-wheelchair"></i>
                                </div>
                    </div>
                </td>

                 <td style="width: 25%;">
                    <div  class="dashboard-items bg-info"  style="padding:20px;margin:auto;width:95%;display: flex;">
                        <div>
                             <div class="h3-dashboard">
                                   Booking &nbsp;&nbsp;
                                </div><br>
                                <div class="h1-dashboard">
                                   <?php    echo $appointmentrow ->num_rows  ?>
                                </div>
                               
                        </div>
                      <div class="dashboard-icons">
                          <i class="fa fa-calendar-alt"></i>
                      </div>
                    </div>
                </td>


               
                <td style="width: 25%;">
                    <div  class="dashboard-items bg-secondary "  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:26px;padding-bottom:26px;">
                        <div>
                                
                                <div class="h3-dashboard">
                                    TodaySessions
                                </div><br>
                                <div class="h1-dashboard">
                                    <?php    echo $schedulerow ->num_rows  ?>
                                </div>
                        </div>
                                <div class="dashboard-icons">
                                    <i class="fa fa-heartbeat"></i>
                                </div>
                    </div>
                </td>
                
            </tr>
        </table>
    </center>
    </td>


<tr>
    <td colspan="4">
        <table width="100%" border="0" class="dashbord-tables">
            <tr>
                <td>
                    <p style="padding:10px;padding-left:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                        Upcoming Appointments until Next <?php  
                        echo date("l",strtotime("+1 week"));
                        ?>
                    </p>
                    <p style="padding-bottom:19px;padding-left:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                        Here's Quick access to Upcoming Appointments until 7 days<br>
                        More details available in @Appointment section.
                    </p>

                </td>
                <td>
                    <p style="text-align:right;padding:10px;padding-right:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                        Upcoming Sessions  until Next <?php  
                        echo date("l",strtotime("+1 week"));
                        ?>
                    </p>
                    <p style="padding-bottom:19px;text-align:right;padding-right:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                        Here's Quick access to Upcoming Sessions that Scheduled until 7 days<br>
                        Add,Remove and Many features available in @Schedule section.
                    </p>
                </td>
            </tr>
    <tr>
        <td width="50%">
            <center>
                <div class="abc scroll" style="height: 200px;">
                <table width="85%" class="sub-table scrolldown" border="0">
                <thead>
                <tr>    
                        <th class="table-headin" style="font-size: 12px;">
                                
                            Appointment number
                            
                        </th>
                        <th class="table-headin">
                            Patient name
                        </th>
                        <th class="table-headin">
                            
                        
                            Doctor
                            
                        </th>
                        <th class="table-headin">
                            
                        
                            Session
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                                        
                <?php
                $nextweek=date("Y-m-d",strtotime("+1 week"));
                $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where schedule.scheduledate>='$today'  and schedule.scheduledate<='$nextweek' order by schedule.scheduledate desc";

                    $result= $database->query($sqlmain);

                    if($result->num_rows==0){
                        echo '<tr>
                        <td colspan="3">
                        <br><br><br><br>
                        <center>
                        <img src="../img/notfound.png" width="25%">
                        
                        <br>
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                        <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</font></button>
                        </a>
                        </center>
                        <br><br><br><br>
                        </td>
                        </tr>';
                        
                    }
                    else{
                    for ( $x=0; $x<$result->num_rows;$x++){
                        $row=$result->fetch_assoc();
                        $appoid=$row["appoid"];
                        $scheduleid=$row["scheduleid"];
                        $title=$row["title"];
                        $docname=$row["docname"];
                        $scheduledate=$row["scheduledate"];
                        $scheduletime=$row["scheduletime"];
                        $pname=$row["pname"];
                        $apponum=$row["apponum"];
                        $appodate=$row["appodate"];
                        echo '<tr>


                            <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);padding:20px;">
                                '.$apponum.'
                                
                            </td>

                            <td style="font-weight:600;"> &nbsp;'.
                            
                            substr($pname,0,25)
                            .'</td >
                            <td style="font-weight:600;"> &nbsp;'.
                            
                                substr($docname,0,25)
                                .'</td >
                               
                            
                            <td>
                            '.substr($title,0,15).'
                            </td>

                        </tr>';
                        
                    }
                }
                     
                ?>

                </tbody>

            </table>
            </div>
            </center>
    </td>

    <td width="50%" style="padding: 0;">
        <center>
            <div class="abc scroll" style="height: 200px;padding: 0;margin: 0;">
            <table width="85%" class="sub-table scrolldown" border="0" >
            <thead>
            <tr>
                    <th class="table-headin">
                    
                    Session Title
                    
                    </th>
                    
                    <th class="table-headin">
                        Doctor
                    </th>
                    <th class="table-headin">
                        
                        Sheduled Date & Time
                        
                    </th>
                        
                    </tr>
            </thead>
            <tbody>
                                        
                        <?php
                        $nextweek=date("Y-m-d",strtotime("+1 week"));
                        $sqlmain= "select schedule.scheduleid,schedule.title,doctor.docname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join doctor on schedule.docid=doctor.docid  where schedule.scheduledate>='$today' and schedule.scheduledate<='$nextweek' order by schedule.scheduledate desc"; 
                            $result= $database->query($sqlmain);

                            if($result->num_rows==0){
                                echo '<tr>
                                <td colspan="4">
                                <br><br><br><br>
                                <center>
                                <img src="../img/notfound.png" width="25%">
                                
                                <br>
                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Sessions &nbsp;</font></button>
                                </a>
                                </center>
                                <br><br><br><br>
                                </td>
                                </tr>';
                                
                            }
                            else{
                            for ( $x=0; $x<$result->num_rows;$x++){
                                $row=$result->fetch_assoc();
                                $scheduleid=$row["scheduleid"];
                                $title=$row["title"];
                                $docname=$row["docname"];
                                $scheduledate=$row["scheduledate"];
                                $scheduletime=$row["scheduletime"];
                                $nop=$row["nop"];
                                echo '<tr>
                                    <td style="padding:20px;"> &nbsp;'.
                                    substr($title,0,30)
                                    .'</td>
                                    <td>
                                    '.substr($docname,0,20).'
                                    </td>
                                    <td style="text-align:center;">
                                        '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
                                    </td>


                                   
                                </tr>';
                                
                            }
                        }
                             
                        ?>

                        </tbody>

                    </table>
                    </div>
                    </center>
            </td>
        </tr>
                            <tr>
                                <td>
                                    <center>
                                        <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Appointments</button></a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Sessions</button></a>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                        </table>
                        </center>
                        </td>
                </tr>
            </table>
        </div>
    </div>
     <script>
          // preloader
    
    function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);
    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});

      </script>
<!--  Orginal Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  
<!--  Orginal Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  
</body>
</html>