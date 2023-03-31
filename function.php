<?php

require 'db-con.php';


// this is error 422 message fuction starts from here
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];

    header("HTTP/1.0 422 Unprocessable Data");
    echo json_encode($data);
    exit();
}

// get customer list function starts from here
function getCustomerList(){

    global $con;

    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($con, $query);

    if($query_run){

        if(mysqli_num_rows($query_run) > 0){

            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Ok, Users Found',
                'data' => $res,
            ];

            header("HTTP/1.0 200 Ok");
            return json_encode($data);


        }else{
            $data = [
                'status' => 404,
                'message' => 'Users Not Found',
            ];

            header("HTTP/1.0 404 Users Not Found");
            return json_encode($data);
        }

    }else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}


// get sigle customer function starts from here
function getCustomer($customerParams){
    global $con;

    if($customerParams['id'] == null){

        return error422('Enter your customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    $query = "SELECT * FROM customers WHERE id = '$customerId' LIMIT 1";
    $result = mysqli_query($con, $query);

    if($result){

        if(mysqli_num_rows($result) == 1){

            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'Customer feached successfully',
                'data' => $res,
            ];

            header("HTTP/1.0 200 Ok Featched");
            return json_encode($data);

        }else{
            $data = [
                'status' => 404,
                'message' => 'User Not Found',
            ];

            header("HTTP/1.0 404 User Not Found");
            return json_encode($data);
        }

    }else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}


// store customer data fuction starts from here
function storeCustomer($customerInput){
    global $con;

    $username = mysqli_real_escape_string($con, $customerInput['username']);
    $email = mysqli_real_escape_string($con, $customerInput['email']);
    $phone = mysqli_real_escape_string($con, $customerInput['phone']);

    if(empty(trim($username))){

        return error422('Enter Your Username');
    }elseif(empty(trim($email))){

        return error422('Enter Your Email');
    }elseif(empty(trim($phone))){

        return error422('Enter Your Phone');
    }else{
        $query = "INSERT INTO customers (username, email, phone) VALUES ('$username', '$email', '$phone')";
        $result = mysqli_query($con, $query);

        if($result){
            
            $data = [
                'status' => 201,
                'message' => 'Customer Created Successfully',
            ];

            header("HTTP/1.0 201 Created");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];

            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
            }
    }

}


// update customer data function starts from here
function updateCustomer($customerInput, $customerParams){
    global $con;

    if(!isset($customerParams['id'])){

        return error422('customer id not found in url');
    }elseif($customerParams['id'] == null){
        return error422('Enter the customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    $username = mysqli_real_escape_string($con, $customerInput['username']);
    $email = mysqli_real_escape_string($con, $customerInput['email']);
    $phone = mysqli_real_escape_string($con, $customerInput['phone']);

    if(empty(trim($username))){

        return error422('Enter Your Username');
    }elseif(empty(trim($email))){

        return error422('Enter Your Email');
    }elseif(empty(trim($phone))){

        return error422('Enter Your Phone');
    }else{
        $query = "UPDATE customers SET username = '$username', email = '$email', phone = '$phone' WHERE id = '$customerId' LIMIT 1";
        $result = mysqli_query($con, $query);

        if($result){
            
            $data = [
                'status' => 200,
                'message' => 'Customer Updated Successfully',
            ];

            header("HTTP/1.0 200 Success");
            return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];

            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
            }
    }
}


// delete customer function starts from here
function deleteCustomer($customerParams){
    global $con;

    if(!isset($customerParams['id'])){

        return error422('customer id not found in url');
    }elseif($customerParams['id'] == null){

        return error422('enter the customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    // first check customer have or not with this $customerId
    $checkQuery = "SELECT * FROM customers WHERE id = '$customerId' LIMIT 1";
    $checkResult = mysqli_query($con, $checkQuery);

    if(mysqli_num_rows($checkResult) == 1){

        // then delete the customer with $customerId
        $query = "DELETE FROM customers WHERE id = '$customerId' LIMIT 1";
        $result = mysqli_query($con, $query);

        if($result){
            $data = [
                'status' => 200,
                'message' => 'Customer Deleted Successfully',
            ];

            header("HTTP/1.0 200 Ok");
            return json_encode($data);

        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];

            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
            }
    }else{
        $data = [
            'status' => 404,
            'message' => 'Customer Not Found',
        ];

        header("HTTP/1.0 404 Customer Not Found");
        return json_encode($data);
        }
    }


?>