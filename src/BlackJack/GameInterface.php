<?php

namespace App\BlackJack;

/**
 * Class BlacJack represents game logik (in the start of game) the game logic for Black Jack.
 *
 */
class GameInterface extends Game 
{

    /**
     * __constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * First two cards for players and one card to bank
     * 
     * @return array<string, array<string, mixed>>
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
     * get array with data for html routes
     * @return array<string, array<string, mixed>>
     */
    public function getGame(): array
    {
        $players = [];
        foreach($this->playing as $name => $player) {
            $players[$name] = $player->getHand();
        }
        
        $array = [
            'players' => $players,
            'bank' => $this->bank->gethand(),
        ];
        return $array;
    }
}
