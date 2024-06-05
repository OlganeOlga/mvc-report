<?php

namespace App\BlackJack;

use App\BlackJack\Desk;
use App\BlackJack\Player;
use App\BlackJack\Bank;

/**
 * Class BlacJack represents the game logic for Black Jack.
 *
 */
class Game {

    /**
     * @var Desk The desk of cards used in the game.
     */
    protected Desk $desk;

    /**
     * @var array<string, Player> array of activ players.
     */
    protected $playing;

    /**
     * @var Bank The Bank participating in the game.
     */
    protected Bank $bank;

    /**
     * Constructor method that initializes the game with default objects and sets the initial game status.
     *
     */
    public function __construct()
    {
        $this->desk = new Desk();
        $this->desk->freshDesk();
        $this->bank = new Bank();
        $this->playing = [];
        //$this->playersNames = [];
    }

    
    /**
     * Set bank.
     * 
     * @return void
     */
    public function setBank(Bank $newBank): void
    {
        $this->bank = $newBank;
    }

    /**
     * add player if a player split hand
     * 
     * @param string $name
     * @param Player $player Player object to add
     * @return void
     */
    public function addPlaying(string $name, Player $player): void
    {
        //$this->playersNames[] = $name;
        $this->playing[$name] = $player;
    }

    /**
     * Get players
     * @param string $name
     * @return Player
     */
    public function findPlayer(string $name): Player
    {
        return $this->playing[$name];
    }

    /**
     * Get desk in current status of the palay
     * 
     * @return string[]
     */
    public function getDesk(): array
    {
        return $this->desk->getDesk();
    }

    /**
     * create and returns banks bet
     *
     * @return int banks bet
     */
    public function bankBet(): int
    {
        $betPeng = random_int(25, 30);
        $this->bank->doBet($betPeng);
        return $this->bank->getBet();
    }

    // /**
    //  * create and returns banks bet
    //  *
    //  * @param Player $player
    //  * @return void
    //  */
    // public function deal(Player $player): void
    // {
    //     $this->bank->dealCards($this->desk, $player);
    // }

    /**
     * Get players as array
     * 
     * @return array<string, Player>
     */
    public function getPlayers(): array
    {
        return $this->playing;
    }

    /**
     * Get active players as array
     * 
     * @return array<string, Player>
     */
    public function getPlaying(): array
    {   $active = [];
        foreach($this->playing as $name => $player) {
            if($player->getStatus() === 'play')
            {
                $active[$name] = $player;
            }
        }
        return $active;
    }

    /**
     * Shuffles all cards in the deck.
     *
     * @return void
     */
    public function shuffleDesk(): void
    {
        $this->desk->shuffleDesk();
    }

    /**
     * Deal cards for a player.
     * 
     * @param Player $player
     * @return int
     */
    public function bankDeal(Player $player): int
    {
        if($player->getStatus() === 'play') {
            $this->bank->dealCards($this->desk, $player);
            return $player->points();
        }
        
        return $player->points();
    }

    /**
     * Get bank.
     * 
     * @return Bank
     */
    public function getBank(): Bank
    {
        return $this->bank;
    }

    // /**
    //  * Get bank.
    //  * 
    //  * @return string Bankstatsu
    //  */
    // public function getBankStatus(): string
    // {
    //     return $this->bank->getStatus();
    // }

    /**
     * check if player can win with his black jack
     * @return string status of play
     */
    public function gameStatus(): string
    {
        if ($this->bank->blackJack()) 
        {
            $this->bank->setStatus('Black Jack');
        }
        $status = $this->bank->getStatus();
        
        if($status === 'play' && count($this->getPlaying()) === 0) {
            $status = 'no playing';
        }
        return $status;
    }

}
