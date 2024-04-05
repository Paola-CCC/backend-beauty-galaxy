<?php 

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\BrandsManager;
use App\Service\HttpRequest;

class BrandsController 
{

    private $brandsManager = null;
    private $httpRequest ;

    public function __construct()
    {
        $this->brandsManager = new BrandsManager('brands');
        $this->httpRequest = new HttpRequest();
    }

    public function getAllBrands() 
    {
        return json_encode($this->brandsManager->findAll());

    }


    public function getById(?int $id) 
    {
        return json_encode($this->brandsManager->findBrandBydId($id));

    }

    public function newBrands() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->brandsManager->addBrand($data['name']));

    }


    public function updateBrands() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->brandsManager->updateBrand($data['name'] ,$data['id']));

    }


    public function deleteBrands(?int $id) 
    {
        return json_encode($this->brandsManager->delete($id));
    }

}