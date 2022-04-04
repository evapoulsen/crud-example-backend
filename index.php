<?php
    /* header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: Content-Type");
    */
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding");
    header("Content-type:application/json");
    require_once ("connect.php");
    
    
    $method = $_SERVER['REQUEST_METHOD'];
    if(isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    } else {
        $user_id = "";
    }

    switch($method) {

        case "GET":
            if ($user_id == "") {
                $query = "SELECT * FROM users";
                $usersdata = array();
                $result = mysqli_query($con, $query);
                if (!$result) die (mysqli_error($con));
                else {
                    $rows = mysqli_num_rows($result);
                    if ($rows > 0) {
                        while($row = mysqli_fetch_object($result)) {
                            $usersdata[] = $row;
                        }
                    } echo json_encode($usersdata);
                }
            } else {
                $query = "SELECT * FROM users WHERE user_id = $user_id";
                $result = mysqli_query($con, $query);
                if (!$result) die (mysqli_error($con));
                else {
                    $rows = mysqli_num_rows($result);
                    if ($rows > 0) {
                        while($row = mysqli_fetch_object($result)) {
                            $userdata[] = $row;
                        }
                    } echo json_encode($userdata);
                }
            }
            break;
            
        case "DELETE":
            $query = "DELETE FROM users WHERE user_id = $user_id";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $response = ['status' => 0, 'message' => 'Failed to delete record.'];
                die(mysqli_error($con));
            } else {
                $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
            }
            echo json_encode($response);
            break;

        case "PUT":
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $query = "UPDATE users SET firstName = '$firstName', lastName = '$lastName', email = '$email', updated_at = NOW() 
            WHERE user_id = '$user_id'";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $response = ['status' => 0, 'message' => 'Failed to update record.'];
                die(mysqli_error($con));
            } else {
                $response = ['status' => 1, 'message' => 'Record updated successfully.'];
            }
            echo json_encode($response);
            break;
        
        case "POST":
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $password = $data['password'];
            $pass_hash = password_hash("$password", PASSWORD_DEFAULT);
            $query = "INSERT INTO users(firstName, lastName, email, pwd) 
            VALUES('$firstName', '$lastName', '$email', '$pass_hash')";
            $result = mysqli_query($con, $query);
            if (!$result) {
                $response = ['status' => 0, 'message' => 'Failed to create user.'];
                die(mysqli_error($con));
            } else {
                $response = ['status' => 1, 'message' => 'User successfully created.'];
            }
        }
?>
