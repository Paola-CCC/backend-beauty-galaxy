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
        $query = "SELECT * FROM products";
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
        AND p.product_image = :product_image";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute([
            ':name'  => $data['name'], 
            ':brand_id' => $data['brand_id'],
            ':product_image' => $data['product_image']
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
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                          description_short = :description_short, 
                          description_long = :description_long, 
                          product_image = :product_image, 
                          quantity = :quantity, 
                          category_id = :category_id, 
                          price = :price,
                          updatedAt = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':brand_id', $data['brand_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description_short', $data['description_short'], PDO::PARAM_STR);
            $stmt->bindParam(':description_long', $data['description_long'], PDO::PARAM_STR);
            $stmt->bindParam(':product_image', $data['product_image'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);
            $stmt->execute();

            $product_id = $data['id'];
        
            if(!empty($data['tagsId']) && !empty($data['subCategories'])) {
                $updatedTags = $this->updateTagsToProduct($data['tagsId'], $product_id);
                $updatedSubCategories = $this->updateSubCategoriesToProduct($data['subCategories'], $product_id);

                if( $updatedTags !== false && $updatedSubCategories !== false ) {
                    return true;
                }
            }

        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du produit :" . (int) $data['id'] . $e->getMessage();
            return false;
        }
    }


    public function createProduct($data) {
        try {

            $query = "INSERT INTO products 
            (brand_id, name, description_short, description_long, product_image, quantity, category_id, createdAt, updatedAt, price) 
            VALUES 
                (
                :brand_id, 
                :name, 
                :description_short, 
                :description_long, 
                :product_image, 
                :quantity, 
                :category_id, 
                CURRENT_TIMESTAMP, 
                NULL, 
                :price)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':brand_id',$data['brand_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description_short', $data['description_short'], PDO::PARAM_STR);
            $stmt->bindParam(':description_long', $data['description_long'], PDO::PARAM_STR);
            $stmt->bindParam(':product_image', $data['product_image'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_INT);
            $stmt->execute();

            $product_id = $this->_connexionBD->lastInsertId();

            if(!empty($data['tagsId']) && !empty($data['subCategories'])) {
                $addedtags = $this->addTagsToProduct($data['tagsId'], $product_id);
                $addedSubCategories = $this->addSubCategoriesToProduct($data['subCategories'], $product_id);

                if( $addedtags !== false && $addedSubCategories !== false) {
                    return true;
                }
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