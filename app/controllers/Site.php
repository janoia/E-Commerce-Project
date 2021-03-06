<?php
class Site extends Controller
{
    public function __construct()
    {
        $this->loginModel = $this-> model('loginModel');
    }

    public function index()
    {
            $this->view('Site/MainPage');       
    }

    public function Settings()
    {
        $this->view('Site/Settings');  
    }

    public function AdminPage()
    {
        $this->view('Site/AdminPage');  
    }

    public function CartPage()
    {
        $this->view('Site/CartPage');  
    }

    public function item()
    {
        $this->view('Site/item');  
    }

    public function Search()
    {

        if(!isset($_POST['input'])){
        $data=[
                'categories' => trim($_POST['categories']),
            ];

        }else{
            $data=[
                'inputs' => trim($_POST['input']),
            ];
            

        }
      
        $this->view('Site/Search',$data);  
    }

    public function login(){
        if(!isset($_POST['login'])){
            $this->view('Site/login');
        }
        else{
            $user = $this->loginModel->getUser($_POST['username']);
            
            if($user != null){
                $hashed_pass = $user->pass_hash;
                $password = $_POST['password'];
                if(password_verify($password,$hashed_pass)){
                    //echo '<meta http-equiv="Refresh" content="2; url=/MVC/">';
                    $this->createSession($user);
                    $data = [
                        'msg' => "Welcome, $user->username!",
                    ];
                    $this->view('Site/MainPage',$data);
                }
                else{
                    $data = [
                        'msg' => "Password incorrect! for $user->username",
                    ];
                    $this->view('Site/login',$data);
                }
            }
            else{
                $data = [
                    'msg' => "User: ". $_POST['username'] ." does not exists",
                ];
                $this->view('Site/login',$data);
            }
        }
    }

    public function signup(){
if(!isset($_POST['signup'])){
            $this->view('Site/signup');
        }
        else{
            $user = $this->loginModel->getUser($_POST['username']);
            if($user == null){
                $data = [
                    'username' => trim($_POST['username']),
                    'pass_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                ];
                if($this->loginModel->createUser($data)){
                        echo 'Please wait creating the account for '.trim($_POST['username']);
                        echo '<meta http-equiv="Refresh" content="2; url=/MVC/Login/">';
                }
            }
            else{
                $data = [
                    'msg' => "User: ". $_POST['username'] ." already exists",
                ];
                $this->view('Site/signup',$data);
            }
            
        }
    }

    public function createSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
    }

    public function logout(){
        unset($_SESSION['user_id']);
        session_destroy();
        echo '<meta http-equiv="Refresh" content="1; url=/MVC/Login/">';
    }

    public function ban($user_id)
    {
        $data=[
                'ID' => $user_id
            ];

            $this->userModel->ban($data)
    }

    public function ban($user_id)
    {
        $data=[
                'ID' => $user_id
            ];

            $this->userModel->unban($data)
    }

    public function AdminUser()
    {
        $users = $this->userModel->getUsers();
        $banned = $this->userModel->getbannedUsers();
            $data = [
                "users" => $users
                "BannedUsers" => $banned
            ];
            $this->view('Site/AdminUser',$data);
    }
}