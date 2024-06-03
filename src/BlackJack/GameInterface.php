<?php

namespace App\BlackJack;


/**
 * Class BlacJack represents the game logic for Black Jack.
 *
 */
class GameInterface extends Game {

    /**
     * __constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Count minimal amout cort in hand of players
     * 
     * @return int
     */
    public function newCardToBank(): int
    {  
        $active = $this->getPlaying();
        $minCardsByActive = 10;
        foreach($this->getPlaying() as $player)
        { 
            $cards = $player->countCards();
            if ($minCardsByActive > $cards) {
                $minCardsByActive = $cards;
            }
        }
        
        if($minCardsByActive - 1 > $this->bank->countCards()) {
            $this->bank->takeCard($this->desk);
        }

        if (count($this->getPlaying()) === 0) {
            while($this->bank->points() < 17 ) {
                $this->bank->takeCard($this->desk);
            }
        }
        return $minCardsByActive;
    }

    /**
     * First two cards for players and one card to bank
     * 
     * @return array{
     *     name: [
     *         hand: array,
     *         points: int,
     *         soft: bool],
     *     bank: [
     *         hand: array,
     *         points: int,
     *         soft: bool]
     *      }
     */
    public function firstDeal(): array
    {
        $players = [];
        foreach($this->playing as $player) {
            $this->bank->dealCards($this->desk, $player);
            $this->bank->dealCards($this->desk, $player);
        }
        
        $this->bank->takeCard($this->desk);
        
        foreach($this->playing as $name => $player) {
            
            if ($player->blackJack() && $this->bank->points() < 10){
                $player->winGame(3, 2);
            }

            $players[$name] = $player->getHand();
        }

        $array = [
            'players' => $players,
            'bank' => $this->bank->gethand(),
        ];
        return $array;
    }

    /**
     *
     * get array with data for html routes
     * @return array{
     *     players: [
     *      bet: int,
     *      hand: string[],
     *      points: int,
     *      soft: bool,
     *      status: string,
     *      insure: bool,
     *      blackJack: bool,
     *      split: bool,
     *      profit: int
     *  }[],
     *     bank: [bet: int,
     *      hand: string[],
     *      points: int,
     *      soft: bool,
     *      status: string,
     *      blackJack: bool,
     *      profit: int]}
     */
    public function getGame(): array
    {
        $players = [];
        foreach($this->playing as $player) {
            $status = $player->getStatus();
            $name = $player->getName();
            if(in_array($status, ['fat', 'win', 'loos', 'wait', 'ready'])) {
                unset($this->playing[$name]);
                $this->ready[$name] = $player;
            } 
            $players[$name] = $player->getHand();
        }

        foreach($this->ready as $player) {
            $players[$player->getName()] = $player->getHand();
        }
        
        $array = [
            'players' => $players,
            'bank' => $this->bank->gethand(),
        ];
        return $array;
    }

    /**
     * see if game is over
     * @return array<int, string|bool>
     */
    public function finish(): array
    {

        if ($this->bank->points() > 21) {
            // finish if bank get fat
            return ['bank fat', true];
        } elseif($this->bank->blackJack()) {
            // finish if bank get black jack
            return ['bank Black Jack', true];
        } elseif (count($this->playing) === 0) {
            $this->newCardToBank();
            return ['count playing', true];
        }
        return ['not finish', false];
    }

    /**
     * Count winst if bankfat
     * 
     * @return void
     */
     public function winstIfBankBlackJack(): void
    { 
        foreach ($this->playing as $player) {
            if($player->insurance()) {
                $player->loosGame(1, 2);
            } 
            $player->loosGame(1, 1);
        }
        
        foreach ($this->ready as $player) {
            if($player->getStatus() === 'wait') {
                $player->loosGame(0, 1);
            } elseif ($player->getStatus() === 'ready') {
                if($player->insurance()) {
                    $player->loosGame(1, 2);
                } 
                $player->loosGame(1, 1);
            }
        } 
    }

    /**
     * Count winst if bank Black JAck
     * 
     * @return void
     */
     public function winstIfBankGet21(): void
    { 
        foreach ($this->playing as $player) {
            $player->loosGame(1, 1);
        }
    
        foreach ($this->ready as $player) {
            if($player->getStatus() === 'wait') {
                $player->winGame(3, 2);
            } elseif ($player->getStatus() === 'ready') {
                $player->loosGame(1, 1);
            }
        }
    }

    /**
     * Count winst if bank has less than 21 points
     * 
     * @return void
     */

     public function winstIfBankLessThan21(): void
    { 
        foreach ($this->getPlayers() as $player) {
            if($player->getStatus() === 'wait') {
                $player->winGame(3, 2);
            } elseif (!in_array($player->getStatus(), ['win', 'loos']) && $player->points() > $this->bank->points()) {
                
                    $player->winGame(1, 1);
                } 
            $player->loosGame(1, 1);
        }
    }

    /**
     * Count winst if bank fat
     * 
     * @return void
     */

     public function winstIfBankFat(): void
    { 
        foreach ($this->playing as $player) {
            $player->winGame(1, 1);
        }

        foreach ($this->ready as $player) {
            if($player->getStatus() === 'wait') {
                $player->winGame(3, 2);
            } elseif ($player->getStatus() === 'ready') {
                $player->winGame(1, 1);
            }
            $player->loosGame(1, 1);
        }
    }

    /**
     * Count winst
     * 
     * @return void
     */
    public function countWinst(): void
    {
        $bankStatus = $this->bank->getStatus();
        switch($bankStatus){
            case('Black Jack'):
                $this->winstIfBankBlackJack();
                break;
            case('win'):
                $this->winstIfBankGet21();
                break;
            case('fat'):
                $this->winstIfBankFat();
                break;
            case('play'):
                $this->winstIfBankLessThan21();
                break;
            
        }
    }
}
