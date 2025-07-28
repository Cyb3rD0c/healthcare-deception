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
         .dashbord-tables{
         animation: transitionIn-Y-over 0.5s;
         }
         .filter-container{
         animation: transitionIn-Y-bottom  0.5s;
         }
         .sub-table,.anime{
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
             if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
                 header("location: ../login.php");
             }else{
                 $useremail=$_SESSION["user"];
             }
         
         }else{
             header("location: ../login.php");
         }
         
         
         //import database
         include("../connection.php");
         
         $sqlmain= "select * from patient where pemail=?";
         $stmt = $database->prepare($sqlmain);
         $stmt->bind_param("s",$useremail);
         $stmt->execute();
         $userrow = $stmt->get_result();
         $userfetch=$userrow->fetch_assoc();
         
         $userid= $userfetch["pid"];
         $username=$userfetch["pname"];
         
         
         //echo $userid;
         //echo $username;
         
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
               <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                  <a href="index.php" class="non-style-link-menu non-style-link-menu-active">
                     <div>
                        <p class="menu-text"><i class="material-symbols-outlined">home</i>Home</p>
                  </a>
                  </div></a>
               </td>
            </tr>
            <tr class="menu-row">
               <td class="menu-btn menu-icon-doctor">
                  <a href="doctors.php" class="non-style-link-menu">
                     <div>
                        <p class="menu-text"> <i class="material-symbols-outlined">supervised_user_circle</i>All Doctors</p>
                  </a>
                  </div>
               </td>
            </tr>
            <tr class="menu-row" >
               <td class="menu-btn menu-icon-session">
                  <a href="schedule.php" class="non-style-link-menu">
                     <div>
                        <p class="menu-text"><i class="material-symbols-outlined">calendar_month</i>Scheduled</p>
                     </div>
                  </a>
               </td>
            </tr>
            <tr class="menu-row" >
               <td class="menu-btn menu-icon-appoinment">
                  <a href="appointment.php" class="non-style-link-menu">
                     <div>
                        <p class="menu-text"><i class="material-symbols-outlined">check_box</i>My Bookings</p>
                  </a>
                  </div>
               </td>
            </tr>
            <tr class="menu-row" >
               <td class="menu-btn menu-icon-settings">
                  <a href="settings.php" class="non-style-link-menu">
                     <div>
                        <p class="menu-text"><i class="material-symbols-outlined">settings</i>Settings</p>
                  </a>
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
      <div class="dash-body" style="">
         <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
            <tr >
               <td>
                  <div class="header-right">
                     <p style="font-size: 23px;font-weight: 600;margin-left:20px;">Home</p>
                  </div>
               </td>
               <td colspan="2" class="nav" >
                  <div class="header-content">
                     <nav class="navbar navbar-expand">
                        <div class="collapse navbar-collapse justify-content-between">
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
                          <span style=""><?php echo substr($username,0,13)  ?></span><br>
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
                     </td> -->
                  
            </tr>
          
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
 <h3><?php echo $username  ?>.</h3>
</div>
</h4>
<p >We would like to take this opportunity to welcome you to our practice and to thank you for choosing our physicians to participate in your healthcare. We look forward to providing you with personalized, comprehensive health care focusing on wellness and prevention.
</p>
</div>
</div>
</div>
                  <center>
                     <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                        <tr>
                           <td >
                              <h3>Welcome!</h3>
                              <h1><?php echo $username  ?>.</h1>
                              <p>Haven't any idea about doctors? no problem let's jumping to 
                                 <a href="doctors.php" class="non-style-link"><b>"All Doctors"</b></a> section or 
                                 <a href="schedule.php" class="non-style-link"><b>"Sessions"</b> </a><br>
                                 Track your past and future appointments history.<br>Also find out the expected arrival time of your doctor or medical consultant.<br><br>
                              </p>
                              <h3>Channel a Doctor Here</h3>
                              <form action="schedule.php" method="post" style="display: flex">
                              <input type="search" name="search" class="input-text " placeholder="Search Doctor and We will Find The Session Available" list="doctors" style="width:45%;">&nbsp;&nbsp;
                              <?php
                                 echo '<datalist id="doctors">';
                                 $list11 = $database->query("select  docname,docemail from  doctor;");
                                 
                                 for ($y=0;$y<$list11->num_rows;$y++){
                                     $row00=$list11->fetch_assoc();
                                     $d=$row00["docname"];
                                     
                                     echo "<option value='$d'><br/>";
                                     
                                 };
                                 
                                 echo ' </datalist>';
                                 ?>
                              <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
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
                                             <div class="h1-dashboard">
                                                <?php    echo $doctorrow->num_rows  ?>
                                             </div>
                                             <br>
                                             <div class="h3-dashboard">
                                                All Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                             </div>
                                             <br>
                                             <div class="h1-dashboard">
                                                <?php    echo $patientrow->num_rows  ?>
                                             </div>
                                          </div>
                                          <div class="dashboard-icons">
                                             <i class="fa fa-wheelchair"></i>
                                          </div>
                                    </td>
                                 </tr>
                                 <tr>
                                 <td style="width: 25%;">
                                 <div  class="dashboard-items bg-info"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                 <div>
                                 <div class="h1-dashboard" >
                                 <?php    echo $appointmentrow ->num_rows  ?>
                                 </div><br>
                                 <div class="h3-dashboard" >
                                 NewBooking &nbsp;&nbsp;
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
                        <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Your Upcoming Booking</p>
                        <center>
                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                        <table width="85%" class="sub-table scrolldown" border="0" >
                        <thead>
                        <tr>
                        <th class="table-headin">
                        Appoint. Number
                        </th>
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
                               $sqlmain= "select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid  and schedule.scheduledate>='$today' order by schedule.scheduledate asc";
                               //echo $sqlmain;
                               $result= $database->query($sqlmain);
                           
                               if($result->num_rows==0){
                                   echo '<tr>
                                   <td colspan="4">
                                   <br><br><br><br>
                                   <center>
                                   <img src="../img/notfound.png" width="25%">
                                   
                                   <br>
                                   <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nothing to show here!</p>
                                   <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Channel a Doctor &nbsp;</font></button>
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
                                   $apponum=$row["apponum"];
                                   $docname=$row["docname"];
                                   $scheduledate=$row["scheduledate"];
                                   $scheduletime=$row["scheduletime"];
                                  
                                   echo '<tr>
                                       <td style="padding:30px;font-size:25px;font-weight:700;"> &nbsp;'.
                                       $apponum
                                       .'</td>
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