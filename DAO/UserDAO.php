<?php 
    namespace DAO;

    use Models\User as User;
    use Models\UserProfile as UserProfile;
    use Models\UserRole as UserRole;
    use \Exception as Exception;

    class UserDAO
    {
        private $connection;
        private $tableName;

        public function __construct() {
            $this->tableName = "users";
        }

        public function add($user){
            
            $query = "INSERT INTO $this->tableName (id_user,email,pass,first_name,last_name,dni,user_type,role_description) 
                        VALUES (:id_user,:email,:pass,:first_name,:last_name,:dni,:user_type,:role_description)";
            $parameters["id_user"] = $user->getId();
            $parameters["email"] = $user->getEmail();
            $parameters["pass"] = $user->getPassword();
            $parameters["first_name"] = $user->getUserProfile()->getFirstName();
            $parameters["last_name"] = $user->getUserProfile()->getLastName();
            $parameters["dni"] = $user->getUserProfile()->getDni();
            $parameters["user_type"] = $user->getUserRole()->getUserType();
            $parameters["role_description"] = $user->getUserRole()->getDescription();
            try
            {
                $this->connection = Connection::getInstance();
                $this->connection->executeNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }


        public function getById($id_user){
            
            $query="SELECT * from $this->tableName where id_user=$id_user";
            
            try
            {    
                $this->connection=Connection::getInstance();
                $results=$this->connection->execute($query);

            }catch(Exception $ex){
                throw $ex;
            }
            
                if(!empty($results)){
                    $row=$results[0];

                    $user = new User($row["id_user"],$row["email"],$row["pass"],$row["first_name"],$row["last_name"],$row["dni"],$row["user_type"],$row["role_description"]);
                    
                    return $user;
                }
                else{
                    return null;
                }
                
        }


        public function findUser($email,$password){
            
            $query="SELECT * from $this->tableName where email=\"$email\" AND pass=\"$password\"";
            
            try
            {
                $this->connection=Connection::getInstance();
                $results=$this->connection->execute($query);

            }catch(Exception $ex){
                throw $ex;
            }

                if(!empty($results)){
                    $row=$results[0];

                    $user = new User($row["id_user"],$row["email"],$row["pass"],$row["first_name"],$row["last_name"],$row["dni"],$row["user_type"],$row["role_description"]);
                    
                    return $user;
                }
                else{
                    return false;
                }
        }


        public function findEmail($email){
            
            $query="SELECT * from $this->tableName where email=$email";
            
            try
            {
                $this->connection=Connection::getInstance();
                $results=$this->connection->execute($query);
            
            }catch(Exception $ex){
                throw $ex;
            }   
                if(!empty($results)){
                    
                    return true;
                }
                else{
                    return false;
                }
        }
    
    }
    
?>