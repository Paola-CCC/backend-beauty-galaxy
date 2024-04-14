<?php
declare(strict_types=1);

namespace App\Routing;


class Routes
{
    public array $routes = [];
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_PUT = 'PUT';
    private const METHOD_DELETE = 'DELETE';

    public function __construct() {

        /** Clients */
        $this->post('/register',['clients' => 'register']);
        $this->post('/login',['clients' => 'login']);
        $this->get('/clients',['clients' => 'all']);
        $this->get('/clientsAllDetails',['clients' => 'allDetails']);
        $this->put('/client-update',['clients' => 'update']);
        $this->get('/clients-show/:id',['clients' => 'show']);
        $this->delete('/clients-delete/:id',['clients' => 'delete']);

        /** PRODUITS */
        $this->get('/products',['products' => 'getAllProducts']);
        $this->get('/latest-products',['products' => 'getLatestProducts']);
        $this->get('/popular-products',['products' => 'getPopularProducts']);
        $this->get('/products-show/:id',['products' => 'getProductByID']);
        $this->get('/productsByCategoryID?id={:id}',['products' => 'getProductByCategorydID']);
        $this->post('/new-products',['products' => 'newProduct']);
        $this->put('/update-products',['products' => 'updateProduct']);
        $this->delete('/product-delete/:id',['products' => 'delete']);

        /** Marque */
        $this->get('/brands',['brands' => 'getAllBrands']);
        $this->get('/brands-show/:id',['brands' => 'getById']);
        $this->post('/new-brands',['brands' => 'newBrands']);
        $this->put('/update-brands',['brands' => 'updateBrands']);
        $this->delete('/delete-brands/:id',['brands' => 'deleteBrands']);

        // Tags
        $this->get('/tags',['tags' => 'getAlltags']);
        $this->get('/tags-show/:id',['tags' => 'getById']);
        $this->post('/new-tags',['tags' => 'newTag']);
        $this->put('/update-tags',['tags' => 'updateTag']);
        $this->delete('/delete-tags/:id',['tags' => 'deleteTag']);

        // Categories
        $this->get('/categories',['categories' => 'getAllcategories']);
        $this->get('/categories-show/:id',['categories' => 'getById']);
        $this->post('/new-categories',['categories' => 'newCategories']);
        $this->put('/update-categories',['categories' => 'updateCategories']);
        $this->delete('/delete-categories/:id',['categories' => 'deleteCategories']);

        // Sous Catégories
        $this->get('/subCategories',['subCategories' => 'getAllSubCategories']);
        $this->get('/subCategories-show/:id',['subCategories' => 'getById']);
        $this->post('/new-subCategories',['subCategories' => 'newSubCategories']);
        $this->put('/update-subCategories',['subCategories' => 'updateSubCategories']);
        $this->delete('/delete-subCategories',['subCategories' => 'deleteSubCategories']);

        //Reviews
        $this->get('/reviews',['reviews' => 'getAllreviews']);
        $this->get('/reviews-show/:id',['reviews' => 'getById']);
        $this->post('/new-reviews',['reviews' => 'newReviews']);
        $this->put('/update-reviews',['reviews' => 'updateReviews']);
        $this->delete('/delete-reviews/:id',['reviews' => 'deleteReviews']);

        //Notes
        $this->get('/notes',['notes' => 'getAllNotes']);
        $this->get('/notes-show/:id',['notes' => 'getById']);
        $this->post('/new-notes',['notes' => 'newNotes']);
        $this->delete('/delete-notes/:id',['notes' => 'deleteNotes']);

        // CART ITEM
        $this->get('/cartItems',['cartItems' => 'getAllCartItems']);
        $this->get('/cartItems-show/:id',['cartItems' => 'getById']);
        $this->post('/new-cartItems',['cartItems' => 'newCartItems']);
        $this->put('/update-cartItems',['cartItems' => 'updateCartItems']);
        $this->delete('/delete-cartItems/:id',['cartItems' => 'deleteCartItems']);

    }
    
    /**
    * @param string $path
    * @param mixed $handler
    */
    function get(string $path, $handler)
    {
        $this->addRoutes(self::METHOD_GET, $path, $handler);
    }

    /**
    * @param string $path
    * @param mixed $handler
    */
    function post(string $path, $handler)
    {
        $this->addRoutes(self::METHOD_POST, $path, $handler);
    }

    /**
    * @param string $path
    * @param mixed $handler
    */
    function put(string $path, $handler)
    {
        $this->addRoutes(self::METHOD_PUT, $path, $handler);
    }

    /**
    * @param string $path
    * @param mixed $handler
    */
    function delete(string $path, $handler)
    {
        $this->addRoutes(self::METHOD_DELETE, $path, $handler);
    }

    /**
    * @param string $method
    * @param string $path
    * @param mixed $handler
    */
    function addRoutes(string $method, string $path, $handler)
    {

        $data = $this->getPattern($path);
        $this->routes[] = [
            'path' => $data["path"],
            'pattern' => $data["regExReplace"],
            'method' => $method,
            'handler' => $handler
        ];
    }

    /**
    * @param string $path
    */
    function getPattern(string $path)
    {    

        $routePrefix = strstr($path, '?', true);
        $subject = $path;
        $pattern = "/\/:(\w+)/";

        if (preg_match("/=({:id})/", $subject, $matches)) {
            return [
                'path' => $routePrefix,
                'regExReplace' => '(\d+)'
            ];
        } else if( preg_match($pattern, $subject)) {
            $cleanPath = preg_replace( $pattern, '',$subject);
            return [
                'path' => $cleanPath ,
                'regExReplace' => '(\d+)'
            ];
        } else {
            return [
                'path' => $path,
                'regExReplace' => ''
            ];

        }
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }
}
