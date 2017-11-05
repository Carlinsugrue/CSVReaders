<?php
//load the database configuration file
include ('../dbConfig.php');

if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                //check whether user already exists in database with same email
                $prevQuery = "SELECT userID FROM User WHERE userID = '".$line[0]."'";
                $prevResult = $db->query($prevQuery);
                $username = substr($line[4], 0, strpos($line[4], '@'));
                if($prevResult->num_rows > 0){
                    //update user data
                    $db->query("UPDATE User SET firstname = '".$line[1]."', surname = '".$line[2]."', username = '".$username."', WHERE userID = '".$line[0]."'");
                }else{
                    //insert user data into database
                    $db->query("INSERT INTO User (userID, firstname, surname, username) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$username."')");
                }
            }
            
            //close opened csv file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

//redirect to the listing page
header("Location: index.php".$qstring);