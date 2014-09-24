<?php

/**
 * Class login
 * handles the user's login and logout process
 */
class Login
{

    private $db_connection = null;
    public $errors = array();
    public $messages = array();

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
        session_start();
        }        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    private function dologinWithPostData()
    {
    if (empty($_POST['login_input_username'])) {
        $this->errors[] = "Username field was empty.";
    } elseif (empty($_POST['login_input_password'])) {
        $this->errors[] = "Password field was empty.";
    } elseif (!empty($_POST['login_input_username']) && !empty($_POST['login_input_password'])) {

            $user_name = $_POST['login_input_username'];
            $password = $_POST['login_input_password'];
            $sqlObject = new \php\SqlObject("SELECT * FROM facilitators 
                                WHERE student_id = :id AND stu_name_last = :name", array($user_name, $password));
            $loginCheck = $sqlObject->Execute();
var_dump($loginCheck);

            if (count($loginCheck)) {
                echo "CORRECT CREDENTIALS";
                $result_row = $loginCheck;

                $_SESSION['facilitator_id'] = $loginCheck[0]['student_id'];
                $_SESSION['user_login_status'] = 1;

            } else {
                $this->errors[] = "This user does not exist.";
            }
        } else {
            $this->errors[] = "Database connection problem.";

        }
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
        $_SESSION = array();
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
            echo "USER LOGGED IN";
            return true;
        }
        // default return
        return false;
    }
}
