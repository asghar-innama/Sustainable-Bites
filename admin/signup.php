<?php
// session_start();
// $connection=mysqli_connect("localhost:3307","root","");
// $db=mysqli_select_db($connection,'demo');
include '../connection.php';
$msg=0;
if(isset($_POST['sign']))
{

    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    $location=$_POST['district'];
    $address=$_POST['address'];

    $pass=password_hash($password,PASSWORD_DEFAULT);
    $sql="select * from admin where email='$email'" ;
    $result= mysqli_query($connection, $sql);
    $num=mysqli_num_rows($result);
    if($num==1){
        echo "<h1><center>Account already exists</center></h1>";
    }
    else {
        $query="insert into admin(name,email,password,location,address) values('$username','$email','$pass','$location','$address')";
        $query_run= mysqli_query($connection, $query);
        if($query_run)
        {
            header("location:signin.php");
        }
        else
        {
            echo '<script type="text/javascript">alert("data not saved")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formstyle.css">
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Register</title>
</head>
<body>
    <div class="container">
        <form action=" " method="post" id="form">
            <span class="title">Register</span>
            <br>
            <br>
            <div class="input-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" required/>
                <div class="error"></div>
            </div>
            <div class="input-group">
                    <label for="email">Email</label>
                <input type="email" id="email" name="email" required/>
                        
                    </div>
            <label class="textlabel" for="password">Password</label> 
             <div class="password">
              
                <input type="password" name="password" id="password"  required/>
                <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>                
                <?php
                    if($msg==1){
                        echo ' <i class="bx bx-error-circle error-icon"></i>';
                        echo '<p class="error">Password don\'t match.</p>';
                    }
                    ?> 
             </div>
            <div class="input-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" id="address" required/></textarea>
                    </div>
            <div class="input-field">
                        <select id="district" name="district" style="padding:10px; padding-left: 20px;">
                          <option value="chennai">Bangalore</option>
                          <option value="kancheepuram">Mysuru</option>
                          <option value="thiruvallur">Hebbal</option>
                          <option value="vellore">Ramnagar</option>
                        </select> 
                    </div>         
            <button type="submit" name="sign">Register</button>
            <div class="login-signup" >
                    <span class="text">Already a member?
                        <a href="signin.php" class="text login-link">Login Now</a>
                    </span>
                </div>
        </form>
    </div>
    <br>
    <br>
    <script src="login.js" ></script>
</body>
</html>