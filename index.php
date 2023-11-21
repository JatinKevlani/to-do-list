<?php
include('backend/database/connection.php');
session_start();

// Fetching user details
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $query = "SELECT * FROM $user[1]";
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    $tasks = $result->fetch_all();
}

// Check for invalid username or password
if (isset($_SESSION['error_message'])) {
    if ($_SESSION['error_message'] == true) {
        echo "<script>alert('Invalid username or password!')</script>";
        $_SESSION['error_message'] = false;
    }
}

// Check for duplicate username error
if (isset($_SESSION['same_name'])) {
    if ($_SESSION['same_name'] == true) {
        echo "
            <script>alert('This username is already taken choose another username.');</script>
        ";
        $_SESSION['same_name'] = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" href="assets/images/download.png">
    <link rel="stylesheet" href="css/datatable.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();

            // Hide DataTable elements (length and filter inputs)
            $("#example_length, #example_filter").hide();

            $(".page").hide();
            $("#home").show();
            $(".pageLink").click(function (e) {
                e.preventDefault();
                const target = $(this).attr("href");
                $(".page").hide();
                const $navLinks = $(".pageLink");
                $navLinks.removeClass("active");
                $(this).addClass("active");
                $(target).show();
            });
        });
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
                link.classList.add('active');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var navbarToggleButton = document.querySelector("#navbarToggleButton");
            var icon = navbarToggleButton.querySelector('i');
            navbarToggleButton.addEventListener('click', function () {
                if (navbarToggleButton.classList.contains("collapsed")) {
                    icon.classList.remove("bi-x");
                    icon.classList.add("bi-list");
                } else {
                    icon.classList.remove("bi-list");
                    icon.classList.add("bi-x");
                }
                // var container = navbarToggleButton.closest(".container");

            });


            document.querySelectorAll('.pageLink').forEach(function(link) {
                link.addEventListener('click', function() {
                    var navbar = link.closest(".navbar-collapse");
                    navbar.classList.remove("show")
                    navbarToggleButton.classList.add("collapsed");
                    navbarToggleButton.ariaExpanded=false;
                    icon.classList.remove("bi-x");
                    icon.classList.add("bi-list");
                });
            });
        });
    </script>
    <!-- Script for editing tasks -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editButtons = document.querySelectorAll('.edit');
            // console.log(editButtons)
            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var taskRow = button.closest('.task');
                    var title = taskRow.querySelector('.title');
                    var desc = taskRow.querySelector('.desc');
                    var flexRow = button.querySelector('.d-flex .flex-row');
                    if (flexRow.innerHTML == '<i class="bi bi-check-all" style="padding-right: 5px;"></i> Done') {
                        flexRow.innerHTML = '<i class="bi bi-pencil" style="padding-right: 5px;"></i> Edit';
                        title.contentEditable = false;
                        desc.contentEditable = false;
                        var tk_id = title.getAttribute('id');
                        var newTitle = title.textContent;
                        var newDesc = desc.textContent;
                        document.getElementById("taskId").value = tk_id;
                        document.getElementById("taskname").value = newTitle;
                        document.getElementById("taskdesc").value = newDesc;
                        document.querySelector("#editTask").submit();
                    } else {
                        flexRow.innerHTML = '<i class="bi bi-check-all" style="padding-right: 5px;"></i> Done';
                        title.contentEditable = true;
                        desc.contentEditable = true;
                        title.focus()
                    }
                    title.addEventListener('blur', function () {
                        desc.focus()
                    });

                });
            });
        });
    </script>
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.7/b-2.4.2/datatables.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top navigation pb-0 ">
        <div class="container ">
            <a class="navbar-brand  navigationContent" href="https://github.com/JatinKevlani/to_do_list.git"><i
                    class="bi bi-card-checklist"></i> To Do List</a>
            <button style="color: #DAD7CD;" class="border-0 fs-1 navbar-toggler shadow-none" id="navbarToggleButton"
                type="button" data-bs-toggle="collapse" data-bs-target="#content">
                <span><i class="bi bi-list"></i></span>
            </button>
            <div class="collapse navbar-collapse navbarContent navbarTrans" id="content">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link  pageLink active navigationContent" href="#home"><i
                                class="icon bi bi-house-door"></i><i class="icon-fill bi bi-house-door-fill"></i>
                            Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  pageLink navigationContent" href="#contact"><i
                                class="icon bi bi-envelope"></i><i class="icon-fill bi bi-envelope-fill"></i>
                            Contact</a>
                    </li>
                    
                </ul>
                <?php
                if (!isset($_SESSION['user'])) {
                    echo "
                        <ul class='navbar-nav ms-auto'>
                            <li class='nav-item'><a id='signupButton' style='hover:pointer' class='nav-link loginNavLink  navigationContent' data-bs-toggle='modal' data-bs-target='#signup'><i class='icon bi bi-person-plus'></i><i class='icon-fill bi bi-person-plus-fill'></i> Signup</a></li>
                            <li class='nav-item'><a id='loginButton' style='hover:pointer' class='nav-link loginNavLink  navigationContent' data-bs-toggle='modal' data-bs-target='#login'><i class='icon bi bi-lock'></i><i class='icon-fill bi bi-lock-fill'></i> Login</a></li>
                        </ul>
                    ";
                } else {
                    echo "
                        <ul class='navbar-nav ms-auto'>
                        <li class='nav-item'>
                        <div class='btn-group'>
                            <a id='signupButton' class='nav-link loginNavLink  navigationContent' href='#'><i class='icon bi bi-person'></i><i class='icon-fill bi bi-person'></i> Hello  $user[1] !</a>
                            <button type='button' style='border:none' class='btn navigatinContent text-white dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown'
                                aria-expanded='false'>
                                <span class='visually-hidden'>Toggle Dropdown</span>
                            </button>
                            <ul class='dropdown-menu'>
                                <li><a class=' ps-2 nav-link  pageLink' href='#profile'><i
                                class='icon bi bi-person-circle'></i><i class='icon-fill bi bi-person-circle'></i>
                            My Profile</a></li>
                                <li>
                                <a id='ps-2 loginButton' class='nav-link  ' href='backend/logout.php'><i class='bi bi-box-arrow-in-right'></i><i class='icon-fill bi bi-arrow-in-right'></i> Logout</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                        </ul>
                    ";
                }
                ?>
            </div>
        </div>
    </nav>
    <main>
        <div id="home" class="row page" style="margin-top: 40px;">
            <div class="col-10 offset-1">
                <div class="container pt-5 ">
                    <div class="row">
                        <div class="d-flex flex-row align-items-center justify-content-evenly align-items-md-stretch">
                            <?php
                            if (!isset($_SESSION['user'])) {
                                echo '
                                    <div class="d-flex justify-content-center display-3 flex-grow-1 align-items-center pt-2">
                                        <p>PLEASE LOGIN!</p>
                                    </div>
                                ';
                            } else {
                                echo '
                                    <div class="d-flex justify-content-center display-3 flex-grow-1 align-items-center pt-2">
                                        <p>TASKS</p>
                                    </div>
                                    <div class="dropdown-center d-md-none">
                                        <button class="btn bg-dark text-white dropdown-toggle buttonDropdown" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bi bi-gear"></i> Actions
                                        </button>
                                        <div class="dropdownMenu dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item btn bg-dark text-white" data-bs-toggle="modal" data-bs-target="#inputModal">
                                                <i class="bi bi-plus"></i> Add
                                            </button>
                                            <button class="dropdown-item btn bg-danger text-white" onclick="delete_all();">
                                                <i class="bi bi-trash"></i> Delete All
                                            </button>
                                        </div>
                                    </div>
                                    <div class="d-none d-md-flex flex-md-column align-items-md-stretch ms-auto">
                                        <button class="px-4 btn bg-dark text-white mb-2" data-bs-toggle="modal" data-bs-target="#inputModal">
                                            <span><i class="bi bi-plus"></i></span> Add
                                        </button>
                                        <button class="px-4 btn bg-danger text-white" onclick="delete_all();">
                                            <span><i class="bi bi-trash"></i></span> Delete All
                                        </button>
                                    </div>
                                ';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Showing tasks table -->
                    <?php if (isset($_SESSION['user'])) { ?>
                        <div id="input"></div>
                        <div class="table-responsive table-bordered mainTable pt-3">
                            <table id="example" class="table table-bordered table data-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th data-sort="true" class="col-1">Sr No.</th>
                                        <th data-sort="true" class="col-3">Title</th>
                                        <th data-sort="true" class="col-5">Description</th>
                                        <th data-sort="true" class="col-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counts = 0;
                                    foreach ($tasks as $task) {
                                        $counts++;
                                        if ($task[3] == "1") {
                                            echo "                                        
                                                <tr class='task'>
                                                    <td>$counts</td>
                                                    <td contenteditable='false' class='title'><s>$task[1]</s></td>
                                                    <td contenteditable='false' class='desc'><s>$task[2]</s></td>
                                                    <td>
                                                        <div class='buttonRow d-flex flex-lg-row justify-content-around flex-md-column align-content-md-stretch py-1 flex-sm-column '>
                                                            <button class='mx-1 btn btn-danger completed' onclick='mark_not_done($task[0]);'>
                                                                <div class='d-flex flex-row'>
                                                                    <i class='bi bi-check-circle' style='padding-right: 5px;'></i> Mark&nbsp;Pending
                                                                </div>
                                                            </button>
                                                            <button class='mx-1 btn btn-warning delete' onclick='delete_task($task[0]);'>
                                                                <div class='d-flex flex-row'>
                                                                    <i class='bi bi-trash' style='padding-right: 5px;'></i> Delete
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ";
                                        } else {
                                            echo "
                                                <tr class='task'>
                                                    <td>$counts</td>                                                    
                                                    <td contenteditable='false' id='$task[0]' class='title'>$task[1]</td>
                                                    <td contenteditable='false' id='$task[0]' class='desc'>$task[2]</td>
                                                    <td>
                                                        <div class='buttonRow d-flex flex-lg-row justify-content-around flex-md-column align-content-md-stretch py-1 flex-sm-column '>
                                                            <button class='mx-1 btn btn-success completed' onclick='mark_done($task[0]);'>
                                                                <div class='d-flex flex-row'>
                                                                    <i class='bi bi-check-circle' style='padding-right: 5px;'></i> Mark&nbsp;Done
                                                                </div>
                                                            </button>
                                                            <button class='mx-1 btn btn-primary edit'>
                                                                <div class='d-flex flex-row'>
                                                                    <i class='bi bi-pencil' style='padding-right: 5px;'></i> Edit
                                                                </div>
                                                            </button>
                                                            <button class='mx-1 btn btn-warning delete' onclick='delete_task($task[0]);'>
                                                                <div class='d-flex flex-row'>
                                                                    <i class='bi bi-trash' style='padding-right: 5px;'></i> Delete
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="contact" class="row page" style="margin-top: 200px">
            <div class="col-8 offset-2 d-flex justify-content-center align-items-center">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row"
                        style="border-bottom: 1px solid black;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;">
                        <img class="img-fluid rounded-circle m-2" src="assets/images/jatin.jpg" alt="Jatin Kevlani">
                        <p class="display-1 m-3"> Jatin Kevlani </p>
                    </div>
                    <div class="d-flex flex-row"
                        style="border-top: 1px solid black;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                        <img class="img-fluid rounded-circle m-2" src="assets/images/kavan.jpg" alt="Kavan Bhavsar">
                        <p class="display-1 m-3"> Kavan Bhavsar</p>
                    </div>
                </div>
            </div>
        </div>
        <!--<div id="profile" class="row page" style="margin-top: 40px;">-->
        <!--    <pre class="display-2">rersssssserer</pre>-->
        <!--</div>-->
        <div id="profile" class="page container-fluid">
            <div class="row" style="margin-top: 100px;">
                <div class="col-md-6 offset-md-3">
                    <div class="card mt-5 fs-4">
                        <div class="card-header">
                            My Profile
                        </div>
                        <div class="card-body py-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Username:</strong>
                                    <?= $user[1] ?>
                                </li>
                                <li class="list-group-item"><strong>Email:</strong>
                                    <?= $user[2] ?>
                                </li>
                                <li class="list-group-item"><strong>Created At:</strong>
                                    <?= $user[4] ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="signup" tabindex="-1" aria-labelledby="signupLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content loginSignupContent">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="mx-auto">
                            <div class="d-flex justify-content-center">
                                <p class="display-3 pb-3"> Sign Up!</p>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="reg_userName"
                                    style="font-weight: bold;">Name:</label>
                                <div class="col-md-9">
                                    <input type="text" id="reg_userName" name="reg_userName"
                                        class="mx-md-2 form-control" required>
                                </div>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="reg_email"
                                    style="font-weight: bold;">Email:</label>
                                <div class="col-md-9">
                                    <input type="email" id="reg_email" name="reg_email" class="mx-md-2 form-control"
                                        required>
                                </div>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="reg_pswd"
                                    style="font-weight: bold;">Password:</label>
                                <div class="col-md-9">
                                    <input type="password" id="reg_pswd" name="reg_pswd" class="mx-md-2 form-control"
                                        required>
                                </div>
                            </div>
                            <div id="resultCont"></div>
                            <div class="d-flex justify-content-end pt-2">
                                <button id="submitBtn" type="button" onclick="signup()"
                                    class="btn loginSignupButton">Signup</button>
                                <p class="fs-5 text-white ms-3"> or </p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#login"
                                    class="btn alternateButton">Login</a>
                            </div>
                        </form>
                    </div>
                    <div style="display: none;">
                        <form action="backend/signup.php" method="post" id="registerForm">
                            <input id="user_Name" type="text" name="user_Name" placeholder=" Enter your username" />
                            <input id="user_Email" type="email" name="user_Email" placeholder="Enter your email"
                                id="email" />
                            <input id="user_Password" type="password" name="user_Password"
                                placeholder="Create your password" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="login" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content loginSignupContent">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="backend/login.php" method="post" class="mx-auto">
                            <div class="d-flex justify-content-center">
                                <p class="display-3 pb-3"> Login</p>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="userName"
                                    style="font-weight: bold;">Username:</label>
                                <div class="col-md-9">
                                    <input type="text" id="userName" name="userName" class="form-control" required>
                                </div>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="userPswd"
                                    style="font-weight: bold;">Password:</label>
                                <div class="col-md-9">
                                    <input type="password" id="userPswd" name="userPswd" class="form-control" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-2">
                                <button type="submit" onclick="login()" class="btn loginSignupButton">Login</button>
                                <p class="fs-5 text-white ms-3"> or </p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#signup"
                                    class="btn alternateButton">Signup</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content addTaskContent">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="backend/newtask.php" method="post" class="mx-auto">
                            <div class="d-flex justify-content-center">
                                <p class="display-3 pb-3"> Add task</p>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="task_title"
                                    style="font-weight: bold;">Title:</label>
                                <div class="col-md-9">
                                    <input type="text" id="task_title" name="task_title" class="form-control">
                                </div>
                            </div>
                            <div class="py-2 form-group row">
                                <label class="col-md-3 col-form-label" for="task_desc"
                                    style="font-weight: bold;">Description:</label>
                                <div class="col-md-9">
                                    <textarea style="min-height: 40px;resize: vertical;" id="task_desc" name="task_desc"
                                        rows="2" class="taskDescription form-control"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-2">
                                <button type="button" class="btn btn-secondary mx-1"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" onclick="addTask()" class="btn btn-dark">Add Task</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div style="display: none;">
            <form action="backend/deletetask.php" method="post" id="deleteTask">
                <textarea name="taskID" id="taskID" cols="30" rows="10"></textarea>
            </form>
        </div>
        <div style="display: none;">
            <form action="backend/markdone.php" method="post" id="markDone">
                <textarea name="taskDONEID" id="taskDONEID" cols="30" rows="10"></textarea>
            </form>
        </div>
        <div style="display: none;">
            <form action="backend/marknotdone.php" method="post" id="markNotDone">
                <textarea name="taskNOTDONEID" id="taskNOTDONEID" cols="30" rows="10"></textarea>
            </form>
        </div>
        <div style="display: none;">
            <form action="backend/edittask.php" method="post" id="editTask">
                <textarea name="taskId" id="taskId" cols="30" rows="10"></textarea>
                <textarea name="taskname" id="taskname" cols="30" rows="10"></textarea>
                <textarea name="taskdesc" id="taskdesc" cols="30" rows="10"></textarea>
            </form>
        </div>
    </main>
    <script src="js/evalidate.js"></script>
    <script src="js/tasks_funcs.js"></script>
    <script>
    // Collapse the navbar when a link is clicked
    
  </script>
</body>
<style>


    .loginSignupContent {
        padding: 20px;
        background-color: rgba(52, 78, 65, 0.455);
        backdrop-filter: blur(6px);
        color: black;
        border: 2px solid rgba(255, 255, 255, 0.49);
        border-radius: 25px;
    }

    .addTaskContent {
        padding: 20px;
        border: 2px solid rgba(52, 78, 65, 0.455);
        background-color: rgba(255, 255, 255, 0.49);
        border-radius: 10px;
        backdrop-filter: blur(6px);
    }

    /* Style for the textarea */
    .taskDescription {
        display: block;
        width: 100%;
        padding: 10px;
        font-size: 16px;
        line-height: 1.4;
        border: 1px solid #ccc;
        resize: none;
        /* Disable resizing */
        overflow-y: auto;
        /* Enable vertical scrollbar when needed */
        height: 80px;
        /* Set a fixed height for the textarea */
        max-height: 100px;
    }

    /* Customize the scrollbar */
    .taskDescription::-webkit-scrollbar {
        width: 10px;
        /* Set the width of the scrollbar */
    }

    .taskDescription::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Set the color of the scrollbar thumb */
        border-radius: 10px;
        /* Adjust the value for the rounded corners */
    }

    .taskDescription::-webkit-scrollbar-track {
        background-color: #f1f1f1;
        /* Set the color of the scrollbar track */
        /* border-bottom-left-radius: 60px; */
    }

    .taskDescription::-webkit-scrollbar-track-piece {
        background-color: #DAD7CD;
    }

    .taskDescription::-webkit-scrollbar-corner {
        background-color: #DAD7CD;
    }

    .loginSignupButton {
        background-color: rgba(88, 129, 87);
        color: black;
    }

    .loginSignupButton:hover {
        background-color: rgba(58, 90, 64);
        color: black;
    }


    .form-control {
        background-color: rgba(240, 244, 234, 0.784);
    }

    /* Optional: Adjust the focus style */
    .form-control:focus {
        box-shadow: none;
        background-color: rgba(240, 244, 234, 0.675);
        border: none;

    }

    .formContainer {
        padding: 20px;
        background-color: rgba(52, 78, 65, 0.455);
        backdrop-filter: blur(6px);
        color: black;
        width: calc(100% - 60%);
        margin: 0 30%;
        border: 2px solid rgba(255, 255, 255, 0.49);
        border-radius: 25px;
        position: fixed;
        top: 20%;
        left: 0%;
        color: black;
    }

    body {
        background-color: #DAD7CD;
    }

    .buttonDropdown {
        background-color: #588157;
        color: #DAD7CD;
    }

    .dropdownMenu {
        border: none;
        background-color: rgba(81, 130, 100, 0.777);
        width: 100px;
        padding: 2px !important;
        border-radius: 5px;
    }

    .dropdownMenu button {
        margin-bottom: 1px;
        height: 36px;
        border-radius: 5px;
        text-align: center;
    }

    .slide-down {
        animation: slide-down 0.5s forwards;
    }

    .slide-up {
        animation: slide-up 0.5s forwards;
    }

    @keyframes slide-down {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slide-up {
        from {
            transform: translateY(0);
            opacity: 1;
        }

        to {
            transform: translateY(-100%);
            opacity: 0;
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 992px) {
        div.d-flex.flex-lg-row.justify-content-around.flex-md-column.align-content-md-stretch.py-1 button {
            margin: 1px 0px;
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 768px) {
        .formContainer {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.114) !important;
            backdrop-filter: blur(9px);
            color: black;
            width: calc(100% - 30%);
            margin: 0 15%;
            border: 2px solid rgba(255, 255, 255, 0.49);
            border-radius: 25px;
            position: fixed;
            top: 20%;
            left: 0%;
            color: black;
        }

    }
    .active {
        background-color: #DAD7CD;
        color: #588157;
        border-radius: 5px 5px 0 0;

    }

    @media only screen and (max-width: 992px) {
        .active {
            background-color: #DAD7CD;
            color: #588157;
            border-radius: 5px;
            padding-left: 12px;
        }
    }

    @media only screen and (min-width:1px)and (max-width: 575px) {
        .formContainer {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.114) !important;
            backdrop-filter: blur(9px);
            color: black;
            width: calc(100% - 14%);
            margin: 0 7%;
            border: 2px solid rgba(255, 255, 255, 0.49);
            border-radius: 25px;
            position: fixed;
            top: 20%;
            left: 0%;
            color: black;
        }

        div.d-flex.flex-lg-row.justify-content-around.flex-md-column.align-content-md-stretch.py-1.flex-sm-column {
            flex-direction: column;
        }
    }

    .nav-link {
        transition: color 0.3s ease-out;
    }
</style>
</html>