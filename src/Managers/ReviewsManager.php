<?php
namespace App\Managers;


use App\Managers\ConnexionPDO;
use PDO;
use PDOException;

class ReviewsManager 
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
        $query = "SELECT r.id , r.product_id, r.rating, r.comment, r.createdAt, r.updatedAt, c.pseudo
        FROM reviews r
        LEFT JOIN clients c 
        ON r.client_id = c.id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    /**
    * @param int $id
    */
    public function findById(int $id) 
    {

        $query = "SELECT r.id , r.product_id, r.rating, r.comment, r.createdAt, r.updatedAt , c.pseudo
        FROM reviews
        LEFT JOIN clients c 
        ON r.client_id = c.id
        WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    public function createReview(?array $data) {
        try {

            $query = "INSERT INTO reviews
                (client_id, product_id, rating, comment, createdAt ,updatedAt) 
            VALUES 
                (   :client_id, 
                    :product_id, 
                    :rating, 
                    :comment, 
                    CURRENT_TIMESTAMP,
                    NULL 
                )";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':client_id',$data['client_id']);
            $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':rating', $data['rating'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout d'une review  :" . $e->getMessage();
            return false;
        }

    }

    public function updateReview(array $data) {
        try {

            $query = "UPDATE reviews
                    SET client_id =:client_id, 
                        product_id =:product_id, 
                        rating =:rating, 
                        comment =:comment, 
                        updatedAt = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':id',$data['id'], PDO::PARAM_INT );
            $stmt->bindParam(':client_id',$data['client_id']);
            $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':rating', $data['rating'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de reviews  :" . $e->getMessage();
            return false;
        }

    }


    public function delete(int $id) 
    {
        $query = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}