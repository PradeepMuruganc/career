<html>
<body>
<?php



$nameError = $emailError = $phoneError = $locationError = $descriptionError = "";
$Name = $Email = $Phone = $Location = $Description= "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Name"])) {
        $nameError = "Name field is empty";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $_POST["Name"])) {
        $nameError = "Only letters and white space allowed";
    }
        else{
            $Name = test_input($_POST["Name"]);
        }
    
    if (empty($_POST["Email"])) {
        $emailError = "Email field is empty";
    } else if (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format";
        }
        else{
            $Email = test_input($_POST["Email"]);
        }
    
    if (empty($_POST["Phone"])) {
        $phoneError = "Phone field is empty";
    } else if (!preg_match("/^\d{10}$/", $_POST["Phone"])) {
            $phoneError = "Invalid phone number";
        }
        else{
            $Phone = test_input($_POST["Phone"]);
        }
    
    if (empty($_POST["Location"])) {
        $locationError = "Location field is empty";
    } else {
        $Location = test_input($_POST["Location"]);
    }
    if (empty($_POST["Description"])) {
        $descriptionError = "Description field is empty";
    } else {
        $Description = test_input($_POST["Description"]);
    }


    $file_size = $_FILES["fileToUpload"]["size"];
    $file_type = $_FILES["fileToUpload"]["type"];
    $allowedTypes = array(
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "text/plain"
    );

    if (in_array($file_type, $allowedTypes)) {
        $max_size = 100 * 1024 * 1024;
        if ($file_size > $max_size) {
            echo "Error: File size exceeds the maximum limit of 100MB.";
        } else if ($_FILES["fileToUpload"]["error"] == 0) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                if(isset($_POST["Name"]) && isset($_POST["Email"]) && isset($_POST["Phone"]) && isset($_POST["Location"]) && isset($_POST["Description"]) && isset($_FILES["fileToUpload"])):
                    $conn=new mysqli('localhost','root','0099','career');
                    $Name = $_POST["Name"];
                    $Email = $_POST["Email"];
                    $Phone = $_POST["Phone"];
                    $Location =$_POST["Location"];
                    $Description =$_POST["Description"];
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
                
                    if($conn->connect_error){
                        die("Connection failed:". $conn->connect_error);
                    }
                    
                    $sql = "INSERT INTO details (Name, Email, Phone, Location, Description, Documents) VALUES ('$Name', '$Email', '$Phone', '$Location', '$Description', '$targetFile')";    
                    if($conn->query($sql)===TRUE){
                        echo "Your record stored successfully";
                    }
                    else{
                        echo "Error:". $conn->error;
                    }
                
                    $conn->close();
                endif;  
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "No file uploaded or there was an error.";
        }
    } else {
        echo "Error: Unsupported file type.";
    }
}
    
 function test_input($Data) {
    $Data = trim($Data);
    $Data = stripslashes($Data);
    $Data = htmlspecialchars($Data);
    return $Data;
}





?>

<br>YOUR NAME IS <span style="color:Green;"><?php echo $Name;?></span><span style="color:Red;"><?php echo $nameError?></span><br>
YOUR EMAIL IS <span style="color:Green;"><?php echo $Email;?></span><span style="color:Red;"><?php echo $emailError?></span><br>
YOUR PHONE IS <span style="color:Green;"><?php echo $Phone;?></span><span style="color:Red;"><?php echo $phoneError?></span><br>
YOUR LOCATION IS <span style="color:Green;"><?php echo $Location;?></span><span style="color:Red;"><?php echo $locationError?></span><br>
YOUR DESCRIPTION IS <span style="color:Green;"><?php echo $Description;?></span><span style="color:Red;"><?php echo $descriptionError?></span>


</body>
</html>
