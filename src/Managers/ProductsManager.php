<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

 class ProductsManager 
 {

    public $_table;
	protected $_connexionBD;
    

	public function __construct($table)
	{
		$this->_table = $table;
		$instanceBD = ConnexionPDO::getInstance();
		$this->_connexionBD = $instanceBD->getConnection();
	}

    //OK
    public function findAll() 
    {
        $query = "SELECT p.id, p.name, p.descriptionShort, p.descriptionLong, p.thumbnail, p.quantity, p.createdAt, p.price, b.name AS brandName, c.name AS categories 
        FROM products p
        LEFT JOIN brands b 
        ON p.brand_id = b.id
        LEFT JOIN categories c 
        ON p.category_id = c.id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    public function findFourMostPopularProduct() 
    {
        $query = "SELECT p.id, p.name, p.descriptionShort, p.descriptionLong, p.thumbnail, p.quantity, p.createdAt, p.price, b.name AS brandName, c.name AS categories 
        FROM products p
        LEFT JOIN brands b 
        ON p.brand_id = b.id
        LEFT JOIN categories c 
        ON p.category_id = c.id
        ORDER BY p.createdAt DESC
        LIMIT 4";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    

    /**
    * @param int $id
    */
    public function findByBrandId(int $id ) 
    {

        $query = "SELECT * FROM products p
        LEFT JOIN brands b 
        ON p.brand_id = b.id
        WHERE b.id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;  
    }


    /**
    * @param string $name
    */
    public function findByBrandName(array $data) 
    {

        $query = "SELECT * FROM products p
        WHERE p.name = :name
        AND p.brand_id = :brand_id
        AND p.thumbnail = :thumbnail";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute([
            ':name'  => $data['name'], 
            ':brand_id' => $data['brand_id'],
            ':thumbnail' => $data['thumbnail']
        ]);
        // $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stmt;  
    }


    /**
     * @param int $id
     */
    public function findByCategorydId(int $id ) 
    {

        $query = "SELECT * FROM products p
        LEFT JOIN categories c 
        ON p.category_id = c.id
        WHERE c.id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    /**
    * @param int $id
    */
    public function findById(int $id) 
    {

        $query = "SELECT * FROM products WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    /**
    * @param array $data
    */
    public function updateProduct(?array $data) {

        try {

            $query = "UPDATE products 
                      SET brand_id = :brand_id, 
                          name = :name, 
                          descriptionShort = :descriptionShort, 
                          descriptionLong = :descriptionLong, 
                          thumbnail = :thumbnail, 
                          quantity = :quantity, 
                          category_id = :category_id, 
                          price = :price,
                          updatedAt = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':brand_id', $data['brand_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':descriptionShort', $data['descriptionShort'], PDO::PARAM_STR);
            $stmt->bindParam(':descriptionLong', $data['descriptionLong'], PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail', $data['thumbnail'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);
            $stmt->execute();

            $product_id = $data['id'];
        
            if(!empty($data['tagsId']) && !empty($data['subCategories'])) {
                $updatedTags = $this->updateTagsToProduct($data['tagsId'], $product_id);
                $updatedSubCategories = $this->updateSubCategoriesToProduct($data['subCategories'], $product_id);
            }

            return true;


        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du produit :" . (int) $data['id'] . $e->getMessage();
            return false;
        }
    }


    public function createProduct(array $data) {
        try {

            $query = "INSERT INTO products 
            (brand_id, name, descriptionShort, descriptionLong, thumbnail, quantity, category_id, createdAt, updatedAt, price) 
            VALUES 
                (
                :brand_id, 
                :name, 
                :descriptionShort, 
                :descriptionLong, 
                :thumbnail, 
                :quantity, 
                :category_id, 
                CURRENT_TIMESTAMP, 
                NULL, 
                :price)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':brand_id',$data['brand_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':descriptionShort', $data['descriptionShort'], PDO::PARAM_STR);
            $stmt->bindParam(':descriptionLong', $data['descriptionLong'], PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail', $data['thumbnail'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);

            if($stmt->execute()){
                $product_id = $this->_connexionBD->lastInsertId();

                if(!empty($data['tagsId']) && !empty($data['subCategories'])) {
                    $addedtags = $this->addTagsToProduct($data['tagsId'], $product_id);
                    $addedSubCategories = $this->addSubCategoriesToProduct($data['subCategories'], $product_id);   
                }
                return true;

            }
        } catch (PDOException $e) {
            echo "Erreur lors de la création du produit :" . $e->getMessage();
            return false;
        }
    }

    public function delete(?int $id) 
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /** Ajouter Tags à product */
    public function addTagsToProduct (array $tagsArray, int $product_id) 
    {

        $query = "INSERT INTO productID_tagID (Id_product, Id_tag) VALUES (?, ?)";
        $stmt = $this->_connexionBD->prepare($query);

        foreach ($tagsArray as $tagsID ) {

            if (is_numeric($tagsID)) {
                if (!$stmt->execute([$product_id, $tagsID])) {
                    echo "Il y a eu un problème dans la requête pour les tags";
                    return false;
                }
            } else {
                echo "Les Tags n'ont pas un type number";
                return false;
            }
        }
    }

    /** Update Tags à product */
    public function updateTagsToProduct (array $tagsArray, int $product_id) 
    {

        $query = "DELETE FROM productID_tagID WHERE Id_product = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $product_id, PDO::PARAM_INT);

        if( $stmt->execute()) {
            return $this->addTagsToProduct($tagsArray,$product_id );
        }
    }

    /** Ajouter Subcategories à product */
    public function addSubCategoriesToProduct (array $subCategoriesArray, int $product_id) 
    {

        $query = "INSERT INTO productID_subCategoriesID (Id_product, Id_subCategory) VALUES (?, ?)";
        $stmt = $this->_connexionBD->prepare($query);

        foreach ($subCategoriesArray as $subCategoryID ) {

            if (is_numeric($subCategoryID)) {
                if (!$stmt->execute([$product_id, $subCategoryID])) {
                    echo "Il y a eu un problème dans la requête pour les sous catégories";
                    return false;
                } 

            } else {
                echo "Les sous catégories n'ont pas un type number";
                return false;
            }
        }
    }

    /** Update Subcategories à product */
    public function updateSubCategoriesToProduct(array $subCategoriesArray, int $product_id)
    {

        $query = "DELETE FROM productID_subCategoriesID WHERE Id_product = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $product_id, PDO::PARAM_INT);

        if( $stmt->execute()) {
            return $this->addSubCategoriesToProduct($subCategoriesArray,$product_id );
        }
    }
    
 }