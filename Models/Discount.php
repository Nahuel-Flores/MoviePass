<?php

namespace Models;


class Discount{
    private $id;
    private $percent;
    private $date;
    private $creditAccount;

    public function __construct($id,$percent,$date,$creditAccount) {
        $this->id=$id;
        $this->percent=$percent;
        $this->date=$date;
        $this->creditAccount=$creditAccount;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
            return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
            $this->date = $date;

            return $this;
    }

    /**
     * Get the value of creditAccount
     */ 
    public function getCreditAccount()
    {
            return $this->creditAccount;
    }

    /**
     * Set the value of creditAccount
     *
     * @return  self
     */ 
    public function setCreditAccount($creditAccount)
    {
            $this->creditAccount = $creditAccount;

            return $this;
    }

    /**
     * Get the value of percent
     */ 
    public function getPercent()
    {
            return $this->percent;
    }

    /**
     * Set the value of percent
     *
     * @return  self
     */ 
    public function setPercent($percent)
    {
            $this->percent = $percent;

            return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
            return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
            $this->id = $id;

            return $this;
    }
}

?>