<?php

namespace Controllers;
use Lib\QR\QRcode;
use DAO\TicketDAO;
use Controllers\ProjectionController as ProjectionController;
use \Exception as Exception;

class TicketController{
    private $ticketDao;
    private $projectionController;

    public function __construct() {
        $this->ticketDao = new TicketDAO();
        $this->projectionController = new ProjectionController();
    }

    public function add($num,$idProj,$idPurchase){
        try{
            return $this->ticketDao->add($num, $idProj, $idPurchase);
        }
        catch(Exception $e){
            $message="Ticket not added.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getSoldTicketsByProjId($idProj){
        try{
            return $soldTickets = $this->ticketDao->getSoldTicketsByProjId($idProj);
            
        }
        catch(Exception $e){
            $message="Error getting cant SoldTickets.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getProjByIdTicket($idTicket){
        try{
            $id = $this->ticketDao->getProjIdByTicketId($idTicket);
            return $proj = $this->projectionController->getById($id);
        }
        catch(Exception $e){
            $message="Error getting projection.";
            include(VIEWS_PATH."message_view.php");
        }

    }

    public function getByProjId($idProj){
        try{
            $arr =$this->ticketDao->getByProjId($idProj);
            for ($i=0; $i <count($arr) ; $i++) { 
                $arr[$i]=$this->setTicketQr($arr[$i]);
            }
            return $arr;
        }
        catch(Exception $e){
            $message="Error getting tickets.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getByUserId($usrId){
        try{
            $arr =$this->ticketDao->getByUserId($usrId);
            for ($i=0; $i <count($arr) ; $i++) { 
                $arr[$i]=$this->setTicketQr($arr[$i]);
            }
            return $arr;
        }
        catch(Exception $e){
            return array();
        }
    }


    public function getByPurchaseId($purchId){
        try{
            $arr =$this->ticketDao->getByPurchaseId($purchId);
            for ($i=0; $i <count($arr) ; $i++) { 
                $arr[$i]=$this->setTicketQr($arr[$i]);
            }
            return $arr;
        }
        catch(Exception $e){
            $message="Error getting tickets.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function showTicketsByPurchaseId($purchId){
        $ticketsArray=$this->getByPurchaseId($purchId);
        $proj = $this->getProjByIdTicket($ticketsArray[0]->getId());
        include VIEWS_PATH."sold_tickets.php";
    }

    public function showTicketsByUserId(){
        session_start();
        $usrId=$_SESSION["Id"];
        $ticketsArray=$this->getByUserId($usrId);
        $projs = $this->projectionController->getAllProjectionsByUser($usrId);
        include VIEWS_PATH."myTickets.php";
    }

    private function setTicketQr($ticket){
        require_once(ROOT. 'phpqrcode/qrlib.php');
        $toEncode=$_SESSION["name"].$ticket->getId();
        $toEncode=str_replace(" ","",$toEncode);
        $path="/Views/img/qr/".$toEncode.".png";
        $root=ROOT.$path;
        $path="/MoviePass".$path;
        \QRcode::png($toEncode,$root,3,3,3);
        $ticket->setQr($path);
        return $ticket;
    }

}

?>