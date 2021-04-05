<?php

    namespace Controllers;

    use DAO\PurchaseDAO as PurchaseDAO;
    use Controllers\TicketController as TicketController;
    use Controllers\ProjectionController as ProjectionController;
    use Controllers\DiscountController as DiscountController;
    use Controllers\CreditAccountController as CreditAccountController;
    use Controllers\PaymentCCController as PaymentCCController;
    use Models\Projection as Projection;
    use Models\Movie as Movie;
    use Models\Room as Room;
    use Models\Cinema as Cinema;
    use Models\Purchase as Purchase;
    use Models\Ticket as Ticket;
    use Models\PaymentCC as PaymentCC;
    use Models\Discount;
    use \Exception as Exception;


    class PurchaseController{
        private $purchaseDAO;
        private $ticketController;
        private $projectionController;
        private $discountController;
        private $creditAccountController;
        private $paymentCCController;

        public function __construct() {
            $this->purchaseDAO = new PurchaseDao();
            $this->ticketController = new TicketController();
            $this->projectionController = new ProjectionController();
            $this->discountController = new DiscountController();
            $this->creditAccountController = new CreditAccountController();
            $this->paymentCCController = new PaymentCCController();
        }
        
        public function processPurchase($quantity_tickets,$id_proj){
            $soldTickets = $this->ticketController->getSoldTicketsByProjId($id_proj);
            $proj = $this->projectionController->getById($id_proj);
            $capacity = $proj->getRoom()->getCapacity();

            $availableTickets = $capacity - $soldTickets;

            if ($quantity_tickets > 0 && $availableTickets >= $quantity_tickets) {
                $date = date("Y-m-d");

                $discountsArray = $this->discountController->getByDate($date);
                $creditAccounts = $this->creditAccountController->getAll();
                
                if ($discountsArray == null) {
                    $discountsArray = array();
                }
                include(VIEWS_PATH."payments_view.php");
            }
            else {
                if($quantity_tickets = 0){
                    $message = "Error the number of tickets to buy is 0";
                }
                else{
                    $title = $proj->getMovie()->getTitle();
                    $message = "There are only $availableTickets tickets available for $title";
                }
                    
                $this->selectProj($id_proj,$message);
            }
        }



        public function selectProj($id_proj,$message=""){
            $proj = $this->projectionController->getById($id_proj);
        
            $room = $proj->getRoom();
            $movie = $proj->getMovie();
            $cinema = $room->getCinema();
            
            include VIEWS_PATH."purchase_form.php";
        }

        public function completePurchase($id_creditAccount,$cardNumber,$aut_cod,$quantity_tickets,$id_proj){
            $proj = $this->projectionController->getById($id_proj);
            $ticketPrice = $proj->getRoom()->getTicketPrice();
            $total = $quantity_tickets * $ticketPrice;
            $date = date("Y-m-d");
            $discountOBJ=$this->discountController->getByDateAccount($date,$id_creditAccount);
            $porc_discount=($discountOBJ==false)? 0 : $discountOBJ->getPercent();
            $final = ($total / 100 ) * (100 - $porc_discount);

            $id_purch = $this->sendPurchase($id_creditAccount,$quantity_tickets,$porc_discount,$date,$total,$aut_cod,$id_proj);

            $this->ticketController->showTicketsByPurchaseId($id_purch);           

            
        }


        public function sendPurchase($id_creditAccount,$quantity_tickets,$discount,$date,$total,$aut_code,$id_proj) {
            $soldTickets = $this->ticketController->getSoldTicketsByProjId($id_proj);
            $proj = $this->projectionController->getById($id_proj);
            $capacity = $proj->getRoom()->getCapacity();

            $availableTickets = $capacity - $soldTickets ;//Usar soldTickets ++ para num asiento
 
            $id_purchase = $this->add($quantity_tickets,$discount,$date,$total);
            
            $this->paymentCCController->add($id_purchase,$id_creditAccount,$aut_code,$date,$total);

            $ticketsArray = array();

            for ($i=0; $i <  $quantity_tickets; $i++) { 
                $soldTickets++;
                $idTicket = $this->ticketController->add($soldTickets,$id_proj,$id_purchase);
            }
            
            return $id_purchase;
        }

        public function add($quantity_tickets,$discount,$date,$total) {
            if(session_status () != 2){
                session_start();  
            }
            $id_user = $_SESSION['Id'];
            $id_purchase="";
            try
            {   
                $id_purchase = $this->purchaseDAO->add($id_user,$quantity_tickets,$discount,$date,$total);
            }
            catch(Exception $ex)
            {
                $message="Error adding the purchase.";
                include(VIEWS_PATH."message_view.php");
            }
            return $id_purchase;
            
        }

        

        public function getById($id_ticket){
            $id_purchase = 0;// con el ticket se busca id compra y se pasa por aca
            
            try
            {
                $this->getById($id_purchase);
            }
            catch(Exception $ex)
            {
                $message="Error getting byId the purchase.";
                include(VIEWS_PATH."message_view.php");
            }

        }  
        
        public function totalSoldByMovie($movieId,$date1,$date2){
            try{
                return $this->purchaseDAO->totalSoldByMovie($movieId,$date1,$date2);
            }
            catch(Exception $ex){
                return 0;
            }
        }

        public function showCinemaStats($cinemaId){
            include(VIEWS_PATH."cinemaStats.php");
        }

        public function showMovieStats($movieId){
            include(VIEWS_PATH."movieStats.php");
        }

        public function totalSoldByMovieJson($movieId,$date1,$date2){
            $total=$this->totalSoldByMovie($movieId,$date1,$date2);
            echo json_encode($total,1);
        }


        public function totalSoldByCinema($cinemaId,$date1,$date2){
            try{
                return $this->purchaseDAO->totalSoldByCinema($cinemaId,$date1,$date2);
            }
            catch(Exception $ex){
                return 0;
            }
        }

        public function totalSoldByCinemaJson($cinemaId,$date1,$date2){
            $total=$this->totalSoldByCinema($cinemaId,$date1,$date2);
            echo json_encode($total,1);
        }
        

    }

?>