<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

class SubCategoriesManager 
{

    public ?string $_table = null;
    protected ?\PDO $_connexionBD = null;

    public function __construct($table)
    {
        $this->_table = $table;
        $instanceBD = ConnexionPDO::getInstance();
        $this->_connexionBD = $instanceBD->getConnection();
    }

    //OK
    public function findAll() 
    {
        $query = "SELECT sub.id , sub.name, c.id AS categoryId , c.name AS categoryName
        FROM sub_categories sub
        LEFT JOIN categories c
        ON c.id = sub.category_id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /**
    * @param int $id
    */
    public function findSubCategoriesBydId(int $id) 
    {

        $query = "SELECT * FROM sub_categories WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;  
    }

    public function addSubCategorie(?string $name ,int $category_id) {
        try {

            $query = "INSERT INTO sub_categories (name, category_id) VALUES (:name, :category_id)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la sous catégorie  : " . $e->getMessage();
            return false;
        }
    }

    public function updateSubCategories(?string $name, ?int $id,?int $category_id) {
        try {
            $query = "UPDATE sub_categories 
            SET name = :name,
                category_id = :category_id
            WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la sous catégorie : " . $e->getMessage();
            return false;
        }
    }

    public function delete(?int $id) 
    {
        $query = "DELETE FROM sub_categories WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}