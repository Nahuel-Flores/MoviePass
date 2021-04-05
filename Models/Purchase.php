<?php 
namespace Models;

class Purchase{
    private $id;
    private $quantity_tickets;
    private $discount;
    private $date;
    private $total;
    
    public function __construct($id,$quantity_tickets,$discount,$date,$total) {
        $this->id = $id;
        $this->quantity_tickets = $quantity_tickets;
        $this->discount = $discount;
        $this->date = $date;
        $this->total = $total;
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

    /**
     * Get the value of quantity_tickets
     */ 
    public function getQuantityTickets()
    {
        return $this->quantity_tickets;
    }

    /**
     * Set the value of quantity_tickets
     *
     * @return  self
     */ 
    public function setQuantityTickets($quantity_tickets)
    {
        $this->quantity_tickets = $quantity_tickets;

        return $this->quantity_tickets;
    }

    /**
     * Get the value of discount
     */ 
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set the value of discount
     *
     * @return  self
     */ 
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
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
     * Get the value of total
     */ 
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */ 
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
}

?>