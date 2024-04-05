<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\ReviewsManager;
use App\Service\HttpRequest;

class ReviewsController 
{

    private $reviewsManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->reviewsManager = new ReviewsManager('reviews');
        $this->httpRequest = new HttpRequest();
    }

    //OK
    public function getAllreviews() 
    {
        return json_encode($this->reviewsManager->findAll());
    }

    //OK
    public function getById(?int $id) 
    {
        return json_encode($this->reviewsManager->findById($id));
    }

    //OK
    public function newReviews() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->reviewsManager->createReview($data));
    }

    //OK
    public function updateReviews() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->reviewsManager->updateReview($data));
    }
    //OK
    public function deleteReviews(?int $id) 
    {
        return json_encode($this->reviewsManager->delete($id));
    }
}