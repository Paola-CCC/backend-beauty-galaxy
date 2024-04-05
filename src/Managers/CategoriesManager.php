<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

class CategoriesManager 
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
        $query = "SELECT * FROM categories";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /**
    * @param int $id
    */
    public function findCategoriesBydId(int $id) 
    {

        $query = "SELECT * FROM categories WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;  
    }

    public function addCategorie(?string $name) {
        try {

            $query = "INSERT INTO categories (name) VALUES (:name)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la catégorie  : " . $e->getMessage();
            return false;
        }
    }

    public function updateCategorie(?string $name, ?int $id) {
        try {
            $query = "UPDATE categories 
            SET name = :name
            WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la catégorie : " . $e->getMessage();
            return false;
        }
    }

    public function delete(?int $id) 
    {
        $query = "DELETE FROM categories WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}