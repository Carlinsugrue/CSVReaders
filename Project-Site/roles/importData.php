<?php
//load the database configuration file
include ('../dbConfig.php');

if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    if(!empty($_POST['role'])){
	
                //check whether user already exists in database with same email
                $prevQuery = "SELECT role FROM Role WHERE role = '".$_POST['role']."'";
                $prevResult = $db->query($prevQuery);
                if($prevResult->num_rows > 0){
                    //update user data
					//$db->query("UPDATE User SET firstname = '".$line[1]."', surname = '".$line[2]."', username = '".$username."', WHERE userID = '".$line[0]."'");
                }else{
                    //insert user data into database
                    $db->query("INSERT INTO Role (role) VALUES ('".$_POST['role']."')");
                }

            $qstring = '?status=succ';

    }else{
        $qstring = '?status=invalid_file';
    }
}

//redirect to the listing page
header("Location: index.php".$qstring);