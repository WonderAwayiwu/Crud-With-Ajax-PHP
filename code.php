<?php

require 'dbcon.php';

if (isset($_POST['save_student'])) {

    $image_name = null;
    if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] == 0) {

        $file_name = $_FILES['student_image']['name'];
        $file_size = $_FILES['student_image']['size'];
        $file_tmp = $_FILES['student_image']['tmp_name'];
        $file_type = $_FILES['student_image']['type'];


        $file_ext_array = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext_array));


        $allowed_extensions = array("jpg", "jpeg", "png");


        if (in_array($file_ext, $allowed_extensions)) {
            //validate file sizse not more that 2MB
            if ($file_size <= 2000000) {

                $image_name = "student_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
                $upload_path = "uploads/" . $image_name;


                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $res = [
                        'status' => 422,
                        'message' => 'Failed to upload image'
                    ];
                    echo json_encode($res);
                    return;
                }
            } else {
                $res = [
                    'status' => 422,
                    'message' => 'Image size should be less than 2MB'
                ];
                echo json_encode($res);
                return;
            }
        } else {
            $res = [
                'status' => 422,
                'message' => 'Only JPG, JPEG and PNG files are allowed'
            ];
            echo json_encode($res);
            return;
        }
    }








    $currentYear = date('y');
    $prefix = 'REG' . $currentYear;


    $index_query = "SELECT IndexNumber FROM students WHERE IndexNumber LIKE '$prefix%' ORDER BY IndexNumber DESC LIMIT 1";
    $index_result = mysqli_query($con, $index_query);
    $row = mysqli_fetch_assoc($index_result);

    if (isset($row['IndexNumber'])) {

        $numericPart = (int)substr($row['IndexNumber'], strlen($prefix));

        $nextNumber = $numericPart + 1;
    } else {

        $nextNumber = 1;
    }

    $newIndex = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);


    $name = trim(mysqli_real_escape_string($con, $_POST['name']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $phone = trim(mysqli_real_escape_string($con, $_POST['phone']));
    $course = trim(mysqli_real_escape_string($con, $_POST['course']));
    $IndexNumber = mysqli_real_escape_string($con, $_POST['IndexNumber']);



    $check_name_query = "SELECT * FROM students WHERE name='$name' and  is_deleted = '0'";
    $check_name_result = mysqli_query($con, $check_name_query);
    if (mysqli_num_rows($check_name_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Name already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_email_query = "SELECT * FROM students WHERE email='$email' and  is_deleted = '0'";
    $check_email_result = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Email already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_phone_query = "SELECT * FROM students WHERE phone='$phone' and  is_deleted = '0'";
    $check_phone_result = mysqli_query($con, $check_phone_query);
    if (mysqli_num_rows($check_phone_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Phone already exists'
        ];
        echo json_encode($res);
        return;
    }



    if ($name == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Please fill in the Name field.'
        ];
        echo json_encode($res);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    } else if (strlen($phone) != 10) {
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
    } else if (substr($phone, 0, 2) !== "02" && substr($phone, 0, 2) !== "05") {
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



    $query = "INSERT INTO students (name, email, phone, course, IndexNumber, image) 
          VALUES ('$name', '$email', '$phone', '$course', '$newIndex', '$image_name')";

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

    $image_update = "";
    if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] == 0) {

        $file_name = $_FILES['student_image']['name'];
        $file_size = $_FILES['student_image']['size'];
        $file_tmp = $_FILES['student_image']['tmp_name'];
        $file_type = $_FILES['student_image']['type'];


        $file_ext_array = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext_array));


        $allowed_extensions = array("jpg", "jpeg", "png");


        if (in_array($file_ext, $allowed_extensions)) {

            if ($file_size <= 2000000) {

                $image_name = "student_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
                $upload_path = "uploads/" . $image_name;


                if (move_uploaded_file($file_tmp, $upload_path)) {

                    $get_img_query = "SELECT image FROM students WHERE id='$student_id'";
                    $img_result = mysqli_query($con, $get_img_query);
                    $img_data = mysqli_fetch_assoc($img_result);


                    if ($img_data['image'] && file_exists("uploads/" . $img_data['image'])) {
                        unlink("uploads/" . $img_data['image']);
                    }


                    $image_update = ", image='$image_name'";
                } else {
                    $res = [
                        'status' => 422,
                        'message' => 'Failed to upload image'
                    ];
                    echo json_encode($res);
                    return;
                }
            } else {
                $res = [
                    'status' => 422,
                    'message' => 'Image size should be less than 2MB'
                ];
                echo json_encode($res);
                return;
            }
        } else {
            $res = [
                'status' => 422,
                'message' => 'Only JPG, JPEG and PNG are allowed'
            ];
            echo json_encode($res);
            return;
        }
    }










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


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    } else if (strlen($phone) != 10) {
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
    } else if (substr($phone, 0, 2) !== "02" && substr($phone, 0, 2) !== "05") {
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



    $check_name_query = "SELECT * FROM students WHERE name='$name' AND id != '$student_id' and  is_deleted = '0'";
    $check_name_result = mysqli_query($con, $check_name_query);
    if (mysqli_num_rows($check_name_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Name already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_email_query = "SELECT * FROM students WHERE email='$email' AND id != '$student_id' and  is_deleted = '0'";
    $check_email_result = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Email already exists'
        ];
        echo json_encode($res);
        return;
    }


    $check_phone_query = "SELECT * FROM students WHERE phone='$phone' AND id != '$student_id' and  is_deleted = '0'";
    $check_phone_result = mysqli_query($con, $check_phone_query);
    if (mysqli_num_rows($check_phone_result) > 0) {
        $res = [
            'status' => 422,
            'message' => 'Phone already exists'
        ];
        echo json_encode($res);
        return;
    }


    $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' $image_update 
          WHERE id='$student_id' and is_deleted = '0'";

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

    $query = "SELECT * FROM students WHERE id='$student_id' and is_deleted = '0'";
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

    // $query = "DELETE FROM students WHERE id='$student_id' and is_deleted = '0'";
    // $query_run = mysqli_query($con, $query);

    $sql = "UPDATE students SET is_deleted = 1 WHERE id = $student_id";
    $query_run = $con->query($sql);

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
