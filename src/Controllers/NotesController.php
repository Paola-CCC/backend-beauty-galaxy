<?php 

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\NotesManager;
use App\Service\HttpRequest;

class NotesController 
{

    private $notesManager ;
    private $httpRequest ;

    public function __construct()
    {
        $this->notesManager = new NotesManager('notes');
        $this->httpRequest = new HttpRequest();
    }

    //OK
    public function getAllNotes() 
    {
        return json_encode($this->notesManager->findAll());
    }

    //OK
    public function getById(?int $id) 
    {
        return json_encode($this->notesManager->findById($id));
    }

    //OK
    public function newNotes() 
    {
        $data = $this->httpRequest->getBody();
        return json_encode($this->notesManager->createNotes($data));
    }

    // OK
    public function deleteNotes(?int $id) 
    {
        return json_encode($this->notesManager->delete($id));
    }
}