<?php

namespace App\Managers;

use App\Managers\ConnexionPDO;
use DateTimeImmutable;
use PDO;

class CartItemsManager 
{

    public $_table;
	protected $_connexionBD;
    protected $_currentDate = null;
    
	public function __construct($table)
	{
		$this->_table = $table;
		$instanceBD = ConnexionPDO::getInstance();
		$this->_connexionBD = $instanceBD->getConnection();
        $now = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $this->_currentDate = $now->format('Y-m-d H:i');
	}

    //OK
    public function findAll() 
    {
        $query = "SELECT * FROM cartItems";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
    * @param int $id
    */
    public function findById(?int $id) 
    {

        $query = "SELECT * FROM cartItems WHERE id=:id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    public function add(?array $data)
    {
        
        $query="INSERT INTO cartItems (user_id,totalPricing, quantity_chosen, status, createdAt, updatedAt) 
                VALUES (:user_id, :totalPricing, :quantity_chosen, :status, :createdAt, NULL)";

        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':totalPricing', $data['totalPricing'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity_chosen', $data['quantity_chosen'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':createdAt', $this->_currentDate);

        if($stmt->execute()){
            $lastCartItemInsert = $this->_connexionBD->lastInsertId();

            $addedCartItem = $this->addToCartItemsID_ProductID($data['productID_List'], $lastCartItemInsert);
            if($addedCartItem) {
                echo "Tout a bien été enregistré avec succès." ;
                return true;
            }

        } else {
            return false;
        }
    }


    public function update(?array $data)
    {

        $query = "UPDATE cartItems 
        SET user_id =:user_id,
            totalPricing =:totalPricing,
            quantity_chosen =:quantity_chosen,
            status =:status,
            updatedAt = :updatedAt
        WHERE id = :id";

        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':totalPricing', $data['totalPricing'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity_chosen', $data['quantity_chosen'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':updatedAt', $this->_currentDate);

        if($stmt->execute()) {

            $updatedCartItem = $this->updateCartItemsID_ProductID($data['productID_List'], $data['id'] );

            if($updatedCartItem) {
                echo "Tout a bien été mise à jour avec succès";
                return true;
            }

        } else {
            return false;
        }
    }


    public function delete(?int $id)
    {

        $query = "DELETE FROM cartItems WHERE id = :id";

        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if( $stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    /** Liéer le productId avec CartItem */
    public function addToCartItemsID_ProductID(?array $productIdList, int $cartItems_id){

        $query = "INSERT INTO cartItemsID_ProductID (cartItems_id ,product_id) VALUES (?, ?)";
        $stmt = $this->_connexionBD->prepare($query);
     
        foreach ($productIdList as $product_Id ) {

            if (is_numeric($product_Id)) {
                !$stmt->execute([$cartItems_id, $product_Id]);

            } else {
                echo "Le productId n'a pas un type number";
                return false;
            }
        }
    }

    public function updateCartItemsID_ProductID(?array $productIdList, int $cartItems_id)
    {

        $query = "DELETE FROM cartItemsID_ProductID WHERE cartItems_id =:cartItems_id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":cartItems_id", $cartItems_id, PDO::PARAM_INT);

        if( $stmt->execute()) {
            return $this->addToCartItemsID_ProductID($productIdList, $cartItems_id);
        }
    }
    

}