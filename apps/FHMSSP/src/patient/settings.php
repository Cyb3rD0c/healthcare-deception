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
        
   <link rel="icon" href="../img/favicon.png" >

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <title>Settings</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-X  0.5s;
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
    $result = $stmt->get_result();
    $userfetch=$result->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

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
                    <td class="menu-btn menu-icon-home " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text"><i class="material-symbols-outlined">home</i>Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text"><i class="material-symbols-outlined">supervised_user_circle</i>all Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text"><i class="material-symbols-outlined">calendar_month</i>Scheduled</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div><p class="menu-text"><i class="material-symbols-outlined">check_box</i>Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings  menu-active menu-icon-settings-active">
                        <a href="settings.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text"><i class="material-symbols-outlined">settings</i>Settings</p></a></div>
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
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                      <tr>

                <div class="header-content">
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
                        
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Settings</p>
                                           
                    </td>
                    
                          <!--   <td width="15%">
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
                    <td colspan="4">
                        
                        <center>
                        <table class="filter-container setting" style="border: none;" border="0">
                           
                            <tr>
                                <td style="width: 25%;">
                                    <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="non-style-link">
                                    <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex">
                                        <div class="btn-icon-back dashboard-icons-setting" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                        <div>
                                                <div class="h1-dashboard">
                                                    Account Settings  &nbsp;

                                                </div><br>
                                                <div class="h3-dashboard" style="font-size: 15px;">
                                                    Edit your Account Details & Change Password
                                                </div>
                                        </div>
                                                
                                    </div>
                                    </a>
                                </td>
                                
                                
                            </tr>
<tr>
<td colspan="4">
    <p style="font-size: 5px">&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 25%;">
    <a href="?action=view&id=<?php echo $userid ?>" class="non-style-link">
    <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex;">
        <div class="btn-icon-back dashboard-icons-setting " style="background-image: url('../img/icons/view-iceblue.svg');"></div>
        <div>
                <div class="h1-dashboard" >
                    View Account Details
                    
                </div><br>
                <div class="h3-dashboard"  style="font-size: 15px;">
                    View Personal information About Your Account
                </div>
        </div>
                
    </div>
    </a>
</td>

</tr>
<tr>
<td colspan="4">
    <p style="font-size: 5px">&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 25%;">
    <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="non-style-link">
    <div  class="dashboard-items setting-tabs"  style="padding:20px;margin:auto;width:95%;display: flex;">
        <div class="btn-icon-back dashboard-icons-setting" style="background-image: url('../img/icons/patients-hover.svg');"></div>
        <div>
                <div class="h1-dashboard" style="color: #ff5050;">
                    Delete Account
                    
                </div><br>
                <div class="h3-dashboard"  style="font-size: 15px;">
                    Will Permanently Remove your Account
                </div>
        </div>
                
    </div>
    </a>
</td>

</tr>
</table>
</center>
</td>
</tr>

</table>
</div>
</div>
<?php 
if($_GET){

$id=$_GET["id"];
$action=$_GET["action"];
if($action=='drop'){
$nameget=$_GET["name"];
echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content">
                            You want to delete Your Account<br>('.substr($nameget,0,40).').
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-account.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="settings.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='view'){
            $sqlmain= "select * from patient where pid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();
            $name=$row["pname"];
            $email=$row["pemail"];
            $address=$row["paddress"];
            
           
            $dob=$row["pdob"];
            $nic=$row['pnic'];
            $tele=$row['ptel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content">
                            eDoc Web App<br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="100%" class="sub-table scrolldown add-doc-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                           
                           
                                <td class="label-td" colspan="">
                                    '.$name.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                           
                           
                                <td class="label-td" colspan="">
                                '.$email.'<br><br>
                                </td>
                            </tr>

                            <tr>
                                <td class="label-td" colspan="">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                           
                                <td class="label-td" colspan="">
                                '.$nic.'<br><br>
                                </td>
                            </tr>

                            <tr>
                                <td class="label-td" colspan="">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            
                           
                                <td class="label-td" colspan="">
                                '.$tele.'<br><br>
                                </td>
                            </tr>

                            <tr>
                                <td class="label-td" colspan="">
                                    <label for="spec" class="form-label">Address: </label>
                                    
                                </td>
                           
                            <td class="label-td" colspan="">
                            '.$address.'<br><br>
                            </td>
                            </tr>

                            <tr>
                                <td class="label-td" colspan="">
                                    <label for="spec" class="form-label">Date of Birth: </label>
                                    
                                </td>
                           
                            <td class="label-td" colspan="">
                            '.$dob.'<br><br>
                            </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <a href="settings.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }elseif($action=='edit'){
            $sqlmain= "select * from patient where pid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();
            $name=$row["pname"];
            $email=$row["pemail"];
           
            
            
            $address=$row["paddress"];
            $nic=$row['pnic'];
            $tele=$row['ptel'];

            $error_1=$_GET["error"];
                $errorlist= array(
                    '1'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
                    '2'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>',
                    '3'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                    '4'=>"",
                    '0'=>'',

                );

            if($error_1!='4'){
                    echo '
                    <div id="popup1" class="overlay">
                            <div class="popup">
                            <center>
                            
                                <a class="close" href="settings.php">&times;</a> 
                                <div style="display: flex;justify-content: center;">
                                <div class="abc">
                                <table width="100%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                        <td class="label-td" colspan="2">'.
                                            $errorlist[$error_1]
                                        .'</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Edit User Account Details.</p>
                                        User ID : '.$id.' (Auto Generated)<br><br>
                                        </td>
                                    </tr>
                                    
                                            <form action="edit-user.php" method="POST" class="add-new-form">

                                  <tr>
                                        <td class="label-td" colspan="">
                                            <label for="Email" class="form-label">Email: </label>
                                            <input type="hidden" value="'.$id.'" name="id00">
                                            <input type="hidden" name="oldemail" value="'.$email.'" >
                                        <input type="email" name="email" class="input-text" placeholder="Email Address" value="'.$email.'" required><br>
                                        </td>
                                   
                                    <td class="label-td" colspan="">
                                            <label for="name" class="form-label">Name: </label>
                                            <input type="text" name="name" class="input-text" placeholder="Doctor Name" value="'.$name.'" required><br>
                                        </td>
                                        
                                    </tr>

                                    <tr>
                                        
                                        
                                   
                                    <tr>
                                        <td class="label-td" colspan="">
                                            <label for="nic" class="form-label">NIC: </label>
                                             <input type="text" name="nic" class="input-text" placeholder="NIC Number" value="'.$nic.'" required><br>
                                        </td>
                                    
                                        <td class="label-td" colspan="">
                                            <label for="Tele" class="form-label">Telephone: </label>
                                            <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" value="'.$tele.'" required><br>
                                        </td>
                                    </tr>

                                    
                                    <tr>
                                        <td class="label-td" colspan="">
                                            <label for="spec" class="form-label">Address</label>
                                           <input type="text" name="address" class="input-text" placeholder="Address" value="'.$address.'" required><br> 
                                        </td>
                                   
                                    
                                        <td class="label-td" colspan="">
                                            <label for="password" class="form-label">Password: </label>
                                            <input type="password" name="password" class="input-text" placeholder="Defind a Password" required><br>
                                        </td>
                                    </tr>
                                   <tr>
                                        <td class="label-td" colspan="">
                                            <label for="cpassword" class="form-label">Conform Password: </label>
                                            <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required><br>
                                        </td>
                                    </tr>
                                   
                        
                                    <tr>
                                        <td colspan="2">
                                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                            <input type="submit" value="Save" class="login-btn btn-primary btn">
                                        </td>
                        
                                    </tr>
                                
                                    </form>
                                    </tr>
                                </table>
                                </div>
                                </div>
                            </center>
                            <br><br>
                    </div>
                    </div>
                    ';
        }else{
            echo '
                <div id="popup1" class="overlay">
                        <div class="popup">
                        <center>
                        <br><br><br><br>
                            <h2>Edit Successfully!</h2>
                            <a class="close" href="settings.php">&times;</a>
                            <div class="content">
                                If You change your email also Please logout and login again with your new email
                                
                            </div>
                            <div style="display: flex;justify-content: center;">
                            
                            <a href="settings.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                            <a href="../logout.php" class="non-style-link"><button  class="btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;Log out&nbsp;&nbsp;</font></button></a>

                            </div>
                            <br><br>
                        </center>
                </div>
                </div>
    ';



        }; }

    }
        ?>
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