<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\BankAccount;
use PHPUnit\Framework\TestCase;
use App\ExternalServices\EmailSenderService;
use App\Services\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class BankAccountTest extends KernelTestCase
{
    private User $user;
    private UserService $userService;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *  @var \App\ExternalServices\EmailSenderService
     */
    private $externalService;
    // private $userRepository;

    // public function __construct(UserRepository $userRepository, EntityManagerInterface $em, EmailSenderService $externalService) {
    //     $this->userRepository = $userRepository;
    //     $this->em = $em;
    //     $this->externalService = $externalService;
    // }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
            
        $this->externalService = new EmailSenderService();
        $this->userService = new UserService($this->em->getRepository(User::class), $this->em, $this->externalService);
        $this->user = $this->em->getRepository(User::class)->find(1);

        parent::setUp();
    }

    // public function testCredit()
    // {
    //     $this->userService->credit($this->user, 200);
    //     $this->assertTrue();
    // }

    public function testDebit()
    {
        $amountToDebit = 100;
        $this->user->getAccount()->setBalance(500);
        $expectedResponse = ['currentBalance'=> $this->user->getAccount()->getBalance() - $amountToDebit, 'debitedAmount' => $amountToDebit];
        $response = $this->userService->debit(1, $amountToDebit);

        $this->assertEquals($expectedResponse,$response);
    }

 /*   public function testCreditMoreThanMax()
    {
        $this->assertTrue();
    }

    public function testDebitLessThanMin()
    {
        
    }

    public function testDebitMoreThanBalance()
    {
        $this->assertTrue();
    }

    public function testDebitDoneBetween22And6()
    {
        $this->assertTrue();
    }

    public function testCreditDoneBetween22And6()
    {
        $this->assertTrue();
    }
*/

}

