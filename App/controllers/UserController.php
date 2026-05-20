<?php
namespace App\controllers;

use Framework\Database; 
use Framework\Validation;
use Framework\Session;


class UserController {
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');

        $this->db = new Database($config);
    }

    public function login()
    {
        \loadView('users/login');
    }




     public function create()
    {
        \loadView('users/create');
    }


    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
            $city = $_POST['city'] ?? '';
            $state = $_POST['state'] ?? ''; 
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? ''; 

$errors = [];

if(!Validation::email($email)){
    $errors['email'] = 'Please enter a valid email address';
    }

if(!Validation::string($name, 2, 50)){
    $errors['name'] = 'Name must be between 2 and 50 characters';
    }

if(!Validation::string($password, 6, 50)){
    $errors['password'] = 'Password must be at least 6 characters';
    }


if(!Validation::match($password, $passwordConfirmation)){
    $errors['password_confirmation'] = 'Passwords do not match';
    }


    if(!empty($errors)) {
        \loadView('users/create', [
            'errors' => $errors,
            'user' => [
                'name' => $name,
                'email' => $email,
                'city' => $city,
                'state' => $state
            ]
        ]);
        exit;
    }

    $params = [
    'email' => $email,
];

$user = $this->db->query(
    'SELECT * FROM users WHERE email = :email',
    $params
)->fetch();

if ($user) {

    $errors['email'] = 'Email is already registered';

    loadView('users/create', [
        'errors' => $errors
    ]);

    exit;
}

$params = [
    'name' => $name,
    'email' => $email,
    'city' => $city,
    'state' => $state,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$this->db->query(
    'INSERT INTO users (name, email, city, state, password)
     VALUES (:name, :email, :city, :state, :password)',
    $params
);

$userId = $this->db->conn->lastInsertId();
Session::set('user',[
    'id' => $userId,
    'name' => $name,
    'email' => $email,
    'city' => $city,
    'state' => $state
]);

redirect('/');
    }

public function logout()
{
    \Framework\Session::remove('user'); // remove only user

    \Framework\Session::destroy(); // destroy full session

    redirect('/');
}

public function authenticate() {
    $email = $_POST['email'];
    $password = $_POST['password'];

$errors = [];

if(!Validation::email
($email)) {
    $errors['email'] = 'Please enter a valid email address';
    }

if(!Validation::string
($password, 6, 50)) {
    $errors['password'] = 'Password must be at least 6 characters';
    }

if(!empty($errors)) {
    loadView('users/login', [
        'errors' => $errors
    ]);
    exit;
}
$params = [
    'email' => $email
];

$user = $this->db->query(
    'SELECT * FROM users WHERE email = :email',
    $params
)->fetch();

if(!$user) {
    $errors['email'] = 'Incorrect 
    Credentials';
    loadView('users/login', [
    'errors' => $errors
]);
exit;
}

if (!password_verify($password, $user->password)) {

    $errors['password'] = 'Incorrect credentials';

    loadView('users/login', [
        'errors' => $errors
    ]);

    exit;
}

Session::set('user', [
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'city' => $user->city,
    'state' => $user->state
]);

redirect('/');


}
}