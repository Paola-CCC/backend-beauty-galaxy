<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

class BrandsManager 
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
        $query = "SELECT * FROM brands";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    

    /**
    * @param int $id
    */
    public function findBrandBydId(int $id) 
    {

        $query = "SELECT * FROM brands WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;  
    }

    public function addBrand(?string $name) {
        try {

            $query = "INSERT INTO brands (name) VALUES (:name)";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la marque  :" . $e->getMessage();
            return false;
        }
    }

    public function updateBrand(?string $name, ?int $id) {
        try {
            $query = "UPDATE brands 
            SET name = :name
            WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la marque :" . $e->getMessage();
            return false;
        }
    }

    public function delete(?int $id) 
    {
        $query = "DELETE FROM brands WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}