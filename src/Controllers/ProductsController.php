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
    private array $allProducts ;

    public function __construct()
    {
        $this->productsManager = new ProductsManager('products');
        $this->httpRequest = new HttpRequest();
    }

    // Tranfome le Json contenant en tableau et calcule la moyenne de note 
    public function getProductsNotes(array $productsList)
    {

        $datas = [];

        foreach ($productsList as  $value) {
            $notes = json_decode('[' . $value['notes_product'] . ']', true);
            $productNotes = 0;
            $counter = count($notes);
             if ($counter > 0) {
                foreach ($notes as $valuesNotes) {
                    $productNotes += $valuesNotes["notes"] / $counter;
                }
             }

            $datas [] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "descriptionShort" => $value["descriptionShort"],
                "descriptionLong" => $value["descriptionLong"],
                "thumbnail" => $value["thumbnail"],
                "quantity" => $value["quantity"],
                "createdAt" => $value["createdAt"],
                "price" => $value["price"],
                "brandName" => $value["brandName"],
                "categories" =>  $value["categories"],
                "notes" => $productNotes,                
            ];

        }

        return $datas;
    }

    public function getAllProducts()
    {

        $productsList = $this->productsManager->findAll();
        $this->allProducts = $this->getProductsNotes($productsList);

        return json_encode($this->allProducts);
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
        $productsList = $this->productsManager->findById($id);
        $this->allProducts = $this->getProductsNotes([$productsList]);
        return json_encode($this->allProducts[0]);
    }

    public function getLatestProducts()
    {

        $productsList = $this->productsManager->findAll();
        $this->allProducts = $this->getProductsNotes($productsList);
        return json_encode($this->allProducts);
    }


    public function getPopularProducts()
    {

        $productsList = $this->productsManager->findFourMostPopularProduct();
        $this->allProducts = $this->getProductsNotes($productsList);
        return json_encode($this->allProducts);
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

        // return json_encode($this->productsManager->search($productsDatas));

        $productsList = $this->productsManager->search($productsDatas);
        $this->allProducts = $this->getProductsNotes($productsList);
        return json_encode($this->allProducts);

    }

    public function delete(?int $id) 
    {
        http_response_code(200);
        return json_encode($this->productsManager->delete((int) $id));
    }

}