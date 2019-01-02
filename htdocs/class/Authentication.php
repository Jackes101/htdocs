<?php


class Authentication
{
    static private $instance = NULL;
    static private $identity = NULL;

    private $conn = null;


    static function getInstance() : Authentication
    {
        if (self::$instance == NULL) {
            self::$instance = new Authentication();
        }
        return self::$instance;
    }

    private function __construct()
    {
        if (isset($_SESSION['identity'])) {
            self::$identity = $_SESSION['identity'];
        }
        $this->conn = Connection::getPdoInstance();

    }

    public function login(string $email, string $password) : bool
    {
        $stmt = $this->conn->prepare("SELECT id, username, email FROM users WHERE email= :email and password = :password");
        $stmt->bindParam(':email', $_POST["loginMail"]);
        $stmt->bindParam(':password', $_POST["loginPassword"]);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $userDto = array('user_id' => $user['id'], 'username' => $user['username'], 'email' => $user['email']);
            $_SESSION['identity'] = $userDto;
            self::$identity = $userDto;
            return true;
        } else {
            return false;
        }
    }

    public function hasIdentity() : bool
    {
        if (self::$identity == NULL) {
            return false;
        }
        return true;
    }

    public function getIdentity()
    {
        if (self::$identity == NULL) {
            return false;
        }
        return self::$identity;
    }

    public function logout()
    {
        unset($_SESSION['identity']);
        $_SESSION = array();
        session_destroy();
        self::$identity = NULL;
    }
}