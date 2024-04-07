<?php

// respect strict du type des paramètres des fonctions
declare(strict_types=1);

namespace App\Controllers;

use App\Entity\Clients;
use App\Managers\ClientsManager;
use App\Service\HttpRequest;
use App\Service\JwtToken;
use DateTime;
use DateTimeImmutable;
use PDO;

class ClientsController 
{

    private $clientsManager ;
    private $httpRequest;
    const ROLE_ID = 2; 

    public function __construct()
    {
        $this->clientsManager = new ClientsManager('clients');
        $this->httpRequest = new HttpRequest();
    }

    public function login ()
    {    
        $data = $this->httpRequest->getBody();
        $email = $data['email'];
        $password = $data['password'] ;
        
        if( !empty($email) && !empty($password)) {

                $user = $this->clientsManager->findByEmail($email);
                $userCounter = $user->rowCount();

                if ($userCounter > 0 ) {
                    $row = $user->fetch();

                    $client = new Clients();
                    $client->setId($row['id']);
                    $client->setpseudo($row['pseudo']);
                    $client->setFirstname($row['firstname']);
                    $client->setLastname($row['lastname']);
                    $client->setEmail($row['email']);
                    $client->setRole($row['role_name']);
                    $client->setBirthday($row['birthday']);

                    $passwordFromDB = $row['password'];

                    if (password_verify($password, $passwordFromDB)) {

                        http_response_code(200);
                        return json_encode([
                            'jwt' => $this->addDatasToJWT($client->getEmail()),
                            'user' => [
                                'id' => $client->getId(),
                                'email' => $client->getEmail(),
                            ]
                        ]);
                    }

                } else {
                    return json_encode([
                        "code" => http_response_code(404),
                        "message" => 'Aucun utilisateur trouvé'
                    ]);
                } 
                
        } else {
            return json_encode([
                "code" => http_response_code(404),
                "message" => 'Veuillez saisir les informations demandées',
            ]);       
        }
    }

    public function register () 
    {
        $data = $this->httpRequest->getBody();
        $email = $data['email'];
        $birthdayData = new DateTime($data['birthday']);
        $immutableBirthday = DateTimeImmutable::createFromMutable($birthdayData)->format('Y-m-d H:i:s');
        
        $tabDatas = [
            "lastname" => $data['lastname'], 
            "firstname" => $data['firstname'],
            "pseudo" => $data['pseudo'],
            "email" =>  $data['email'],
            "password" => $data['password'],
            "role" => self::ROLE_ID,
            "birthday" => $immutableBirthday
        ];
        
        $num = $this->clientsManager->findByEmail($email)->rowCount(); 

        if ($num === 0 ){
 
            if($this->clientsManager->insertUser($tabDatas) === true){

                $lastClientInsert = $this->clientsManager->getLastInsertId();
                $userClient = $this->clientsManager->findById((int) $lastClientInsert);

                $client = new Clients();
                $client->setId((int) $lastClientInsert);
                $client->setpseudo($userClient['pseudo']);
                $client->setFirstname($userClient['firstname']);
                $client->setLastname($userClient['lastname']);
                $client->setEmail($userClient['email']);
                $client->setRole($userClient['role_name']);
                $client->setBirthday($userClient['birthday']);

                return json_encode([
                    'jwt' => $this->addDatasToJWT($email),
                    'user' => [
                        'id' => $client->getId(),
                        'email' => $client->getEmail(),
                    ]
                ]);
            } else {

                return json_encode([
                    "message" => "Inpossible de s'inscrire"
                ]);
            }

        } else {

            http_response_code(404);
            return json_encode([
                "message" => 'Veuillez saisir une autre adresse mail'
            ]);
        } 
    }

    // Mettes des informations dans le JWT __, Enregistrement
    public function addDatasToJWT(?string $userEmail)
    {
       
        $element = $this->clientsManager->findByEmail($userEmail)->fetchAll(PDO::FETCH_ASSOC);
        
        if ($element > 0){

            foreach($element as $value) {

                $userInfosJWT = [
                    'id' => (int) $value["id"],
                    'pseudo' => $value["pseudo"],
                    'email' => $value["email"],
                    'role' => $value["role_name"]
                ];

                $JWT = new JwtToken();
                $createdJWT = $JWT->createToken($userInfosJWT);
                setcookie('token_jwt', $createdJWT, time() + 3600, '/');
                return $createdJWT;
            }
        }
    }

    //Ok
    public function update()
    {

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['userId'];
        $pseudo = $data['pseudo'] ;
        $email = $data['email'];
        $password = $data['password'] ;

        http_response_code(200);
        return json_encode($this->clientsManager->update([
            "id" => $userId,
            "pseudo" => $pseudo,
            "email" =>  $email,
            "password" => $password,
        ]));
    }
    
    //OK
    public function show(int $id)
    {
        http_response_code(200);
        return json_encode($this->clientsManager->findById((int) $id));
    }

    //OK
    public function all()
    {
        http_response_code(200);
        return json_encode($this->clientsManager->findAll());
    }

    //OK
    public function delete(int $id)
    {
        http_response_code(200);
        return json_encode($this->clientsManager->delete($id));
    }
}