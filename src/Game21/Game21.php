<?php

namespace App\Game21;

use App\Game21\CardGraphics;
//use App\Game21\Hand;
use App\Game21\Desk;
use App\Game21\Player;
use App\Game21\Bank;
use Symfony\Component\HttpFoundation\Session\Session;

class Game21
{
    protected $desk;
    protected $player;
    protected $bank;
    protected $status;

    public function __construct(Desk $desk, Bank $bank, Player $player)
    {
        if($desk->toArray() === []) {
            $desk->freshDesk();
        }

        $this->desk = $desk;
        $this->bank = $bank;
        $this->player = $player;
        $this->status = "start";
    }

    public function toSession(Session $session)
    {
        $session->set('desk', $this->desk->toArray());
        $session->set('bank', $this->bank->toArray());
        $session->set('player', $this->player->toArray());
    }

    public function set(Session $session)
    {
        $this->desk->set($session->get('desk'));
        $this->bank->set($session->get('bank'));
        $this->player->set($session->get('player'));
    }

    /**
     * //FROM PLAY
     * function start game: shows desk, shows the desk, changes status of play
     * change session
     * @param {session} Session
     * @return {data}: array
     */
    public function firstState(Session $session): array
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
     * @param {session} Session
     * @return {data}: array
     */
    public function secondState(Session $session): array
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
     * @param {session} Session
     * @param {bet} integer: bet of the player
     * @return {data}: array
     */
    public function thirdState(Session $session, int $bet): array
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
     * @param {session} Session
     * @return {data}: array
     */
    public function playerNewCard(Session $session): array
    {
        $this->set($session);
        $this->bank->dealCards($this->desk, [$this->player]);
        $points = $this->player->points();
        if($points > 21) {
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
     * @param {session} Session
     * @return {data}: array
     */
    public function bankGetCards(Session $session): array
    {
        $this->set($session);
        $this->bank->takeCards($this->desk);
        $points = $this->bank->points();
        $this->status = "compare";
        if($points > 21) {
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
