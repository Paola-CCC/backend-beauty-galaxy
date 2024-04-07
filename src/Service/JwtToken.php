<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Clients;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{

    
    private const REFRESH_THRESHOLD = 1800; 

    public function createToken ($userDatas): string
    {

        $issuedAt = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $expire = $issuedAt->modify('+10 days')->getTimestamp();

        return JWT::encode([
            'exp' => time() + 20,
            'iat' => $issuedAt->getTimestamp(),  
            'nbf' => $issuedAt->getTimestamp(),
            'exp' => $expire,                  
            'user' => $userDatas
        ], JWT_SECRET , 'HS256');
       
    }

    /** Va rafraichir mon token a chaque connexion */ 
    public function verifyToken($tokenJWT) 
    {
        try {

            $decoded = JWT::decode($tokenJWT, new Key(JWT_SECRET, 'HS256'));
            $exp = $decoded->exp;

            // Vérifier si le jeton est expiré ou non
            if (isset($exp) && time() > $exp) {
                return false;
            }
            
            if ($this->isDelayExpired($exp) === false) {

                $user = new Clients();
                $user->setId($decoded->id);
                $user->setpseudo($decoded->pseudo);
                $user->setEmail($decoded->email);
                $user->setRole($decoded->role);

                return $this->executeRefreshToken([
                    'id' => (int) $decoded->id,
                    'pseudo' => $decoded->pseudo,
                    'email' => $decoded->email,
                    'role' => $decoded->role
                ]);
            } 
        
        } catch (Exception $e) {

            return json_encode([
                "message" => "Le jeton JWT n'a pas pu être raffraichi",
                "error"  => $e
            ]);
        }
    }

    function isDelayExpired(int $jwtTimeExp ): bool {

        $currentDateTime = time();

        $diffInSeconds = $currentDateTime - $jwtTimeExp;

        if ($diffInSeconds > time() + self::REFRESH_THRESHOLD) {
            return true ;
        } else {
            return false ;
        }      
    }

    /** Refresh old Token */ 
    public function executeRefreshToken( array $userDatas)
    {

        $newJWT = $this->createToken($userDatas);
        $cookie = new CookieHelper();
        return $cookie->setCookie('token_jwt', $newJWT );
        
    }

}