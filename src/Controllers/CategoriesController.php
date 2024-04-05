<?php 

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\CategoriesManager;
use App\Service\HttpRequest;

class CategoriesController 
{

    private $categoriesManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->categoriesManager = new CategoriesManager('categories');
        $this->httpRequest = new HttpRequest();
    }

    public function getAllCategories() 
    {
        return json_encode($this->categoriesManager->findAll());
    }


    public function getById(?int $id) 
    {
        return json_encode($this->categoriesManager->findCategoriesBydId($id));
    }

    public function newCategories() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->categoriesManager->addCategorie($data['name']));
    }


    public function updateCategories() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->categoriesManager->updateCategorie($data['name'] ,$data['id']));
    }

    public function deleteCategories(?int $id) 
    {
        return json_encode($this->categoriesManager->delete($id));
    }

}