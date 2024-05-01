<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Test cases for class Card from Game21.
 */
class Game21Test extends TestCase
{
    /**
     * Construct object and verify it is a Game21 object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     */
    public function testCreateObject(): void
    {

        $game = new Game21();
        $this->assertInstanceOf("\App\Game21\Game21", $game);
    }

    public function testToSession(): void
    {
        // Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for toArray method calls
        $desk->method('toArray')
            ->willReturn([[0,1], [2,3]]); // Mocked desk array

        $bank->method('toArray')
            ->willReturn([['hand' => [
                                'points' => 0,
                                'cards' => [],
                            ],
                            'bet' => 0,
                            'profit' => 0,
                        ]]); 
        // Mocked bank array

        $player->method('toArray')
            ->willReturn([['hand' => [
                                'points' => 0,
                                'cards' => [],
                            ],
                            'bet' => 0,
                            'profit' => 0,
                        ]]); 
        // Mocked player array

        // Set up expectations for set method calls
        $session->expects($this->exactly(3))
                ->method('set')
                ->withConsecutive(
                    ['desk', [[0, 1], [2, 3]]],
                    ['bank', [['hand' => [
                                        'points' => 0,
                                        'cards' => [],
                                    ],
                                    'bet' => 0,
                                    'profit' => 0,
                                ]]],
                    ['player', [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]]
                );

        // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

        // Call the toSession method
        $game->toSession($session);
    }

    public function testSet(): void
    {
        // Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for get method calls
        $session->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive(
                    ['desk'],
                    ['bank'],
                    ['player']
                )
                ->willReturnOnConsecutiveCalls(
                    // Mocked desk array
                    [[0, 1], [2, 3]], 
                    // Mocked bank array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]],
                // Mocked player array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]
                );

        // Set up expectations for set method calls
        $desk->expects($this->once())
            ->method('set')
            // Mocked desk array
            ->with([[0, 1], [2, 3]]);

        $bank->expects($this->once())
            ->method('set')
            // Mocked bank array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        $player->expects($this->once())
            ->method('set')
            // Mocked player array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

        // Call the set method
        $game->set($session);
    }

    public function testFirstState(): void
    {
        //Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for get method calls
        $session->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive(
                    ['desk'],
                    ['bank'],
                    ['player']
                )
                ->willReturnOnConsecutiveCalls(
                    // Mocked desk array
                    [[0, 1], [2, 3]], 
                    // Mocked bank array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]],
                // Mocked player array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]
                );

        // Set up expectations for set method calls
        $desk->expects($this->once())
            ->method('set')
            // Mocked desk array
            ->with([[0, 1], [2, 3]]);

        $bank->expects($this->once())
            ->method('set')
            // Mocked bank array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        $player->expects($this->once())
            ->method('set')
            // Mocked player array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

         // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

         // Call the firstState method with the mock session object
        $data = $game->firstState($session);
 
         // Assert that the returned data contains the expected keys
        $this->assertArrayHasKey('desk', $data);
    }

    public function testSecondState(): void
    {
        //Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for get method calls
        $session->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive(
                    ['desk'],
                    ['bank'],
                    ['player']
                )
                ->willReturnOnConsecutiveCalls(
                    // Mocked desk array
                    [[0, 1], [2, 3]], 
                    // Mocked bank array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]],
                // Mocked player array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]
                );

        // Set up expectations for set method calls
        $desk->expects($this->once())
            ->method('set')
            // Mocked desk array
            ->with([[0, 1], [2, 3]]);

        $bank->expects($this->once())
            ->method('set')
            // Mocked bank array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        $player->expects($this->once())
            ->method('set')
            // Mocked player array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

         // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

         // Call the firstState method with the mock session object
        $data = $game->secondState($session);
 
         // Assert that the returned data contains the expected keys
        $this->assertArrayHasKey('bankBet', $data);
    }

    public function testThirdState(): void
    {
        //Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for get method calls
        $session->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive(
                    ['desk'],
                    ['bank'],
                    ['player']
                )
                ->willReturnOnConsecutiveCalls(
                    // Mocked desk array
                    [[0, 1], [2, 3]], 
                    // Mocked bank array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]],
                // Mocked player array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]
                );

        // Set up expectations for set method calls
        $desk->expects($this->once())
            ->method('set')
            // Mocked desk array
            ->with([[0, 1], [2, 3]]);

        $bank->expects($this->once())
            ->method('set')
            // Mocked bank array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        $player->expects($this->once())
            ->method('set')
            // Mocked player array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

         // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

         // Call the firstState method with the mock session object
        $data = $game->thirdState($session, 21);
 
         // Assert that the returned data contains the expected keys
        $this->assertArrayHasKey('PlayerPoints', $data);
    }

    public function testPlayerNewCard(): void
    {
        //Create mock objects for Session, Desk, Bank, and Player
        $session = $this->createMock(Session::class);
        $desk = $this->createMock(Desk::class);
        $bank = $this->createMock(Bank::class);
        $player = $this->createMock(Player::class);

        // Set up expectations for get method calls
        $session->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive(
                    ['desk'],
                    ['bank'],
                    ['player']
                )
                ->willReturnOnConsecutiveCalls(
                    // Mocked desk array
                    [[0, 1], [2, 3]], 
                    // Mocked bank array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]],
                // Mocked player array
                    [['hand' => [
                        'points' => 0,
                        'cards' => [],
                    ],
                    'bet' => 0,
                    'profit' => 0,
                ]]
                );

        // Set up expectations for set method calls
        $desk->expects($this->once())
            ->method('set')
            // Mocked desk array
            ->with([[0, 1], [2, 3]]);

        $bank->expects($this->once())
            ->method('set')
            // Mocked bank array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

        $player->expects($this->once())
            ->method('set')
            // Mocked player array
            ->with([['hand' => [
                            'points' => 0,
                            'cards' => [],
                        ],
                        'bet' => 0,
                        'profit' => 0,
                    ]]);

         // Instantiate the Game21 object
        $game = new Game21($desk, $bank, $player);

         // Call the firstState method with the mock session object
        $data = $game->playerNewCard($session);
 
         // Assert that the returned data contains the expected keys
        $this->assertArrayHasKey('PlayerPoints', $data);
    }
}