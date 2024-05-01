<?php

namespace App\Game21;

use App\Game21\CardGraphics;
//use App\Game21\Hand;
use App\Game21\Desk;
use App\Game21\Player;
use App\Game21\Bank;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game21
{
    protected Desk $desk;
    protected Player $player;
    protected Bank $bank;
    protected string $status;

    public function __construct(Desk $desk = new Desk, Bank $bank = new Bank, Player $player = new Player)
    {
        $this->desk = $desk;
        $this->desk->freshDesk();
        $this->bank = $bank;
        $this->player = $player;
        $this->status = "start";
    }

    /**
     * ToSession method saves the game state in the SessionInterfase
     * 
     * @return void
     */
    public function toSession(SessionInterface $session): void
    {
        $session->set('desk', $this->desk->toArray());
        $session->set('bank', $this->bank->toArray());
        $session->set('player', $this->player->toArray());
    }

    /**
     * Set method change properties of this according the values
     * get fromSessionInterfase $session
     * 
     * @return void
     */
    public function set(SessionInterface $session): void
    {
        $this->desk->set($session->get('desk'));
        $this->bank->set($session->get('bank'));
        $this->player->set($session->get('player'));
    }

    /**
     * function start game: shows desk, shows the desk, changes status of play
     * change session
     *
     * @param SessionInterface $session
     * @return array<string, mixed> Key-value array with key 'desk' and an array value.
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
     * function follows game: Shuffles desk, bank does bet and gives first card to the player
     * change session
     * @param SessionInterface $session
     * @return array<string, mixed> key-value array with keys 'desk', 'bankBet', 'playerCards'
     */
    public function secondState(SessionInterface $session): array
    {
        $this->set($session);
        $this->desk->shuffleDesk();
        $this->bank->doBet(rand(20, 25));
        $this->bank->dealCards($this->desk, [$this->player]);
        $this->toSession($session);
        $data = [
            //'Status' => $this->status,
            'desk' => $this->desk->getDesk(),
            'bankBet' => $this->bank->getBet(),
            'playerCards' => $this->player->getHand()
        ];
        return $data;
    }

    /**
     * function follows game: take players bet
     * change session
     * @param SessionInterface $session
     * @param int $bet represents bet of the player
     * @return array<string, mixed> key-value array with keys 'desk', 'playerPoints', 'playerCards'
     */
    public function thirdState(SessionInterface $session, ?int $bet): array
    {
        $this->set($session);
        $this->player->doBet($bet);
        $this->toSession($session);
        $data = [
            //'Status' => $this->status,
            'playerCards' => $this->player->getHand(),
            'PlayerPoints' => $this->player->points(),
        ];
        return $data;
    }

    /**
     * function follows game: gives player one card,
     * changes game status if players points >= 21;
     *  changes $sesion
     * change session
     * @param SessionInterface $session
     * @return array<string, mixed> key-value array with keys 'desk', 'bankBet', 'bankCards',
     * 'playerCards', 'playerBet', 'bankPoints'
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
     * function follows game: gives bank cards (while bank points < 17),
     * changes game status if banks points >= 21;
     * changes $sesion
     * change session
     * @param SessionInterface $session
     * @return array<string, mixed> key-value array with keys 'desk', 'bankBet', 'playerCards'
     * 'bankCards', 'playerBet', 'playerPoints', 'bankPoints'
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
