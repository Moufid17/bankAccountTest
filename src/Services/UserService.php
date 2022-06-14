<?php

namespace App\Services;
use App\Entity\BankAccount;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Carbon\Carbon;

Class UserService{

    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em) {
        $this->userRepository = $userRepository;
        $this->em = $em;
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
            return ['currentBalance'=> 0, 'addedAmount' => 0];
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
        return ['currentBamance' => $newBalance, 'debitedAmount' => $debitedAmount]; 
    }
    
    public function account(){
        
    }

    public function notif(){
        // $currentDate = Carbon::now();   
    }
}