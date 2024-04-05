<?php

namespace App\Managers;

use PDO;
use PDOException;

class NotesManager 
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
        $query = "SELECT * FROM notes";
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

        // echo " PATATE";
        $query = "SELECT * FROM notes WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    //OK
    public function createNotes(?array $data) {
        try {

            $query = "INSERT INTO notes
                (client_id, product_id, notes, createdAt) 
            VALUES 
                (   :client_id, 
                    :product_id, 
                    :notes, 
                    CURRENT_TIMESTAMP
                )";
            $stmt = $this->_connexionBD->prepare($query);
            $stmt->bindParam(':client_id',$data['client_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':notes', $data['notes'], PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la note  :" . $e->getMessage();
            return false;
        }
    }

    public function delete(int $id) 
    {
        $query = "DELETE FROM notes WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

}