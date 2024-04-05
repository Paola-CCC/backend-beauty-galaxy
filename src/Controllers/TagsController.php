<?php 

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\TagsManager;
use App\Service\HttpRequest;

class TagsController 
{

    private $tagsManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->tagsManager = new TagsManager('tags');
        $this->httpRequest = new HttpRequest();
    }

    public function getAllTags() 
    {
        return json_encode($this->tagsManager->findAll());
    }


    public function getById(?int $id) 
    {
        return json_encode($this->tagsManager->findTagBydId($id));
    }

    public function newTag() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->tagsManager->addTag($data['name']));
    }


    public function updateTag() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->tagsManager->updateTag($data['name'] ,$data['id']));
    }

    public function deleteTag(?int $id) 
    {
        return json_encode($this->tagsManager->delete($id));
    }

}