<?php
namespace App\Controllers;

use App\Controllers\ErrorController;
use Framework\Database;
use Framework\Validation;
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
            ->query('SELECT * FROM listings')
            ->fetchAll(PDO::FETCH_OBJ);

       loadView('listings/index', [
    'listings' => $listings
]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show()
{
    $id = $_GET['params'][0] ?? null;

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
    
    $newListingData['user_id'] = 1; // Assuming user ID is 1 for now, replace with actual user ID in production
    
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


 $fields = [];
 foreach ($newListingData as $field => $value) {
    $fields[] = $field;
 }
//    $this->db->query('INSERT INTO listings 
//         (title, description, salary, tags,
//          company, address, city, state, phone,
//          email, requirements, benefits, user_id) 
//          VALUES (:title, :description, :salary,
//          :tags, :company, :address, :city,
//          :state, :phone, :email, :requirements,
//          :benefits, :user_id)', $newListingData);





 $fields = implode(', ', $fields);
 
 $value = [];
    foreach ($newListingData as $field => $value) {
        if ($value === '') {
        $newListingData[$field] = null;
        }
        $values[] = ':' . $field;
    }
    $values = implode(', ', $values);

$query = "INSERT INTO listings ($fields) VALUES ($values)";

$this->db->query($query, $newListingData);




redirect('/listings');





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

    if (!$listing) {
        ErrorController::notFound('Listing not found');
        return;
    }

    $this->db->query('DELETE FROM listings WHERE id = :id', $params);

    $_SESSION['success_message'] = 'Listing deleted successfully';

    redirect('/listings');
}
  
}