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

         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

          <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <title>Sessions</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
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


    //echo $userid;
    //echo $username;
    
    date_default_timezone_set('Asia/Kolkata');

    $today = date('Y-m-d');


 //echo $userid;
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
                        <a href="index.php" class="non-style-link-menu ">
                            <div><p class="menu-text"><i class="material-symbols-outlined">home</i>Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu">
                            <div><p class="menu-text"><i class="material-symbols-outlined">supervised_user_circle</i>All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text"><i class="material-symbols-outlined">calendar_month</i>Scheduled</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div><p class="menu-text"><i class="material-symbols-outlined">check_box</i>Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu">
                            <div><p class="menu-text"><i class="material-symbols-outlined">settings</i>Settings</p></a></div>
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
        <?php

                $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today'  order by schedule.scheduledate asc";
                $sqlpt1="";
                $insertkey="";
                $q='';
                $searchtype="All";
                        if($_POST){
                        //print_r($_POST);
                        
                        if(!empty($_POST["search"])){
                            /*TODO: make and understand */
                            $keyword=$_POST["search"];
                            $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and (doctor.docname='$keyword' or doctor.docname like '$keyword%' or doctor.docname like '%$keyword' or doctor.docname like '%$keyword%' or schedule.title='$keyword' or schedule.title like '$keyword%' or schedule.title like '%$keyword' or schedule.title like '%$keyword%' or schedule.scheduledate like '$keyword%' or schedule.scheduledate like '%$keyword' or schedule.scheduledate like '%$keyword%' or schedule.scheduledate='$keyword' )  order by schedule.scheduledate asc";
                            //echo $sqlmain;
                            $insertkey=$keyword;
                            $searchtype="Search Result : ";
                            $q='"';
                        }

                    }


                $result= $database->query($sqlmain)


                ?>
                  
        <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                
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
              <td >
            <!-- <form action="" method="post" class="header-search"> -->

                        <!-- <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php  echo $insertkey ?>">&nbsp;&nbsp; -->
                        
                        <?php
                           // echo '<datalist id="doctors">';
                           // $list11 = $database->query("select DISTINCT * from  doctor;");
                           // $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;")//;
                            

                           // for ($y=0;$y<$list11->num_rows;$y++){
                            //    $row00=$list11->fetch_assoc();
                            //    $d=$row00["docname"];
                            //   
                            //    echo "<option value='$d'><br/>";
                               
                           // };


                            //for ($y=0;$y<$list12->num_rows;$y++){
                            //    $row00=$list12->fetch_assoc();
                             //   $d=$row00["title"];
                               
                            //    echo "<option value='$d'><br/>";
                                                                      

                       // echo ' </datalist>';
?>
                        
                
                        <!-- <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;"> -->
                        <!-- </form> -->
    </td>
    
                </tr>
                
                
                <tr style="background: #dae1f3;">


    <td>
        <p style="font-size:18px;background: #dae1f3;color: #444;"><?php echo $searchtype." Sessions"."(".$result->num_rows.")"; ?> </p>
        <p ><?php echo $q.$insertkey.$q ; ?> </p>
    </td>

    <td>
      <form action="" method="post" class="header-search">

        <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php  echo $insertkey ?>">&nbsp;&nbsp;
        
        <?php
            echo '<datalist id="doctors">';
            $list11 = $database->query("select DISTINCT * from  doctor;");
            $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;");
            

            for ($y=0;$y<$list11->num_rows;$y++){
                $row00=$list11->fetch_assoc();
                $d=$row00["docname"];
               
                echo "<option value='$d'><br/>";
               
            };//


            for ($y=0;$y<$list12->num_rows;$y++){
                $row00=$list12->fetch_assoc();
                $d=$row00["title"];
               
                echo "<option value='$d'><br/>";
                                                         };

        echo ' </datalist>';
?>
        

        <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
        </form>  
    </td>
    
                </tr>
                
                
                
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                            
                        <tbody >
                        
                            <?php

                                
                                

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
                                    //echo $result->num_rows;
                                for ( $x=0; $x<($result->num_rows);$x++){
                                    echo "<tr>";
                                    for($q=0;$q<3;$q++){
                                        $row=$result->fetch_assoc();
                                        if (!isset($row)){
                                            break;
                                        };
                                        $scheduleid=$row["scheduleid"];
                                        $title=$row["title"];
                                        $docname=$row["docname"];
                                        $scheduledate=$row["scheduledate"];
                                        $scheduletime=$row["scheduletime"];

                                        if($scheduleid==""){
                                            break;
                                        }

                                        echo '
                                        <td style="width: 25%;">
                                                <div  class="dashboard-items search-items"  >
                                                
                                                    <div style="width:100%">
                                                            <div class="h1-search">
                                                                '.substr($title,0,21).'
                                                            </div><br>
                                                            <div class="h3-search">
                                                                '.substr($docname,0,30).'
                                                            </div>
                                                            <div class="h4-search">
                                                                '.$scheduledate.'<br>Starts: <b>@'.substr($scheduletime,0,5).'</b> (24h)
                                                            </div>
                                                            <br>
                                                            <a href="booking.php?id='.$scheduleid.'" ><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Book Now</font></button></a>
                                                    </div>
                                                            
                                                </div>
                                            </td>';

                                    }
                                    echo "</tr>";
                                    
                                    
                                    // echo '<tr>
                                    //     <td> &nbsp;'.
                                    //     substr($title,0,30)
                                    //     .'</td>
                                        
                                    //     <td style="text-align:center;">
                                    //         '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
                                    //     </td>
                                    //     <td style="text-align:center;">
                                    //         '.$nop.'
                                    //     </td>

                                    //     <td>
                                    //     <div style="display:flex;justify-content: center;">
                                        
                                    //     <a href="?action=view&id='.$scheduleid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                    //    &nbsp;&nbsp;&nbsp;
                                    //    <a href="?action=drop&id='.$scheduleid.'&name='.$title.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel Session</font></button></a>
                                    //     </div>
                                    //     </td>
                                    // </tr>';
                                    
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
        </div>
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
