<?php

namespace App\Services;
use App\Entity\BankAccount;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;
use App\ExternalServices\EmailSenderService;

Class UserService{

    private $userRepository;
    private $em;
    private $externalService;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, EmailSenderService $externalService) {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->externalService = $externalService;
    }

    public function credit($userId, int $amount){
        
        $user = $this->userRepository->find($userId); 
        $userAccount = $user->getAccount();
        
        if ($userAccount === null) {
            $userAccount = new BankAccount();
            $userAccount->setOwner($user);
            $this->em->persist($user);
        }

        $newBalance = $userAccount->getBalance() + $amount;
        $amountAdd = 0;
        if($newBalance > 1000){
            $newBalance = 1000;
            $amountAdd = 1000 - $userAccount->getBalance();
        } elseif ($newBalance <= 1000){
            $amountAdd = $amount;
        }


        $userAccount->setBalance($newBalance);
        $this->em->flush();
        # Notify user 
        $currentHour = Carbon::now('Europe/Paris')->hour;
        if($currentHour >= 22 || $currentHour <= 6){

        }
        $this->notif();
        return ['currentBalance'=> $newBalance, 'addedAmount' => $amountAdd];
    }

    public function debit($userId, int $amount){
        $user = $this->userRepository->find($userId);
        $userAccount = $user->getAccount();

        if ($userAccount === null) {
            $userAccount = new BankAccount();
            $userAccount->setBalance(0);
            $userAccount->setOwner($user);
            $this->em->persist($user);
            $this->em->flush();
            $this->notif();
            return ['currentBalance'=> 0, 'debitedAmmount' => 0];
        }
        
        $newBalance = $userAccount->getBalance() - $amount;
        $debitedAmount = 0;
        if($newBalance < 0){
            $newBalance = 0;
            $debitedAmount = $userAccount->getBalance();
        } elseif ($newBalance >= 0){
            $debitedAmount = $amount;
        }
        $userAccount->setBalance($newBalance);
        $this->em->flush();
        $this->notif();
        return ['currentBalance' => $newBalance, 'debitedAmount' => $debitedAmount]; 
    }
    
    public function account($userId){
        $user = $this->userRepository->find($userId);
        return ['Balance' => $user->getAccount()->getBalance()];
    }

    private function notif() {
        $currentDate = Carbon::now('Europe/Paris'); 
        if($currentDate->hour >= 22 || $currentDate->hour <= 6){
            $message = `Vous aviez effectuer une opération de ? d'un montant de ? à `;
            $this->externalService->sendEmail($message);
            return true;
        }
        return false;
    }

}