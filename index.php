<!DOCTYPE = html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    function searchEmail($id, $array) 
    {
    foreach ($array as $key => $value) 
    {
        if ($value[0] == $id) 
        {
            return $value;
        }
    }
    return null;
    }

    function searchPassword($id, $array) 
    {
        foreach ($array as $key => $value) 
        {
            if ($value[1] == $id) 
            {
                return $value;
            }
        }
        return null;
    }

echo '
    <section>
        <div class="form-container">
            <h1>LOGIN</h1>
            <form action ="index.php" method="post">
                <div class="control">
                    <label for ="name"> Name </label>
                    <input type="text" name="name" id="name">
                </div>
                <div class="control">
                    <label for = "passw">Password </label>
                    <input type="password" name="password" id="passw">
                </div>
                <div class ="control">
                    <input type="submit" value="Login" name="submit" id="submit">
                </div>
                <div class="link">
                    <a href="https://youtu.be/MI-BjoALUnE">Forgot password?</a>
                </div>
            </form>
';
if(isset($_POST['submit'])) 
    {
        $username = $_POST["name"];
        $password = $_POST["password"];
    
        if($username=="" or $password=="") 
        {
            print '
                <script type="text/javascript">
                    alert("Please enter your username and password!"); 
                </script> ';
        }
        else
        {
            $file = fopen("files/password.txt", "r") or exit("Unable to open file!");
            while(!feof($file)) 
            {
                $raw_pass[] = fgets($file);
            }
            array_pop($raw_pass);
            
            fclose($file);
    
            for($i=0;$i<count($raw_pass);$i++) 
            {
                $conv_hexa[$i]=bin2hex($raw_pass[$i]);
                $split_hexa[$i]=str_split($conv_hexa[$i],2);
            }
            
            for($i=0;$i<count($conv_hexa);$i++)
            {
                for($j=0;$j<count($split_hexa[$i]);$j++) 
                {
                    $conv_decimal[$i][$j]=hexdec($split_hexa[$i][$j]);

                    if($conv_decimal[$i][$j] == 10) 
                    {
                        unset($conv_decimal[$i][$j]);
                    }
                }
            }

            $key=[5,-14,31,-9,3];
            foreach($conv_decimal as $row => $value) 
            {
                for($i=0; $i<count($value);$i++) 
                {
                    $increased_decimal[$row][$i]=$value[$i]-$key[$i%5];
                }                     
            }

            for($i=0;$i<count($increased_decimal);$i++) 
            {
                for($j=0;$j<count($increased_decimal[$i]);$j++) 
                {
                    $decoded_pass[$i][$j]=chr($increased_decimal[$i][$j]);
                }
            }
        
            for($i=0;$i<count($decoded_pass);$i++) 
            {
                $decoded[$i]=join($decoded_pass[$i]);
            }
    
            for($i=0;$i<count($decoded);$i++) 
            {
                $split[]=(explode("*",$decoded[$i]));

                file_put_contents("files/decoded.txt", print_r($split, true));
            }


            if(searchEmail($username, $split)!=null && searchPassword($password, $split)!=null) 
            {
                    error_reporting(E_ALL);
                    $dbServer="localhost";
                    $dbUser="root";
                    $dbPass="";
                    $dbName="adatok";
                    
                    $con = new mysqli($dbServer,$dbUser,$dbPass,$dbName);
                    
                    $sql="SELECT Titkos FROM tabla WHERE username='$username'";
                    $result=$con->query($sql);

                    while($sqlcolor=mysqli_fetch_row($result))
                        {
                            $color = $sqlcolor[0];
        
                            print ' <h2 id="result"> ';
                            print $username . " = '".$color. "'.";
                            print ' </h2> ';
                        }
            }
                else 
                {
                    print '
                    <script type="text/javascript">
                     alert("The username or password is incorrect!"); 
                    </script> ';
                }
        }
    }
    
    print '
    </div> 
    </section>
    ';

?>
</body>
</html>