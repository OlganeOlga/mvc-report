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

    // /**
    //  * Look if bank should take new cards
    //  * 
    //  * @return bool
    //  */
    // public function newCardToBank(): bool
    // {
    //     $bankPoints = $this->bank->points();
    //     $bankcards = $this->bank->countCards();
    //     $active = $this->getPlaying();
    //     if($bankPoints < 17) {
    //         if(count($active) > 0) {   
    //             foreach($active as $player) { 
    //                 if ($bankcards + 1 >= $player->countCards()) {
    //                     return false;
    //                 } 
    //             }
    //             return true; 
    //         }
    //         return true;
    //     }
    //     return false;
    // }

    /**
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
        foreach($this->playing as $name => $player) {
            $players[$name] = $player->getHand();
        }
        
        $array = [
            'players' => $players,
            'bank' => $this->bank->gethand(),
        ];
        return $array;
    }

    // /**
    //  * Count winst if bankfat
    //  * 
    //  * @return void
    //  */
    //  public function winstIfBankBlackJack(): void
    // {         
    //     foreach ($this->playing as $player) {
    //         $status =  $player->getStatus();
    //         if (in_array($status, ['play', 'ready'])) {
    //             $player->insurance() === true ? $player->loosGame(1, 2) : $player->loosGame(1, 1);
                
    //         } elseif($player->getStatus() === 'Black Jack') {
    //             $player->loosGame(0, 1);
    //         } elseif ($status === 'fat') {
    //             $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
    //         }
    //     }
    // }

    // /**
    //  * Count winst if bank Black Jack
    //  * 
    //  * @return void
    //  */
    //  public function winstIfBankGet21(): void
    // { 
    //     foreach ($this->playing as $player) {
    //        $status =  $player->getStatus();
    //         if(in_array($status,['play', 'ready', 'fat']) ){
    //             $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
    //         } elseif($player->getStatus() === 'Black Jack') {
    //             $player->winGame(3, 2);
    //         }
    //     }
    // }

    // /**
    //  * Count winst if bank has less than 21 points
    //  * 
    //  * @return void
    //  */

    //  public function winstIfBankLessThan21($bankPoints): void
    // { 
    //     foreach ($this->getPlayers() as $player) {
    //         $pStatus =  $player->getStatus();
    //         $playPoints = $player->points();
    //         switch($pStatus){
    //             case 'Black Jack':
    //                 $player->winGame(3, 2);
    //                 break;
    //             case 'play':
    //             case 'ready':
    //                 if($playPoints > $bankPoints){
    //                     $player->insurance() === true ? $player->winGame(1, 2) : $player->winGame(1, 1);
    //                     break;
    //                 } 
    //                 $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
    //                     break;  
    //             case 'fat':
    //                 $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
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
    //     foreach ($this->getPlayers() as $player) {
    //         $pStatus =  $player->getStatus();
    //         $playPoints = $player->points();
    //         switch($pStatus){
    //             case 'Black Jack':
    //                 $player->winGame(3, 2);
    //                 break;
    //             case 'play':
    //             case 'ready':
    //                 $player->insurance() === true ? $player->winGame(1, 2) : $player->winGame(1, 1);
    //                 break;
    //             case 'fat':
    //                 $player->insurance() === true ? $player->loosGame(3, 2) : $player->loosGame(1, 1);
    //         }
    //     }
    // }

    // /**
    //  * Count winst based on bank status
    //  * 
    //  * @return void
    //  */
    // public function countWinst(): void
    // {
    //     $bankStatus = $this->bank->getStatus();
    //     switch($bankStatus) {
    //         case 'Black Jack':
    //             $this->winstIfBankBlackJack();
    //             break;
    //         case('win'):
    //             $this->winstIfBankGet21();
    //             break;
    //         case('fat'):
    //             $this->winstIfBankFat();
    //             break;
    //         case('play'):
    //             $bankpoints = $this->bank->points();
    //             $this->winstIfBankLessThan21($bankpoints);
    //             break;
            
    //     }
    // }

    // /**
    //  * see if game is over
    //  * @return bool
    //  */
    // public function finish(): bool
    // {   
    //     switch($this->gameStatus()) {
    //         case 'fat':
    //             return true;
    //         case 'Black Jack':
    //             return true;
    //         case '21':
    //             return true;
    //         case 'no playing':
    //             $this->newCardToBank();
    //             return true;
    //         default:
    //             return false;
    //     }
    // }
}
