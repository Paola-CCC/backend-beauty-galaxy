<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

class TagsManager 
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
        $query = "SELECT * FROM tags";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /**
    * @param int $id
    */
    public function findTagBydId(?int $id) 
    {

        $query = "SELECT * FROM tags WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;  
    }

    public function addTag(?string $name) {
        try {
            $query = "INSERT INTO tags (name) VALUES (:name)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du tag :" . $e->getMessage();
            return false;
        }
    }

    public function updateTag(?string $name, ?int $id) {
        try {
            $query = "UPDATE tags 
            SET name = :name
            WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du tag :" . $e->getMessage();
            return false;
        }
    }

    public function delete(?int $id) 
    {
        $query = "DELETE FROM tags WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}