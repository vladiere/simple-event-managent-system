<?php 
    session_start();
    require './backend.php';
    
    if (isset($_POST["choice"])) {
        $back = new backend();
        switch ($_POST["choice"]) {
            case 'login':
                echo $back->login($_POST["username"], $_POST["password"]);
                break;
            case 'register':
                echo $back->register($_POST["fullname"],$_POST["username"], $_POST["password"]);
                break;
            case 'displayAccDetails':
                echo $back->displayAccount();
                break;
            case 'logout':
                session_unset();
                session_destroy();
                echo '200';
                break;
            case 'addevent':
                echo $back->addEvents($_POST["eventname"], $_POST["eventstart"], $_POST["eventend"]);
                break;
            case 'displayevents':
                echo $back->displayEvents();
                break;
            case 'deleteevent':
                echo $back->deleteEvent($_POST["eventID"]);
                break;
            case 'changepassword':
                echo $back->changePassword($_POST["newpass"]);
                break;
            case 'changeusername':
                echo $back->changeUsername($_POST["newuser"]);
                break;
            case 'changefullname':
                echo $back->changeFullname($_POST["newname"]);
                break;
            default:
                # code...
                break;
        }
    }

?>