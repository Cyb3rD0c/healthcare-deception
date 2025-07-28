<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="author" content="Mayuri K">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">

     <link rel="icon" href="../img/favicon.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables,.doctor-heade{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,#anim{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .doctor-heade{
            animation: transitionIn-Y-over 0.5s;
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
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];


    //echo $userid;
    //echo $username;
    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
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
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text"><i class="material-symbols-outlined"> dashboard</i>
                        Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div><p class="menu-text">
                     <i class="material-symbols-outlined">description</i>Appointments</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu">
                            <div>
                            <p class="menu-text"><i class="material-symbols-outlined">list_alt</i>Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text"><i class="material-symbols-outlined">face</i>Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text"><i class="material-symbols-outlined">settings</i>Settings</p></a></div>
                    </td>
                </tr>

                 <tr  class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="../logout.php" class="non-style-link-menu">
                            <div>
                      <p class="menu-text"><i class="material-symbols-outlined">power_settings_new</i>Logout</p></a></div>
                    </td> 
               </tr>

                
                      </a>
                
            </table>
        </div>

        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                        <tr >
                             <td >
                     <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">     Dashboard</p>
                          
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
                          <span style=""><?php echo substr($useremail,0,22)  ?></span><br>
                         <span style="">
                        <?php echo substr($useremail,0,22)  ?>
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
                            
               
                           <!--  <td width="25%">

                            </td> -->
                            <!-- <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php 
                                date_default_timezone_set('Asia/Kolkata');
        
                                $today = date('Y-m-d');
                                echo $today;


                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");


                                ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                            </td>
        
         -->
                        </tr>
<tr>
    <td colspan="4" >
        <div  class="card" >
<div class="body">

<div  class="cards" style="float:left;">
<img  src="../img/card.png" alt="" >
</div>

                  <div  class="head">
     <h4  class="font-20 weight-500 mb-2">
       Welcome back 

 <div class="colage">
 Sarah Smith!
</div>
</h4>
<p >We would like to take this opportunity to welcome you to our practice and to thank you for choosing our physicians to participate in your healthcare. We look forward to providing you with personalized, comprehensive health care focusing on wellness and prevention.
</p>
</div>
</div>
</div>
                        
            <center>
            <table class="filter-container doctor-header" style="border: none;width:95%" border="0" >
            <tr>
                <td >
            <h3>Welcome!</h3>
            <h1><?php echo $username  ?>.</h1>
            <p>Thanks for joinnig with us. We are always trying to get you a complete service<br>
            You can view your dailly schedule, Reach Patients Appointment at home!<br><br>
            </p>
            <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="width:30%">View My Appointments</button></a>
            <br>
            <br>
        </td>
    </tr>
    </table>
    </center>
                    
    </td>
    </tr>
    <tr>
        <td colspan="4">
            <table border="0" width="100%">
<tr>
<td width="50%">

        <center>
            <table class="filter-container" style="border: none;" border="0">
                <tr>
                    <td colspan="4">
                        <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Status</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        <div  class="dashboard-items bg-danger"  style="padding:20px;margin:auto;width:95%;display: flex">
                            <div>
                                 <div class="h3-dashboard">
                                        All Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div><br>
                                    <div class="h1-dashboard">
                                        <?php    echo $doctorrow->num_rows  ?>
                                    </div>
                                   
                            </div>
                                    <div class=" dashboard-icons" >
                             <i class="fa fa-user-md"></i> 
                                </div>
                        </div>
                    </td>

                    <td style="width: 25%;">
                        <div  class="dashboard-items bg-success"  style="padding:20px;margin:auto;width:95%;display: flex;">
                            <div>
                                <div class="h3-dashboard">
                                        All Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                    </tr>
                    <tr>
                    <td style="width: 25%;">
                        <div  class="dashboard-items bg-info"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                            <div>
                                <div class="h3-dashboard" >
                                        NewBooking &nbsp;&nbsp;
                                    </div><br>
                                    <div class="h1-dashboard" >
                                        <?php    echo $appointmentrow ->num_rows  ?>
                                    </div>
                                    
                            </div>
                               <div class="dashboard-icons">
                                          <i class="fa fa-calendar-alt"></i>
                            </div>
                        </div>
                        
                    </td>

                    <td style="width: 25%;">
                        <div  class="dashboard-items bg-secondary"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                            <div>
                                <div class="h3-dashboard" style="font-size: 15px">
                                        Today Sessions
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
                                <td>

                                    <p id="anim" style="font-size: 20px;font-weight:600;padding-left: 40px;">Your Up Coming Sessions until Next week</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
                                        <thead>
                                            
                                        <tr>
                                                <th class="table-headin">
                                                    
                                                
                                                Session Title
                                                
                                                </th>
                                                
                                                <th class="table-headin">
                                                Sheduled Date
                                                </th>
                                                <th class="table-headin">
                                                    
                                                     Time
                                                    
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
                                                        <td style="padding:20px;font-size:13px;">
                                                        '.substr($scheduledate,0,10).'
                                                        </td>
                                                        <td style="text-align:center;">
                                                            '.substr($scheduletime,0,5).'
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
                        </table>
                    </td>
                <tr>
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
</body>
</html>