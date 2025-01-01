<?php
//POST (JSON):
include('../model/qlydocgia_model.php');
// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header("Access-Control-Allow-Methods: POST");
// header("Allow: GET, POST, OPTIONS, PUT, DELETE");
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");


$request_method = $_SERVER['REQUEST_METHOD'];
switch ($request_method){
    case 'POST':
        $inputdata = json_decode(file_get_contents("php://input"), true);

    if(empty($inputdata)) {
        var_dump($_POST);
        $createStory= insertStory($_POST);
    } else {
        $createStory = insertStory($inputdata);

    }
    echo $createStory;
    break;
    
    case 'PUT':
        $inputdata = json_decode(file_get_contents("php://input"), true);

    if (empty($inputdata) || !isset($inputdata["ma_truyen"]) || !isset($inputdata["ten_truyen"]) || !isset($inputdata["tac_gia"]) || !isset($inputdata["mo_ta"]) ) {
        $data = [
            'status' => 400,
            'message' => 'Bad Request: Missing required fields',
        ];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
        exit;
    }
    $truyenupdate = updateStory($inputdata);
    echo $truyenupdate;
    break;
    case 'DELETE':
        $inputdata = json_decode(file_get_contents("php://input"), true);

    if(!empty($inputdata)) {
        $deleteStory = deleteStory($inputdata); 
        echo $deleteStory;
    } else {
        $data = [
            'status' => 400,
            'message' => 'Invalid or Empty Data',
        ];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
    }
    break;
    case'GET':
        $customerList = readStoryList();
            echo $customerList;
            break;
    default:
    $data = [
                'status' => 405,
                'message' => $requestmethod . ' Method Not Allowed',
            ];
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode($data);
}


// if($requestmethod == 'POST') {
//     $inputdata = json_decode(file_get_contents("php://input"), true);

//     if(empty($inputdata)) {
//         var_dump($_POST);
//         $createStory= insertStory($_POST);
//     } else {
//         $createStory = insertStory($inputdata);

//     }
//     echo $createStory;

// } else {
//     $data = [
//         'status' => 405,
//         'message' => $requestmethod . ' Method Not Allowed',
//     ];
//     header("HTTP/1.0 405 Method Not Allowed");
//     echo json_encode($data);
// }
// $requestmethod = $_SERVER['REQUEST_METHOD'];
// if($requestmethod == 'PUT') {
//     $inputdata = json_decode(file_get_contents("php://input"), true);

//     if (empty($inputdata) || !isset($inputdata["ma_truyen"]) || !isset($inputdata["ten_truyen"]) || !isset($inputdata["tac_gia"]) || !isset($inputdata["mo_ta"]) ) {
//         $data = [
//             'status' => 400,
//             'message' => 'Bad Request: Missing required fields',
//         ];
//         header("HTTP/1.0 400 Bad Request");
//         echo json_encode($data);
//         exit;

// }
// $truyenupdate = updateStory($inputdata);
//     echo $truyenupdate;
// }
//  else {
//     $data = [
//         'status' => 405,
//         'message' => $requestmethod . ' Method Not Allowed',
//     ];
//     header("HTTP/1.0 405 Method Not Allowed");
//     echo json_encode($data);
// }
// $requestmethod = $_SERVER['REQUEST_METHOD'];
// if($requestmethod == 'DELETE') {
//     $inputdata = json_decode(file_get_contents("php://input"), true);

//     if(!empty($inputdata)) {
//         $deleteStory = deleteStory($inputdata); 
//         echo $deleteStory;
//     } else {
//         $data = [
//             'status' => 400,
//             'message' => 'Invalid or Empty Data',
//         ];
//         header("HTTP/1.0 400 Bad Request");
//         echo json_encode($data);
//     }
// } else {
//     $data = [
//         'status' => 405,
//         'message' => $requestmethod . ' Method Not Allowed',
//     ];
//     header("HTTP/1.0 405 Method Not Allowed");
//     echo json_encode($data);
// }
// $requestmethod = $_SERVER['REQUEST_METHOD'];
    // if($requestmethod == 'GET') {
    //     // if(isset($_GET['id'])) {
    //     //     $customer = readStoryOne($_GET['id']);
    //     //     echo $customer;
    //     // } else {
    //     //     $customerList = readStoryList();
    //     //     echo $customerList;
    //     // }
    //     $customerList = readStoryList();
    //         echo $customerList;
    // } else {
    //     $data = [
    //         'status' => 405,
    //         'message' => $requestmethod . 'Method Not Allowed',
    //     ];
    //     header("HTTP/1.0 405 Method Not Allowed");
    //     echo json_encode($data);
    // }
?>