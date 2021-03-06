<?php

/**
 * Class login
 * handles the user's login and logout process
 */
class UserAccessControl
{
    private $timetoutTime = 3000;
    private $db_connection = null;
    public $errors = array();
    public $messages = array();
    public $loop = 0;
    public $requiresPasswordReset = false;
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
        session_start();
        }
        if (isset($_GET["logout"])) {
            echo "nope";
            $this->doLogout();
        }
        elseif (isset($_POST['login'])) {
            switch (substr($_POST['login_input_username'],0,1)) {
                case 'n':
                    $this->doFacilitatorLogin();
                    break;
                case 's':
                    $this->doStaffLogin();
                    break;
                default:
                    $this->errors[] = "Incorrect credentials";
                    break;
            }
        }
    }


    private function doFacilitatorLogin()
    {
    if (empty($_POST['login_input_username'])) {
        $this->errors[] = "Username field was empty.";
    } elseif (empty($_POST['login_input_password'])) {
        $this->errors[] = "Password field was empty.";
    } elseif (!empty($_POST['login_input_username']) && !empty($_POST['login_input_password'])) {

            $user_name = $_POST['login_input_username'];
            $password = $_POST['login_input_password'];

            $sqlObject = new \php\SqlObject("SELECT student_id, active, stu_name_first FROM facilitators 
                                WHERE student_id = :id AND stu_name_last = :name", array($user_name, $password));

            $loginCheck = $sqlObject->Execute();

            if (count($loginCheck)) {
                if($loginCheck[0]['active']) {
                    $userType = "student";
                    $result_row = $loginCheck;

                    $_SESSION['user_id'] = $loginCheck[0]['student_id'];
                    $_SESSION['user_login_status'] = 1;
                    $_SESSION['user_name'] = $loginCheck[0]['stu_name_first'];
                    $_SESSION['user_type'] = $userType;
                } else {
                    $this->errors[] = "This user is not active";
                }
            } else {
                $this->errors[] = "This user does not exist.";
            }
        } else {
            $this->errors[] = "Database connection problem.";

        }
    }

    private function doStaffLogin() {
   if (empty($_POST['login_input_username'])) {
        $this->errors[] = "Username field was empty.";
    } elseif (empty($_POST['login_input_password'])) {
        $this->errors[] = "Password field was empty.";
    } elseif (!empty($_POST['login_input_username']) && !empty($_POST['login_input_password'])) {

            $user_name = $_POST['login_input_username'];
            $password = $_POST['login_input_password'];
            $sqlObject = new \php\SqlObject("SELECT staff_id, staff_name_first, passReset FROM STIMulate.staff
                                WHERE staff_id = :id AND staff_password = MD5(:pass)", array($user_name, $password));
            $loginCheck = $sqlObject->Execute();

            if (count($loginCheck)) {
                $userType = "staff";
                $result_row = $loginCheck;
                $_SESSION['user_id'] = $loginCheck[0]['staff_id'];
                $_SESSION['user_login_status'] = 1;
                $_SESSION['user_name'] = $loginCheck[0]['staff_name_first'];
                $_SESSION['user_type'] = $userType;
                $_SESSION['requires_reset'] = $loginCheck[0]['passReset'] == '1';

            } else {
                $this->errors[] = "This user does not exist.";
            }
        } else {
            $this->errors[] = "Database connection problem.";

        }
    }
    public function checkTimeout() {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $this->timetoutTime)) {
            session_unset();     
            session_destroy();
            header("Location: Global_Gateway.php?ref=timeout");
        }
        $_SESSION['last_activity'] = time(); 
    }
    
    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
       // $_SESSION = array();
        unset($_SESSION['user_id']);
        $_SESSION['user_login_status'] = 0;

        session_destroy();
        // return a little feeedback message
        $this->messages[] = "You have been logged out.";

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
