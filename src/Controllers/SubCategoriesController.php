<?php 

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\SubCategoriesManager;
use App\Service\HttpRequest;

class SubCategoriesController 
{

    private $subCategoriesManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->subCategoriesManager = new SubCategoriesManager('sub_categories');
        $this->httpRequest = new HttpRequest();
    }

    //OK
    public function getAllSubCategories() 
    {
        return json_encode($this->subCategoriesManager->findAll());
    }

    //OK
    public function getById(?int $id) 
    {
        return json_encode($this->subCategoriesManager->findSubCategoriesBydId($id));
    }

    //OK
    public function newSubCategories() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->subCategoriesManager->addSubCategorie($data['name'],$data['category_id']));
    }

    //OK
    public function updateSubCategories() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->subCategoriesManager->updateSubCategories($data['name'] ,$data['id'] , $data['category_id']));
    }

    //OK
    public function deleteSubCategories(?int $id) 
    {
        return json_encode($this->subCategoriesManager->delete($id));
    }

}