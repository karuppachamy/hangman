<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping;

/**
 * @Entity
 * @Table(name="game")
 */

class Game
{
    const GAME_STATUS_SUCCESS = 2;

    const GAME_STATUS_FAILURE = 1;

    const GAME_STATUS_BUSY = 0;

    const SUCCESS = 'Success';

    const FAILURE = 'Failure';

    const BUSY = 'Busy';

    private $statusDescription = array(
        self::GAME_STATUS_FAILURE => self::FAILURE,
        self::GAME_STATUS_SUCCESS => self::GAME_STATUS_SUCCESS,
        self::GAME_STATUS_BUSY => self::BUSY
    );
    /**
     * @var integer
     *
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Column(name="word", type="string", length=64)
     */
    private $word;

    /**
     * @var string
     * @Column(name="tries", type="integer", length=64)
     */
    private $tries;

    /**
     * @var string
     * @Column(name="guessword", type="string", length=64)
     */
    private $guessWord;
    
    /**
     *
     * @var integer
     * @Column(name="status", type="integer", length=2)
     */
    private $status;

    const MAX_TRIES = 11;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $tries
     */
    public function setTries($tries)
    {
        $this->tries = $this->tries + $tries;
    }

    /**
     * @return mixed
     */
    public function getTries()
    {
        return $this->tries;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $guessWord
     */
    public function setGuessWord($guessWord)
    {
        $this->guessWord = $guessWord;
    }

    /**
     * @return string
     */
    public function getGuessWord()
    {
        return $this->guessWord;
    }

    public function isSuccess()
    {
        return ($this->getWord() == $this->getGuessWord());
    }

    public function isFailure()
    {
        return (self::MAX_TRIES - $this->getTries() == 0);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function toArray()
    {
        $data = array();

        $data['id'] = $this->getId();
        $data['word'] = $this->getWord();
        $data['guessWord'] = $this->getGuessWord();
        $data['tries'] = $this->getTries();
        $data['remainingTries'] = self::MAX_TRIES - $this->getTries();
        $data['success'] = $this->isSuccess();
        $data['failure'] = $this->isFailure();
        $data['status'] = $this->statusDescription[$this->getStatus()];
        
        return $data;
    }
}
