<?php


namespace App\Controllers;

use App\Managers\BrandsManager;
use App\Managers\CategoriesManager;
use App\Managers\ProductsManager;
use App\Managers\SubCategoriesManager;
use App\Service\HttpRequest;

class ProductsController 
{

    private $productsManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->productsManager = new ProductsManager('products');
        $this->httpRequest = new HttpRequest();
    }


    public function getAllProducts()
    {
        http_response_code(200);
        return json_encode($this->productsManager->findAll());
    }


    public function getAllCriterias()
    {

        $brandsManager = new BrandsManager('brands');
        $categoriesManager = new CategoriesManager('categories');
        $subCategoriesManager = new SubCategoriesManager('sub_categories');

        return json_encode([
            'brands' => $brandsManager->findAll(),
            'categories' => $categoriesManager->findAll(),
            'subCategories' =>  $subCategoriesManager->findAll()
        ]);
    }

    /**
    * @param int $int
    */
    public function getProductByID(?int $id)
    {
        http_response_code(200);
        return json_encode($this->productsManager->findById($id));
    }

    public function getLatestProducts()
    {
        http_response_code(200);
        return json_encode($this->productsManager->findLatestProduct());
    }


    public function getPopularProducts()
    {
        http_response_code(200);
        return json_encode($this->productsManager->findFourMostPopularProduct());
    }


    /**
    * @param int $int
    */
    public function getProductByBrandID(?int $id)
    {
        http_response_code(200);
        return json_encode($this->productsManager->findByBrandId($id));
    }

    /**
    * @param int $int
    */
    public function getProductByCategorydID(?int $id) 
    {
        http_response_code(200);
        return json_encode($this->productsManager->findByCategorydId($id));
    }

    public function filterProductByName(string $name) 
    {
        echo " name ". $name ;
        return json_encode($this->productsManager->allFilterName($name));
    }


    public function newProduct() 
    {
        $data = $this->httpRequest->getBody();

        $productsDatas = [
            'brand_id' => $data['brand_id'],
            'name' => $data['name'],
            'descriptionShort' => $data['descriptionShort'],
            'descriptionLong' => $data['descriptionLong'],
            'thumbnail' => $data['thumbnail'],
            'quantity' => $data['quantity'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'tagsId' => $data['tagsId'],
            'subCategories' => $data['subCategories']
        ];

        $productRows = $this->productsManager->findByBrandName($productsDatas)->fetchAll();

        if( count($productRows) === 0) {

            if($this->productsManager->createProduct($productsDatas) ) {
                return json_encode([
                    "message" => 'Le produit a bien été enregistré'
                ]);
            } else {
                return json_encode([
                    "error message" => "Le produit n'a pas pu être enregistré"
                ]);
            }


        } else {

            return json_encode([
                "message" => 'Le produit existe déjà'
            ]);
        }

    }

    public function updateProduct() 
    {
        $data = $this->httpRequest->getBody();
        $productsDatas = [
            'id' => (int) $data['id'],
            'brand_id' => $data['brand_id'],
            'name' => $data['name'],
            'descriptionShort' => $data['descriptionShort'],
            'descriptionLong' => $data['descriptionLong'],
            'thumbnail' => $data['thumbnail'],
            'quantity' => $data['quantity'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'tagsId' => $data['tagsId'],
            'subCategories' => $data['subCategories']
        ];

        return json_encode($this->productsManager->updateProduct($productsDatas));
    }

    public function searchProduct()
    {
        $data = $this->httpRequest->getBody();
        $productsDatas = [
            'brand_id' => (int) $data['brandId'] ?? null,
            'category_id' => (int) $data['categoryId'] ?? null,
            'price_min' => (int) $data['minPrice'] ?? null,
            'price_max' => (int) $data['maxPrice'] ?? null,
            'subCategory_Id' => (int) $data['subCategoryId'] ?? null
        ];

        return json_encode($this->productsManager->search($productsDatas));

    }

    public function delete(?int $id) 
    {
        http_response_code(200);
        return json_encode($this->productsManager->delete((int) $id));
    }

}