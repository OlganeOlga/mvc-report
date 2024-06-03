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

    // /**
    //  * @var array<string, Player> array of ready (won/loosed) players.
    //  */
    // protected $ready;

    // /**
    //  * @var string[] array with players names.
    //  */
    // protected $playersNames;

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
     * @param string players name
     * @return int
     */
    public function bankDeal(Player $player, string $name): int
    {
        if($player->getStatus() === 'play') {
            return $this->bank->dealCards($this->desk, $player);
        }
        
        return $player->points();
    }

    // /**
    //  * Count minimal amout cort in hand of players
    //  * 
    //  * @return int
    //  */
    // public function newCardToBank(): int
    // {  
    //     $active = $this->getPlaying();
    //     $minCardsByActive = 10;
    //     foreach($this->getPlaying as $player)
    //     { 
    //         $cards = $player->countCards();
    //         if ($minCardsByActive > $cards) {
    //             $minCardsByActive = $cards;
    //         }
    //     }
        
    //     if($minCardsByActive - 1 > $this->bank->countCards()) {
    //         $this->bank->takeCard($this->desk);
    //     }

    //     if (count($this->getPlaying()) === 0) {
    //         while($this->bank->points() < 17 ) {
    //             $this->bank->takeCard($this->desk);
    //         }
    //     }
    //     return $minCardsByActive;
    // }

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
    //  * First two cards for players and one card to bank
    //  * 
    //  * @return array{
    //  *     name: [
    //  *         hand: array,
    //  *         points: int,
    //  *         soft: bool],
    //  *     bank: [
    //  *         hand: array,
    //  *         points: int,
    //  *         soft: bool]
    //  *      }
    //  */
    // public function firstDeal(): array
    // {
    //     $players = [];
    //     foreach($this->playing as $player) {
    //         $this->bank->dealCards($this->desk, $player);
    //         $this->bank->dealCards($this->desk, $player);
    //     }
        
    //     $this->bank->takeCard($this->desk);
        
    //     foreach($this->playing as $name => $player) {
            
    //         if ($player->blackJack() && $this->bank->points() < 10){
    //             $player->winGame(3, 2);
    //         }

    //         $players[$name] = $player->getHand();
    //     }

    //     $array = [
    //         'players' => $players,
    //         'bank' => $this->bank->gethand(),
    //     ];
    //     return $array;
    // }

    // /**
    //  *
    //  * get array with data for html routes
    //  * @return array{
    //  *     players: [
    //  *      bet: int,
    //  *      hand: string[],
    //  *      points: int,
    //  *      soft: bool,
    //  *      status: string,
    //  *      insure: bool,
    //  *      blackJack: bool,
    //  *      split: bool,
    //  *      profit: int
    //  *  }[],
    //  *     bank: [bet: int,
    //  *      hand: string[],
    //  *      points: int,
    //  *      soft: bool,
    //  *      status: string,
    //  *      blackJack: bool,
    //  *      profit: int]}
    //  */
    // public function getGame(): array
    // {
    //     $players = [];
    //     foreach($this->playing as $player) {
    //         $status = $player->getStatus();
    //         $name = $player->getName();
    //         if(in_array($status, ['fat', 'win', 'loos', 'wait', 'ready'])) {
    //             unset($this->playing[$name]);
    //             $this->ready[$name] = $player;
    //         } 
    //         $players[$name] = $player->getHand();
    //     }

    //     foreach($this->ready as $player) {
    //         $players[$player->getName()] = $player->getHand();
    //     }
        
    //     $array = [
    //         'players' => $players,
    //         'bank' => $this->bank->gethand(),
    //     ];
    //     return $array;
    // }

    // /**
    //  * see if game is over
    //  * @return array<int, string|bool>
    //  */
    // public function finish(): array
    // {

    //     if ($this->bank->points() > 21) {
    //         // finish if bank get fat
    //         return ['bank fat', true];
    //     } elseif($this->bank->blackJack()) {
    //         // finish if bank get black jack
    //         return ['bank Black Jack', true];
    //     } elseif (count($this->playing) === 0) {
    //         $this->newCardToBank();
    //         return ['count playing', true];
    //     }
    //     return ['not finish', false];
    // }

    // /**
    //  * Count winst if bankfat
    //  * 
    //  * @return void
    //  */
    //  public function winstIfBankBlackJack(): void
    // { 
    //     foreach ($this->playing as $player) {
    //         if($player->insurance()) {
    //             $player->loosGame(1, 2);
    //         } 
    //         $player->loosGame(1, 1);
    //     }
        
    //     foreach ($this->ready as $player) {
    //         if($player->getStatus() === 'wait') {
    //             $player->loosGame(0, 1);
    //         } elseif ($player->getStatus() === 'ready') {
    //             if($player->insurance()) {
    //                 $player->loosGame(1, 2);
    //             } 
    //             $player->loosGame(1, 1);
    //         }
    //     } 
    // }

    // /**
    //  * Count winst if bank Black JAck
    //  * 
    //  * @return void
    //  */
    //  public function winstIfBankGet21(): void
    // { 
    //     foreach ($this->playing as $player) {
    //         $player->loosGame(1, 1);
    //     }
    
    //     foreach ($this->ready as $player) {
    //         if($player->getStatus() === 'wait') {
    //             $player->winGame(3, 2);
    //         } elseif ($player->getStatus() === 'ready') {
    //             $player->loosGame(1, 1);
    //         }
    //     }
    // }

    // /**
    //  * Count winst if bank has less than 21 points
    //  * 
    //  * @return void
    //  */

    //  public function winstIfBankLessThan21(): void
    // { 
    //     foreach ($this->playing as $player) {
    //         if($player->points() > $this->bank->points()){
    //             $player->winGame(1, 1);
    //         } 
    //         $player->loosGame(1, 1);
    //     }
    
    //     foreach ($this->ready as $player) {
    //         if($player->getStatus() === 'wait') {
    //             $player->winGame(3, 2);
    //         } elseif ($player->getStatus() === 'ready') {
    //             if($player->points() > $this->bank->points()){
    //                 $player->winGame(1, 1);
    //             } 
    //             $player->loosGame(1, 1);
    //         }
    //     }
    // }

    // /**
    //  * Count winst if bank fat
    //  * 
    //  * @return void
    //  */

    //  public function winstIfBankFat(): void
    // { 
    //     foreach ($this->playing as $player) {
    //         $player->winGame(1, 1);
    //     }

    //     foreach ($this->ready as $player) {
    //         if($player->getStatus() === 'wait') {
    //             $player->winGame(3, 2);
    //         } elseif ($player->getStatus() === 'ready') {
    //             $player->winGame(1, 1);
    //         }
    //         $player->loosGame(1, 1);
    //     }
    // }

    // /**
    //  * Count winst
    //  * 
    //  * @return void
    //  */
    // public function countWinst(): void
    // {
    //     $bankStatus = $this->bank->getStatus();
    //     switch($bankStatus){
    //         case('Black Jack'):
    //             $this->winstIfBankBlackJack();
    //             break;
    //         case('win'):
    //             $this->winstIfBankGet21();
    //             break;
    //         case('fat'):
    //             $this->winstIfBankFat();
    //             break;
    //         case('play'):
    //             $this->winstIfBankLessThan21();
    //             break;
            
    //     }
    // }
}
