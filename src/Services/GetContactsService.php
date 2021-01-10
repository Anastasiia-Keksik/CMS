<?php


namespace App\Services;


use App\Repository\AccountRepository;
use App\Repository\ComicRepository;
use App\Repository\ContactRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class GetContactsService
{
    private $contactsRepository;

    private $accountRepository;

    private $comicsRepository;

    public function __construct(ContactRepository $contactRepo, AccountRepository $accRepo, ComicRepository $comicRepository)
    {
        $this->contactsRepository = $contactRepo;
        $this->accountRepository = $accRepo;
        $this->comicsReposiotory=$comicRepository;
    }

    public function getContacts($user){
        $contacts = $this->contactsRepository->findContacts($user);
        $contactProfile = [];

        $i = 0;
        foreach ($contacts as $contact){
            $contactProfile['fhd'.$i] = $this->accountRepository->findJustUsernameAndAvatar($contact['contact']);//TODO: moze jakies lepsze zapytanie, a nie z foreeacha podpierdalac (JOIN)
            $i++;
        }

        return $contactProfile;
    }

    public function getComics($user){
        return $this->comicsReposiotory->findMineComics($user);
    }
}