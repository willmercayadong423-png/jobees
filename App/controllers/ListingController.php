<?php

namespace App\Controllers;

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


    $requiredFields = ['title', 'description', 'email', 'city', 'state'];

    $error = [];

    foreach ($requiredFields as $field) {
        if (empty($newListingData[$field]) ||
        !Validation::string($newListingData
        [$field])) {
            $error[$field] = ucfirst($field) .
             ' is required';
        }    
       
    }

if(!empty($errors)) {
    loadView('listings/create', [
        'errors' => $errors,
        'listing' => $newListingData
    ]);
 }else {

  echo "Success"; 
}

  
}
}