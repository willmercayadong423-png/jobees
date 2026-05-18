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
        inspectAndDie(Validation::match('test1', 'test2'));

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
}