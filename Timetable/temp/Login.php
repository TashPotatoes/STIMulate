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
        session_start();
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    private function dologinWithPostData()
    {
    if (empty($_POST['user_name'])) {
        $this->errors[] = "Username field was empty.";
    } elseif (empty($_POST['user_password'])) {
        $this->errors[] = "Password field was empty.";
    } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            $user_name = real_escape_string($_POST['user_name']);
            echo "CONNECTED TO DB YAY";
            $sqlObject = new \php\SqlObject("SELECT * FROM facilitators 
                                WHERE student_id = :id AND stu_name_last = :name", array($user_name, $user_password));
            $loginCheck = $sqlObject->Execute();

            if ($loginCheck->rowCount() == 1) {
                echo "RESULT EXISTS";
                $result_row = $result_of_login_check->fetch_object();

                if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

                    $_SESSION['user_name'] = $result_row->user_name;
                    $_SESSION['user_email'] = $result_row->user_email;
                    $_SESSION['user_login_status'] = 1;

                } else {
                    $this->errors[] = "Wrong password. Try again.";
                }
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
            return true;
        }
        // default return
        return false;
    }
}
