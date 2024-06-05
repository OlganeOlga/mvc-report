<?php

namespace App\BlackJack;


/**
 * Class WinstCouter contain functions för endig of game and conting wins
 * for Black Jack.
 *
 */
class WinstCounter extends GameInterface 
{

    /**
     * __constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Count winst if bankfat
     * 
     * @return void
     */
     public function winstIfBankBlackJack(): void
    {         
        foreach ($this->playing as $player) {
            $status =  $player->getStatus();
            if (in_array($status, ['play', 'ready'])) {
                $player->insurance() === true ? $player->loosGame(1, 2) : $player->loosGame(1, 1);
                
            } elseif($player->getStatus() === 'Black Jack') {
                $player->loosGame(0, 1);
            } elseif ($status === 'fat') {
                $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
            }
        }
    }

    /**
     * Count winst if bank Black Jack
     * 
     * @return void
     */
     public function winstIfBankGet21(): void
    { 
        foreach ($this->playing as $player) {
           $status =  $player->getStatus();
            if(in_array($status,['play', 'ready', 'fat']) ){
                $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
            } elseif($player->getStatus() === 'Black Jack') {
                $player->winGame(3, 2);
            }
        }
    }

    /**
     * Count winst if bank has less than 21 points
     * 
     * @return void
     */

     public function winstIfBankPlay($bankPoints): void
    { 
        foreach ($this->getPlayers() as $player) {
            $pStatus =  $player->getStatus();
            $playPoints = $player->points();
            switch($pStatus){
                case 'Black Jack':
                    $player->winGame(3, 2);
                    break;
                case 'play':
                case 'ready':
                    if($playPoints > $bankPoints){
                        $player->insurance() === true ? $player->winGame(1, 2) : $player->winGame(1, 1);
                        break;
                    } 
                    $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
                        break;  
                case 'fat':
                    $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
                    break;
            }
        }
    }

    /**
     * Count winst if bank fat
     * 
     * @return void
     */

     public function winstIfBankFat(): void
    { 
        foreach ($this->getPlayers() as $player) {
            $pStatus =  $player->getStatus();
            switch($pStatus){
                case 'Black Jack':
                    $player->winGame(3, 2);
                    break;
                case 'play':
                case 'ready':
                    $player->insurance() === true ? $player->winGame(1, 2) : $player->winGame(1, 1);
                    break;
                case 'fat':
                    $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
                    break;
            }
        }
    }

    /**
     * Count winst based on bank status
     * 
     * @return void
     */
    public function countWinst(): void
    {
        $bankStatus = $this->bank->getStatus();
        switch($bankStatus) {
            case 'Black Jack':
                $this->winstIfBankBlackJack();
                break;
            case('win'):
                $this->winstIfBankGet21();
                break;
            case('fat'):
                $this->winstIfBankFat();
                break;
            case('play'):
                $bankpoints = $this->bank->points();
                $this->winstIfBankPlay($bankpoints);
                break;
            
        }
    }

    /**
     * see if game is over
     * @return bool
     */
    public function finish(): bool
    {   
        switch($this->gameStatus()) {
            case 'fat':
            case 'Black Jack':
            case '21':
            case 'ready':
                    return true;
            case 'no playing':
                while($this->bank->points() < 17) {
                    $this->bank->takeCard($this->desk);
                }
                return true;
            case 'play':
                return false;
            default:
                return false;
        }
    }
}
