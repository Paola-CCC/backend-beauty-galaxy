<?php

namespace App\Managers;

use App\Entity\Clients;
use App\Managers\ConnexionPDO;
use DateTimeImmutable;
use IntlDateFormatter;
use PDO;

 class ClientsManager 
 {

    public $_table = 'clients';
	protected $_connexionBD;

	public function __construct($table)
	{
		$this->_table = $table;
		$instanceBD = ConnexionPDO::getInstance();
		$this->_connexionBD = $instanceBD->getConnection();
	}

    //OK
    public function findById(?int $id ) 
    {

        $query = "SELECT c.id, c.username, c.firstname, c.lastname, c.email, c.password, c.created_at, c.birthday, r.name  as role_name
                  FROM clients c
                  LEFT JOIN role r ON c.role_Id = r.id
                  WHERE c.id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ; 
        
    }

    //OK
    public function findByEmail(string $email) 
    {

        $query = "SELECT c.id, c.username, c.firstname, c.lastname, c.email, c.password, c.created_at, c.birthday, r.name as role_name
        FROM clients c
        LEFT JOIN role r ON c.role_Id = r.id
        WHERE c.email = :email";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":email", $email , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt ;   
    }


    //OK
    public function findAll()
    {

        $query = "SELECT c.id, c.firstname, c.lastname, c.username, c.email, c.created_at, c.birthday, r.name as role_name
        FROM clients c
        LEFT JOIN role r 
        ON c.role_Id = r.id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ;
    }

    //OK
    public function insertUser( $data) 
    {

        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        // Récupération de la date et heure actuelles
        $now = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $created_at = $now->format('Y-m-d H:i');

        $query = "INSERT INTO clients ( firstname, lastname, username, email, password, created_at, role_Id ,birthday ) VALUES ( :firstname, :lastname, :username, :email, :password, :created_at, :role_Id ,:birthday)";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":firstname", $data['firstname']);
        $stmt->bindParam(":lastname", $data['lastname']);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":created_at",$created_at);
        $stmt->bindParam(":role_Id", $data['role']);
        $stmt->bindParam(":birthday", $data['birthday']);

        if($stmt->execute()){ 

            $lastInsertedId = $this->_connexionBD->lastInsertId();
            $userClient = $this->findById($lastInsertedId);

            $client = new Clients();
            $client->setId($lastInsertedId);
            $client->setUsername($userClient['username']);
            $client->setFirstname($userClient['firstname']);
            $client->setLastname($userClient['lastname']);
            $client->setEmail($userClient['email']);
            $client->setRole($userClient['role_name']);
            $client->setBirthday($userClient['birthday']);

            return true;
        } else {
            return false;
        }
    }

    //OK
    public function update(array $data ) 
    {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $now = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        // Formatage de la date en format SQL
        $created_at = $now->format('Y-m-d H:i:s');

        $query = "UPDATE user SET username=:username, email=:email, password=:password, created_at=:created_at, role_Id=:role_Id, birthday=:birthday WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $data['id']);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":created_at",$created_at );
        $stmt->bindParam(":role_Id", $data['role']);
        $stmt->bindParam(":birthday", $data['birthday']);
        return $stmt->execute();
    }

    /** Suppression de l'utilisateur de la base de données */ 
    public function delete(int $id) 
    {
        $query = "DELETE FROM clients WHERE id = :id";
        $stmt = $this->_connexionBD->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
 }