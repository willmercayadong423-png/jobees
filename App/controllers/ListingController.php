<?php
namespace App\Controllers;

use App\Controllers\ErrorController;
use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;
use PDO;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');

        $this->db = new Database($config);
    }

    public function index()
    {
   

        $listings = $this->db
            ->query('SELECT * FROM listings ORDER BY created_at DESC')
            ->fetchAll(PDO::FETCH_OBJ);

       loadView('listings/index', [
    'listings' => $listings
]);
    }

    public function create()
    {
        loadView('listings/create');
    }

   public function show($params)
{
    $id = $params[0] ?? null;

    if (!$id) {
        die('Invalid ID');
    }

    $listing = $this->db
        ->query(
            'SELECT * FROM listings WHERE id = :id',
            ['id' => $id]
        )
        ->fetch(PDO::FETCH_OBJ);

    if (!$listing) {
        die('Listing not found');
    }

    loadView('listings/show', [
        'listing' => $listing
    ]);
}
public function store(){
   $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

    
    $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
    
    $newListingData['user_id'] = $_SESSION['user']['id'];
    
    $newListingData = array_map('sanitize', 
    $newListingData);


    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

   $errors = [];

foreach ($requiredFields as $field) {
    if (empty($newListingData[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
    }
}

if (!empty($errors)) {
    loadView('listings/create', [
        'errors' => $errors,
        'listing' => $newListingData
    ]);
} else {

$fields = array_keys($newListingData);

$values = array_map(fn($field) => ':' . $field, $fields);

$fields = implode(', ', $fields);

$values = implode(', ', $values);

$query = "INSERT INTO listings ($fields) VALUES ($values)";
  Session::setFlashMessage('success_message', 'Listing created successfully');
   


$this->db->query($query, $newListingData);

$id = $this->db->conn->lastInsertId();

redirect('/listings');
exit;



    // $newListingData['salary'] = !empty($newListingData['salary'])
    //     ? (float) $newListingData['salary']
    //     : 0;

 

}
}
public function destroy($params)
{
    $id = $params[0] ?? null;

    if (!$id) {
        ErrorController::notFound('Listing not found');
        return;
    }

    $params = ['id' => $id];

    $listing = $this->db
        ->query('SELECT * FROM listings WHERE id = :id', $params)
        ->fetch(PDO::FETCH_OBJ);


//check if listing exists before deleting

    if (!$listing) {
        ErrorController::notFound('Listing not found');
        return;
    }

//authorization

if(!Authorization::isOwner($listing->user_id)) {
   
    Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
    return redirect('/listings/' . $listing->id);
}







    $this->db->query('DELETE FROM listings WHERE id = :id', $params);


  Session::setFlashMessage('success_message', 'Listing deleted successfully');
   
    redirect('/listings');
}



public function edit()
{
  

    $id = (int) ($_GET['params'][0] ?? 0);

    if ($id <= 0) {
        die('Invalid ID');
    }

    $listing = $this->db
        ->query(
            'SELECT * FROM listings WHERE id = :id',
            ['id' => $id]
        )
        ->fetch(PDO::FETCH_OBJ);

    if (!$listing) {
        die('Listing not found');
    }

    loadView('listings/edit', [
        'listing' => $listing
    ]);
}

public function update($params)
{
    $id = $params[0] ?? null;

    if (!$id) {
        die('Invalid ID');
    }

    $listing = $this->db
        ->query(
            'SELECT * FROM listings WHERE id = :id',
            ['id' => $id]
        )
        ->fetch(PDO::FETCH_OBJ);

    if (!$listing) {
        die('Listing not found');
    }

    $allowedFields = [
        'title', 'description', 'salary', 'tags', 'company',
        'address', 'city', 'state', 'phone', 'email',
        'requirements', 'benefits'
    ];

    $updatedValues = array_intersect_key($_POST, array_flip($allowedFields));
    $updatedValues = array_map('sanitize', $updatedValues);

    $setString = implode(', ', array_map(
        fn($field) => "$field = :$field",
        array_keys($updatedValues)
    ));

    $updatedValues['id'] = $id;
    

    $query = "UPDATE listings SET $setString WHERE id = :id";

$stmt = $this->db->query($query, $updatedValues);

if ($stmt->rowCount() > 0) {
    Session::setFlashMessage('success_message', 'Listing updated successfully');
} else {
    Session::setFlashMessage('error_message', 'No changes were made');
}

redirect('/listings');
exit;



}
}