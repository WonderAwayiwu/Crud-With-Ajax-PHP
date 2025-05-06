<?php






require 'dbcon.php';

if (isset($_POST['save_student'])) {
   

    $name = trim(mysqli_real_escape_string($con, $_POST['name']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $phone = trim(mysqli_real_escape_string($con, $_POST['phone']));
    $course = trim(mysqli_real_escape_string($con, $_POST['course']));


    $check_name_query = "SELECT * FROM students WHERE name='$name'";
    $check_name_result = mysqli_query($con, $check_name_query);
    if (mysqli_num_rows($check_name_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Name already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_email_query = "SELECT * FROM students WHERE email='$email'";
    $check_email_result = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Email already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_phone_query = "SELECT * FROM students WHERE phone='$phone'";
    $check_phone_result = mysqli_query($con, $check_phone_query);
    if (mysqli_num_rows($check_phone_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Phone already exists'
        ];
        echo json_encode($res);
        return;
    }


    // $check_course_query = "SELECT * FROM students WHERE course='$course'";
    // $check_course_result = mysqli_query($con, $check_course_query);
    // if (mysqli_num_rows($check_course_result) > 0) {
    //     $res = [
    //         'status' => 422,
    //         'message' => 'Course already exists'
    //     ];
    //     echo json_encode($res);
    //     return;
    // }







    if ($name == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Name field.'
        ];
        echo json_encode($res);
        return;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $res = [
            'status' => 422,
            'message' => 'Invalid email format'
        ];
        echo json_encode($res);
        return;
    }

    if ($email == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Email field.'
        ];
        echo json_encode($res);
        return;
    }



    if ($phone == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Phone field.'
        ];
        echo json_encode($res);
        return;
    }else if (strlen($phone) != 10) {
        $res = [
            'status' => 422,
            'message' => 'Phone number must be 10 digits long'
        ];
        echo json_encode($res);
        return;
    }
    
    if (!ctype_digit($phone)) {
        $res = [
            'status' => 422,
            'message' => 'Phone number must contain only digits'
        ];
        echo json_encode($res);
        return;
    }

    else if (substr($phone, 0, 2) !== "02" && substr($phone, 0, 2) !== "05") {
        $res = [
            'status' => 422,
            'message' => 'Phone number must start with 02 or 05'
        ];
        echo json_encode($res);
        return;
    }
    
    


    if ($course == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Course field.'
        ];
        echo json_encode($res);
        return;
    }

    $query = "INSERT INTO students (name,email,phone,course) VALUES ('$name','$email','$phone','$course')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Created Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if (isset($_POST['update_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $name = trim(mysqli_real_escape_string($con, $_POST['name']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $phone = trim(mysqli_real_escape_string($con, $_POST['phone']));
    $course = trim(mysqli_real_escape_string($con, $_POST['course']));

    if ($name == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Name field.'
        ];
        echo json_encode($res);
        return;
    }

   
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $res = [
            'status' => 422,
            'message' => 'Invalid email format'
        ];
        echo json_encode($res);
        return;
    }

    if ($email == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Email field.'
        ];
        echo json_encode($res);
        return;
    }



    if ($phone == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Phone field.'
        ];
        echo json_encode($res);
        return;
    }else if (strlen($phone) != 10) {
        $res = [
            'status' => 422,
            'message' => 'Phone number must be 10 digits long'
        ];
        echo json_encode($res);
        return;
    } 
    
    if (!ctype_digit($phone)) {
        $res = [
            'status' => 422,
            'message' => 'Phone number must contain only digits'
        ];
        echo json_encode($res);
        return;
    }
    else if (substr($phone, 0, 2) !== "02" && substr($phone, 0, 2) !== "05") {
        $res = [
            'status' => 422,
            'message' => 'Phone number must start with 02 or 05'
        ];
        echo json_encode($res);
        return;
    }
    
    


    if ($course == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Course field.'
        ];
        echo json_encode($res);
        return;
    }



    $check_name_query = "SELECT * FROM students WHERE name='$name' AND id != '$student_id'";
    $check_name_result = mysqli_query($con, $check_name_query);
    if (mysqli_num_rows($check_name_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Name already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_email_query = "SELECT * FROM students WHERE email='$email' AND id != '$student_id'";
    $check_email_result = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Email already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_phone_query = "SELECT * FROM students WHERE phone='$phone' AND id != '$student_id'";
    $check_phone_result = mysqli_query($con, $check_phone_query);
    if (mysqli_num_rows($check_phone_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Phone already exists'
        ];
        echo json_encode($res);
        return;
    }




    $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' 
                WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Updated Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if (isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['student_id']);

    $query = "SELECT * FROM students WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $student = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'Student Fetch Successfully by id',
            'data' => $student
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Student Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $query = "DELETE FROM students WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
