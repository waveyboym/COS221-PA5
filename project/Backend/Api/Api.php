<?php
/**
*@file Config.php
*@class config
*@authors Michael, Jaden Jaide-Maree Add your name here if you write code in this file
*@brief allows us to talk to the database
*/
include "../Config/Config.php";

/**
*@brief request type that the user made when a call was made to this api
*/
enum REQUESTYPE: string
{
    case REGISTER = 'REGISTER';
    case LOGIN = 'LOGIN';
    case GET_WINERIES = 'GET_WINERIES';
    case GET_WINE = 'GET_WINE';
    case GET_APPELLATION = 'GET_APPELLATION';
    case GET_VARIETAL = 'GET_VARIETAL';
    case GET_COUNTRY = 'GET_COUNTRY';
    
    /**Add more cases */
}

/**
*@brief error types for a more structured way of defining and sending errors back to the client
*/
enum ERRORTYPES: int
{
    case INVALIDEMAIL = 1;//Invalid user email
    case INVALIDPASSWORD = 2;//Invalid user password
    case NULLUSER = 3;//No user exists in the database with the given email
    case WRONGPASSWORD = 4;//Wrong password
    case USERNAMETAKEN = 5;//Username is unavailable
    case INCORRECTSORT = 6;//unsupported sort parameter given
    /**Add more cases */
}


/**
*@brief allows us to talk to the database
*/
class Api extends config{

    /**
    *@brief creates a static instance of this class and returns it (PLEASE DON'T MODIFY UNLESS ABSOLUTELY NECESSARY)
    *@param none
    *@return Api
    */
    public static function instance(){
        static $Instance = null;
        if($Instance === null)$Instance = new api();
        return $Instance;
    }

    /**
    *@brief handles the logging in of the user to the backend by checking if they exist on the backend, and checking that their password matches
    *@param UserEmail carries the users email address
    *@param UserPassword carries the users password
    *@return void
    */
    public function loginUser($UserEmail, $UserPassword){
        if(!filter_var($UserEmail, FILTER_VALIDATE_EMAIL)){
            return $this->constructResponseObject($this->createError(ERRORTYPES::INVALIDEMAIL), "error");
        }
        if(!preg_match("/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*?><.,;:]{8,}$/", $UserPassword)){
            return $this->constructResponseObject($this->createError(ERRORTYPES::INVALIDPASSWORD), "error");
        }

        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('SELECT Username FROM USER WHERE Username = ? AND Password = ?');
        
        //hash password first using algorithm (TBD) before adding as param for stmt execution
        $success = $stmt->execute(array($UserEmail, $UserPassword));

        if($success && $stmt->fetchColumn() != 0){
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject($this->createError(ERRORTYPES::NULLUSER), "error");
        }
    }

    public function registerUser($Username, $email, $pswrd){
        if(!filter_var($UserEmail, FILTER_VALIDATE_EMAIL)){
            return $this->constructResponseObject($this->createError(ERRORTYPES::INVALIDEMAIL), "error");
        }
        if(!preg_match("/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*?><.,;:]{8,}$/", $UserPassword)){
            return $this->constructResponseObject($this->createError(ERRORTYPES::INVALIDPASSWORD), "error");
        }

        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT UserID WHERE Username = ?");
        $success = $stmt->execute($Username);

        if($success && $stmt->fetchColumn() == 0){
            $stmt = $conn->prepare(/**INSERT query*/);
            $success = $stmt->execute($Username, $email, $pswrd);

            if($success){
                return $this->constructResponseObject("", "success");
            }
        }
        else{
            return $this->constructResponseObject($this->createError(ERRORTYPES::USERNAMETAKEN), "error");
        }
    }

    public function getAppellations(){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT appellation FROM wine GROUP BY appellation");
        $stmt->execute();
        $data = json_encode($stmt->fetchAll());
        return $this->constructResponseObject($data, "success");
    }

    public function getVarietals(){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT varietal FROM wine GROUP BY varietal");
        $stmt->execute();
        $data = json_encode($stmt->fetchAll());
        return $this->constructResponseObject($data, "success");
    }

    public function getCountries(){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT country FROM country");
        $stmt->execute();
        $data = json_encode($stmt->fetchAll());
        return $this->constructResponseObject($data, "success");
    }

    public function getWines($USERREQUEST){

        //SORTS
        $sort = false;
        $ORDERBY = "";
        if(isset($USERREQUEST->sort)){
            $options = array("price_amount", "pointScore", "alcohol_percentage", "vintage", "year_bottled");
            if(!in_array($options, $USERREQUEST->sort)){
                return array("status" => "error","data" => $this->createError(ERRORTYPES::INCORRECTSORT));
            }
            else{
                $ORDERBY = " ORDER BY :order";
                $sort = true;
            }  
        }

        //FILTERS
        $filterchecks = array('appellation'=>false, 'varietal'=>false, 'colour'=>false, 'carbonation'=>false, 'sweetness'=>false, 'country'=>false);
        $WHERE_CLAUSES = array();
        $JOIN = "";
        if(isset($USERREQUEST->filters)){
            $filters = $USERREQUEST->filters;
            
            for($i = 0; $i < sizeof($filterchecks) - 1; $i++){ //sizeof - 1 to exlude country
                $current = array_keys($filterchecks)[$i];
                if(isset($filters->$current)){
                    $WHERE_CLAUSES[] = "$current = :$current";
                    $filterchecks[$i] = true;
                }
            }

            if(isset($filters->country)){
                $WHERE_CLAUSES[] = "region.country = :country";
                $JOIN = "JOIN winery ON wine.wineryID = winery.wineryID JOIN location ON winery.locationID = location.locationID JOIN region ON region.regionID = location.regionID";
            }
        }
        $WHERE = implode(" AND ", $WHERE_CLAUSES);

        //Statement
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM WINES $JOIN $WHERE $ORDERBY");

        //bindings
        if($sort == true){
            $stmt.bindParam(':order', $USERREQUEST->sort);
        }
        for($i = 0; $i < sizeof($filterchecks); $i++){
            if(array_values($filterchecks)[$i] == true){
                $stmt.bindParam(":" . array_keys($filterchecks)[$i], $filters->array_keys($filterchecks)[$i]); 
            }
        }

        $stmt->execute();
        $data = json_encode($stmt->fetchAll());
        return constructResponseObject($data, "success");
  
    }

    public function searchWine($name){
        return null;
    }

    public function getWineries($req_info){
        // -Can get winery data
        //     -All 
        //     -All sorted by location
        //     -All south african (if user is foreign)
        //     -All foreign (if user is southAfrican)
        //     -All sorted by distance away from user

        if(isset($req_info->location)){
            //ORDER BY distance using lat and long
        }
        if($req_info->SouthAfrican == true){
            //where country NOT SA
        }
        else{
            //where country is SA
        }
    }
    

    public function searchWinery($name){
        return null;
    }

    /**
    *@brief Creates an error based on the passed in parameter error type
    *@param errortype the error type
    *@return string
    */
    private function createError($errortype){
        if($errortype == ERRORTYPES::INVALIDEMAIL)return "Invalid email";
        else if($errortype == ERRORTYPES::INVALIDPASSWORD)return "Invalid password";
        else if($errortype == ERRORTYPES::NULLUSER)return "No user exists with this email address";
        else if($errortype == ERRORTYPES::WRONGPASSWORD)return "The password for this account is wrong";
        else if($errortype == ERRORTYPES::USERNAMETAKEN)return "Username is unavailable";
        else if($errortype == ERRORTYPES::INCORRECTSORT)return "Given sort value is not supported";
    }
    
    /**
    *@brief Creates an error based on the passed in parameter error type
    *@param $desc description can be a message or an array object or anything as long as it's JSON encodable. Default value is "Error. Post parameters are missing"
    *@param $status is the status of the response, error or success. Default value is "error"
    *@return string
    */
    private function constructResponseObject($desc = "Error. Post parameters are missing", $status = "error"){
        $value = array(
            "status"=> $status,
            "timestamp" => time(),
            "data" => $desc
        );
        $value = json_encode($value);

        return $value == false ? "" : $value;
    }
}

/**
*@brief creates an instance of the api
*/
$apiconfig = api::instance();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $json = file_get_contents('php://input');
    $USERREQUEST = json_decode($json);

    if($USERREQUEST->type == REQUESTYPE::REGISTER->value){
        echo $apiconfig->registerUser($data->username, $data->email, $data->password);
    }
    else if($USERREQUEST->type == REQUESTYPE::LOGIN->value){
        echo $apiconfig->loginUser($data->email, $data->password);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_WINE->value){
        //echo $apiconfig->getWines($/**add missing parameters */);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_WINERIES->value){
        //echo $apiconfig->getWineries($/**add missing parameters */);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_WINE){
        $res = $apiconfig->getWines($USERREQUEST);
        echo $res;
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_APPELLATION){
        $res = $apiconfig->getAppellations();
        echo $res;
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_VARIETAL){
        $res = $apiconfig->getVarietals();
        echo $res;
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_COUNTRY){
        $res = $apiconfig->getCountries();
        echo $res;
    }
    else if($USERREQUEST->type == REQUESTYPE::SEARCH_WINERY){
        $res = $apiconfig->searchWinery($USERREQUEST->$name);
        echo $res;
    }
    else if($USERREQUEST->type == REQUESTYPE::SEARCH_WINE){
        $res = $apiconfig->searchWine($USERREQUEST->$name);
        echo $res;
    }
    
}
