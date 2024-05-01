<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Desk;
use App\Game21\Player;
use App\Game21\Bank;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Game21 represents the game logic for a simplified version of the card game 21.
 * 
 * The game involves a desk of cards, a player, and a bank. The game progresses through various states
 * such as initial setup, dealing cards, player actions, and bank actions.
 */
class Game21
{
    /**
     * @var Desk The desk of cards used in the game.
     */
    protected Desk $desk;

    /**
     * @var Player The player participating in the game.
     */
    protected Player $player;

    /**
     * @var Band The Band participating in the game.
     */
    protected Bank $bank;

    /**
     * @var string represents state of the game.
     */
    protected string $status;

    /**
     * Constructor method that initializes the game with default objects and sets the initial game status.
     *
     * @param Desk $desk The desk of cards used in the game.
     * @param Bank $bank The bank or dealer in the game.
     * @param Player $player The player participating in the game.
     */
    public function __construct(Desk $desk = new Desk, Bank $bank = new Bank, Player $player = new Player)
    {
        $this->desk = $desk;
        $this->desk->freshDesk();
        $this->bank = $bank;
        $this->player = $player;
        $this->status = "start";
    }

    /**
     * Saves the current game state in the session interface.
     *
     * @param SessionInterface $session The session interface used to store game state.
     * @return void
     */
    public function toSession(SessionInterface $session): void
    {
        $session->set('desk', $this->desk->toArray());
        $session->set('bank', $this->bank->toArray());
        $session->set('player', $this->player->toArray());
    }

    /**
     * Sets the game state based on the data retrieved from the session interface.
     *
     * @param SessionInterface $session The session interface used to retrieve game state data.
     * @return void
     */
    public function set(SessionInterface $session): void
    {
        $this->desk->set($session->get('desk'));
        $this->bank->set($session->get('bank'));
        $this->player->set($session->get('player'));
    }

    /**
     * Initiates the game by setting up the initial state and returning the desk of cards.
     *
     * @param SessionInterface $session The session interface used to store and retrieve game state.
     * @return array<string, mixed> An array containing the desk of cards.
     */
    public function firstState(SessionInterface $session): array     
    {
        $this->set($session);
        $this->toSession($session); 
        $data = [
            //'Status' => $this->status,
            'desk' => $this->desk->getDesk(),
        ];
        return $data;
    }

    /**
     * Shuffles the desk of cards and initiates the first round of the game by making a bet and dealing cards.
     *
     * @param SessionInterface $session The session interface used to store and retrieve game state.
     * @return array<string, mixed> An array containing the desk of cards, bank's bet, and player's cards.
     */
    public function secondState(SessionInterface $session): array
    {
        $this->set($session);
        $this->desk->shuffleDesk();
        $this->bank->doBet(rand(20, 25));
        $this->bank->dealCards($this->desk, [$this->player]);
        $this->toSession($session);
        $data = [
            'desk' => $this->desk->getDesk(),
            'bankBet' => $this->bank->getBet(),
            'playerCards' => $this->player->getHand()
        ];
        return $data;
    }

    /**
     * Processes the player's bet and updates the game state accordingly.
     *
     * @param SessionInterface $session The session interface used to store and retrieve game state.
     * @param int|null $bet The bet amount placed by the player.
     * @return array<string, mixed> An array containing the player's cards and points.
     */
    public function thirdState(SessionInterface $session, ?int $bet): array
    {
        $this->set($session);
        $this->player->doBet($bet);
        $this->toSession($session);
        $data = [
            'playerCards' => $this->player->getHand(),
            'PlayerPoints' => $this->player->points(),
        ];
        return $data;
    }

    /**
     * Deals an additional card to the player and determines the game status based on the player's points.
     *
     * @param SessionInterface $session The session interface used to store and retrieve game state.
     * @return array<string, mixed> An array containing the game status, player's cards, bank's cards, and points.
     */
    public function playerNewCard(SessionInterface $session): array
    {
        $this->set($session);
        $this->bank->dealCards($this->desk, [$this->player]);
        $points = $this->player->points();
        if($points > 21){
            $this->status = "fat player";
        } elseif ($points == 21) {
            $this->status = "player wins";
        }
        $this->toSession($session);
        $data = [
            'Status' => $this->status,
            'playerCards' => $this->player->getHand(),
            'bankCards' => $this->bank->gethand(),
            'PlayerPoints' => $points,
            'playerBet' => $this->player->getBet(),
            'BankPoints' => $this->bank->points()
        ];
        return $data;
    }

    /**
     * Initiates the bank's turn by dealing cards until the bank's points reach a certain threshold.
     *
     * @param SessionInterface $session The session interface used to store and retrieve game state.
     * @return array<string, mixed> An array containing the game status, bank's bet, player's cards, bank's cards, and points.
     */
    public function bankGetCards(SessionInterface $session): array
    {
        $this->set($session);
        $this->bank->takeCards($this->desk);
        $points = $this->bank->points();
        $this->status = "compare";
        if($points > 21){
            $this->status = "fat bank";
        } elseif ($points == 21) {
            $this->status = "bank wins";
        }
        $this->toSession($session);
        $data = [
            'Status' => $this->status,
            'bankBet' => $this->bank->getBet(),
            'playerCards' => $this->player->getHand(),
            'bankCards' => $this->bank->gethand(),
            'PlayerPoints' => $this->player->points(),
            'playerBet' => $this->player->getBet(),
            'BankPoints' => $points,
        ];
        return $data;
    }
}
