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

    // OK
    public function findAll() 
    {
        
        $query = "SELECT b.id, b.name , 
            GROUP_CONCAT(
                JSON_OBJECT(
                    'id', c.id,
                    'name', c.name
                )
            ) AS categories
        FROM brands b
        LEFT JOIN brandID_CategoryID b_c 
            ON b.id = b_c.brand_id
        LEFT JOIN categories c 
            ON c.id = b_c.category_id
        GROUP BY 
            b.id, 
            b.name;
        ";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $datas = [];

        foreach ($row as  $value) {
            $categories = json_decode('[' . $value['categories'] . ']', true);
            $datas [] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "categories" => $categories
            ];
            // print_r($categories);
            // var_dump($value['categories']);
        }

        // var_dump($row );
        return $datas;
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