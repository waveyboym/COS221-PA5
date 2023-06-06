<?php
/**
*@file Config.php
*@class config
*@authors Michael, Jaden, Jaide-Maree Add your name here if you write code in this file
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
    case LOGIN_ADMIN = 'LOGIN_ADMIN';
    case LOGIN_MANAGER = 'LOGIN_MANAGER';
    case LOGOUT = "LOGOUT";
    case GET_WINERIES = 'GET_WINERIES';
    case GET_WINE = 'GET_WINE';
    case GET_VARIETAL = 'GET_VARIETAL';
    case GET_COUNTRY = 'GET_COUNTRY';
    case SEARCH_WINERY = 'SEARCH_WINERY';
    case SEARCH_WINE = 'SEARCH_WINE';
    case DELETE_ACCOUNT = 'DELETE_ACCOUNT';
    case UPDATE_USERNAME = 'UPDATE_USERNAME';
    case UPDATE_PASSWORD = 'UPDATE_PASSWORD';
    case GET_USER_REVIEWS = 'GET_USER_REVIEWS';
    case INSERT_REVIEW = 'INSERT_REVIEW';
    case UPDATE_REVIEW = 'UPDATE_REVIEW';
    case DELETE_REVIEW = 'DELETE_REVIEW'; 
    case GET_REVIEW_COUNT = 'GET_REVIEW_COUNT';
    case GET_WINERY_ADMIN = 'GET_WINERY_ADMIN';
    case GET_MANAGERS_ADMIN = 'GET_MANAGERS_ADMIN';
    case OPEN_WINERY_ADMIN = 'OPEN_WINERY_ADMIN';
    case ADD_WINERY_ADMIN = 'ADD_WINERY_ADMIN';
    case DELETE_WINERY_ADMIN = 'DELETE_WINERY_ADMIN';
    case OPEN_WINERY = 'OPEN_WINERY';
    case OPEN_WINE = 'OPEN_WINE';
    case LOAD_MORE_WINES = 'LOAD_MORE_WINES';
    case GET_WINE_REVIEWS = 'GET_WINE_REVIEWS';
    case LOAD_MANAGER_DATA = 'LOAD_MANAGER_DATA';
    case ADD_WINE = 'ADD_WINE';
    case EDIT_WINE = 'EDIT_WINE';
    case DELETE_WINE = 'DELETE_WINE';
    /**Add more cases */
}

/**
*@brief error types for a more structured way of defining and sending errors back to the client
*/
enum ERRORTYPES: string
{
    case INVALIDEMAIL = 'Invalid email';//Invalid user email
    case INVALIDPASSWORD = 'Invalid password';//Invalid user password
    case SAMEPASSWORD = 'Password entered is the same as the one in use';//Password entered is the same as the one in use
    case NULLUSER = 'Incorrect email or password';//incorrect email or password
    case WRONGPASSWORD = 'The password for this account is wrong';//Wrong password
    case USERNAMETAKEN = 'Username is unavailable';//Username is unavailable
    case EMAILTAKEN = 'Email is unavailable';//Email is unavailable
    case INCORRECTSORT = 'Given sort value is not supported';//unsupported sort parameter given
    case NONAME = 'Name is a required field';//no name given for search
    case ISMANAGER = 'Manager cannot login as a tourist';//Manager cannot login as a tourist
    /**Add more cases */
}


/**
*@brief allows us to talk to the database
*/
class Api extends config{

    /**
    *@brief creates a static instance of this class and returns it (PLEASE DON'T MODIFY UNLESS ABSOLUTELY NECESSARY)
    *@param $none
    *@return Api
    */
    public static function instance(){
        static $Instance = null;
        if($Instance === null)$Instance = new api();
        return $Instance;
    }

    /**
    *@brief handles the logging in of the user to the backend by checking if they exist on the backend, and checking that their password matches
    *@param $UserEmail carries the users email address
    *@param $UserPassword carries the users password
    *@return string
    */
    public function loginUser($UserEmail, $UserPassword){
        if(!filter_var($UserEmail, FILTER_VALIDATE_EMAIL)){
            return $this->constructResponseObject(ERRORTYPES::INVALIDEMAIL->value, "error");
        }
        
        /*if(!preg_match("/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*?><.,;:]{8,}$/", $UserPassword)){
            return $this->constructResponseObject(ERRORTYPES::INVALIDPASSWORD->value, "error");
        }*/

        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('SELECT username, userID FROM user WHERE email = ? AND Password = ?');
        
        $hashedPass = hash("sha256", $UserPassword, false);

        $success = $stmt->execute(array($UserEmail, $hashedPass));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($success && $stmt->rowCount() != 0){
            $stmt2 = $conn->prepare('SELECT userID FROM tourist WHERE userID = ?');
            $userID = $row['userID'];
        
            $success = $stmt2->execute(array($userID));

            if($success && $stmt2->rowCount() != 0){
                return $this->constructResponseObject($stmt->fetchAll(), "success");
            }
            else{
                return $this->constructResponseObject(ERRORTYPES::ISMANAGER->value, "error");
            }
        }
        else{
            return $this->constructResponseObject(ERRORTYPES::NULLUSER->value, "error");
        }
    }

    /**
    *@brief handles the logging in of the admin to the backend by checking if they exist on the database, and checking that the key matches and setting session variables
    *@param $AdminKey secret key of admin
    *@return string
    */
    public function loginAdmin($AdminKey){
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT adminkey FROM admin WHERE adminkey = ?;");
        $success = $stmt->execute(array($AdminKey));

        if($success && $stmt->rowCount() > 0){
            session_start();
            $_SESSION["adminkey"] = $AdminKey;
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject("Database connection has failed or no admin exists", "error");
        }
    }

    /**
    *@brief handles the logging in of the manager to the backend by checking if they exist on the database and setting session variables
    *@param $ManagerUsername username of manager
    *@param $ManagerPassword password of manager
    *@return string
    */
    public function loginManager($ManagerUsername, $ManagerPassword){
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT userID, username FROM user WHERE email = ? AND Password = ?");
        $hashedPass = hash("sha256", $ManagerPassword, false);
        $success = $stmt->execute(array($ManagerUsername, $hashedPass));

        if($success && $stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            //use return data over here
            foreach($result as $valuesToOutput){
                if(isset($valuesToOutput['userID']) && isset($valuesToOutput['username'])){
                    $stmt = $conn->prepare("SELECT userID FROM winery_manager WHERE userID = ?;");
                    $success = $stmt->execute(array($valuesToOutput['userID']));

                    if($success && $stmt->rowCount() > 0){
                        session_start();
                        $_SESSION["managerkey"] = $valuesToOutput['userID'];
                        $_SESSION["managerusername"] = $valuesToOutput['username'];
                        return $this->constructResponseObject("", "success");
                    }
                    else{
                        return $this->constructResponseObject("Database connection has failed or no manager exists", "error");
                    }
                }
                else{
                    return $this->constructResponseObject("Database connection has failed or no manager exists", "error");
                }
            }
            return $this->constructResponseObject("Database connection has failed or no manager exists", "error");
        }
        else{
            return $this->constructResponseObject("Database connection has failed or no manager exists", "error");
        }
    }

    /**
    *@brief Logs out a user by destroying their sessions
    *@param $none
    *@return void
    */
    public function logout(){
        session_start();
        session_unset();
        session_destroy();
    }

    /**
    *@brief Creates a new user on the backend
    *@param $Username username of a new user
    *@param $email email of a user
    *@param $pswrd password of a user
    *@param $isSouthAfrican whether or not the user is a south african tourist
    *@return string
    */
    public function registerUser($Username, $email, $pswrd, $isSouthAfrican){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return $this->constructResponseObject(ERRORTYPES::INVALIDEMAIL->value, "error");
        }
        if(!preg_match("/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*?><.,;:]{8,}$/", $pswrd)){
            return $this->constructResponseObject(ERRORTYPES::INVALIDPASSWORD->value, "error");
        }

        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?"); //Check if username is taken
        $success = $stmt->execute(array($Username));

        if($success && $stmt->rowCount() == 0){

            $conn = $this->connectToDatabase();
            $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?"); //Check if email is taken
            $success = $stmt->execute(array($email));

            if($success && $stmt->rowCount() == 0){
                $stmt = $conn->prepare("INSERT INTO user(username, email, password) VALUES (?, ?, ?);"); //Insert user into user table

                $hashedPass = hash('sha256', $pswrd, false);
                $success = $stmt->execute(array($Username, $email, $hashedPass));
    
                if($success){
                    // INSERT INTO tourist(userID, isSouthAfrican) SELECT userID, 0 FROM user ORDER BY userID DESC LIMIT 1
                    
                    $stmt = $conn->prepare("INSERT INTO tourist(userID, isSouthAfrican) SELECT userID, ? FROM user ORDER BY userID DESC LIMIT 1"); //Insert user into tourist table
                    $success = $stmt->execute(array($isSouthAfrican));
                    return $this->constructResponseObject("", "success");
                }
                else{
                    return $this->constructResponseObject("Failed to insert new user", "error");
                }    
            }
            else{
                return $this->constructResponseObject(ERRORTYPES::EMAILTAKEN->value, "error");
            }            
        }
        else{
            return $this->constructResponseObject(ERRORTYPES::USERNAMETAKEN->value, "error");
        }
    }

    /**
    *@brief Deletes a user from the database
    *@param $Username username of the user
    *@return string
    */
    public function deleteUser($username){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('DELETE FROM user WHERE username = ?');
        $success = $stmt->execute(array($username));
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

    /**
    *@brief Updates the old username to the new username in the database
    *@param $CurrUsername current username of the user
    *@param $NewUsername new username to set user to
    *@return string
    */
    public function updateUsername($CurrUsername, $NewUsername){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?"); //Check if username is taken
        $success = $stmt->execute(array($NewUsername));

        if($success && $stmt->rowCount() == 0){
            $stmt = $conn->prepare('UPDATE user SET username = ? WHERE username = ?');
            $success = $stmt->execute(array($NewUsername, $CurrUsername));

            if($stmt->rowCount() > 0){
                return $this->constructResponseObject("", "success");
            }
            else{
                return $this->constructResponseObject("", "error");
            }
        }
        else{
            return $this->constructResponseObject(ERRORTYPES::USERNAMETAKEN->value, "error");
        }
    }

    /**
    *@brief Updates the password to the new password in the database of the passed in username
    *@param $username current username of the user
    *@param $newPswrd new username to set user to
    *@return string
    */
    public function updatePassword($username, $newPswrd){
        if(!preg_match("/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%^&*?><.,;:]{8,}$/", $newPswrd)){
            return $this->constructResponseObject(ERRORTYPES::INVALIDPASSWORD->value, "error");
        }

            $conn = $this->connectToDatabase();
            $stmt = $conn->prepare('SELECT username FROM user WHERE username = ? AND Password = ?');
            
            $hashedPass = hash("sha256", $newPswrd, false);

            $success = $stmt->execute(array($username, $hashedPass));

            if($success && $stmt->rowCount() == 0){
                $stmt = $conn->prepare('UPDATE user SET password = ? WHERE username = ?');
    
                $success = $stmt->execute(array($hashedPass, $username));
    
                if($stmt->rowCount() > 0){
                    return $this->constructResponseObject("", "success");
                }
                else{
                    return $this->constructResponseObject("", "error");
                }
            }
            else{
                return $this->constructResponseObject(ERRORTYPES::SAMEPASSWORD->value, "error");
            }
    }

    /**
    *@brief Gets all of the reviews made by this user
    *@param $username current username of the user
    *@return string
    */
    public function getUserReviews($username){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('SELECT reviewID ,review_description, points  FROM review JOIN user ON userID = reviewer_userID WHERE username = ?');
        $success = $stmt->execute(array($username));

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($rows);
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject($rows, "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

    /**
    *@brief Gets the review count, which is the number of reviews made by this user
    *@param $username current username of the user
    *@return string
    */
    public function getReviewCount($username){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('SELECT COUNT(*)  FROM review JOIN user ON userID = reviewer_userID WHERE username = ?');
        $success = $stmt->execute(array($username));

        if($stmt->rowCount() > 0){
            return $this->constructResponseObject($stmt->fetchColumn(), "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

// Function will need further editing as I am unsure how the wineID info will be procured from client-side

    /**
    *@brief Gets the review count, which is the number of reviews made by this user
    *@param $points the points for this review created by the user
    *@param $review the review text made by the user
    *@param $username the username of the user
    *@param $wineID id of the wine
    *@return string
    */
    public function insertReview($points, $review, $username, $wineID){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('INSERT INTO review(points, review_description, reviewer_userID, wineID) SELECT ?, ?, userID, ? FROM  user WHERE username = ?');
        $success = $stmt->execute(array($points, $review, $wineID, $username));
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

    /**
    *@brief Updates the review on the database
    *@param $review the review text made by the user
    *@param $reviewID id of the review
    *@return string
    */
    public function updateReview($review, $reviewID){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('UPDATE review SET review_description = ? WHERE reviewID = ?');
        $success = $stmt->execute(array($review, $reviewID));
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

    /**
    *@brief Deletes the review on the database
    *@param $reviewID id of the review
    *@return string
    */
    public function deleteReview($reviewID){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('DELETE FROM review WHERE reviewID = ?');
        $success = $stmt->execute(array($reviewID));
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject("", "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

     // * Get Wine Reviews
    /**
    *@brief Get all reviews of this wine
    *@param $wineID id of the wine
    *@return string
    */
     public function getWineReviews($wineID){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare('SELECT reviewID ,review_description, points, username FROM review JOIN user ON userID = reviewer_userID WHERE wineID = ?');
        $success = $stmt->execute(array($wineID));

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($rows);
        
        if($stmt->rowCount() > 0){
            return $this->constructResponseObject($rows, "success");
        }
        else{
            return $this->constructResponseObject("", "error");
        }
    }

    /**
    *@brief Gets varietals from database
    *@param $none
    *@return string
    */
    public function getVarietals(){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT varietal FROM wine GROUP BY varietal");
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $this->constructResponseObject($data, "success");
    }

    /**
    *@brief Gets countries from database
    *@param $none
    *@return string
    */
    public function getCountries(){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT country FROM country");
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $this->constructResponseObject($data, "success");
    }

    /**
    *@brief Gets wines from database based on sorts, location or regions
    *@param $USERREQUEST an object from the frontend
    *@return string
    */
    public function getWines($USERREQUEST){

        $pagesize = 10;
        //SORTS
        $ORDERBY = "";
        
        if(isset($USERREQUEST->sort)){
            $options = array("price_amount", "pointScore", "alcohol_percentage", "vintage", "year_bottled");
            if(!in_array($USERREQUEST->sort, $options)){
                return $this->constructResponseObject(ERRORTYPES::INCORRECTSORT->value, "error");
            }
            else{
                $ORDERBY = " ORDER BY $USERREQUEST->sort ";
                $sort = true;
            }  
        }
        $LIMIT = "LIMIT $pagesize";
        $lastcount = 0;
        if(isset($USERREQUEST->lastcount)){
            $lastcount = $USERREQUEST->lastcount;
            $countgiven = true;
            $LIMIT = "LIMIT " . $lastcount . ", $pagesize";
            
        }
        
        //FILTERS
        $filterchecks = array('varietal'=>0, 'colour'=>0, 'carbonation'=>0, 'sweetness'=>0, 'country'=>0);
        $country = false;
        $regionset = false;
        $WHERE_CLAUSES = array();
        $JOIN = "JOIN winery ON wine.wineryID = winery.wineryID JOIN location ON winery_locationID = location.locationID JOIN region ON region.regionID = location.regionID";
            
        if(isset($USERREQUEST->filters)){
            $filters = $USERREQUEST->filters;
            
            for($i = 0; $i < sizeof($filterchecks) - 1; $i++){ //sizeof - 1 to exlude country
                $current = array_keys($filterchecks)[$i];
                if(isset($filters->$current)){
                    $WHERE_CLAUSES[] = "$current LIKE :$current";
                    $filterchecks[$current] = 1;
                }
            }

            if(isset($filters->country)){
                $WHERE_CLAUSES[] = "region.country = :country";
                $country = true;
            }

            if(isset($filters->region)){
                $WHERE_CLAUSES[] = "region.region_name = :region";
                $regionset = true;
            }
        }
        if(sizeof($WHERE_CLAUSES) != 0){
            $WHERE = "WHERE ";
        }
        else{
            $WHERE = "";
        }
        $WHERE .= implode(" AND ", $WHERE_CLAUSES);

        //Statement
        $FIELDS = "wineID, wine_name, varietal, carbonation, sweetness, colour, vintage, year_bottled, wine_imageURL, pointScore, currency, price_amount, alcohol_percentage, winery_name, location.address AS address, region.region_name AS region, region.country AS country";
        $conn = $this->connectToDatabase();

        $stmt = $conn->prepare("SELECT $FIELDS FROM wine $JOIN $WHERE $ORDERBY $LIMIT");

        if($filterchecks["colour"] == 1){
            $stmt->bindParam(":colour" , $filters->colour); 
        }
        if($filterchecks["carbonation"] == 1){
            $stmt->bindParam(":carbonation" , $filters->carbonation); 
        }
        if($filterchecks["sweetness"] == 1){
            $stmt->bindParam(":sweetness" , $filters->sweetness); 
        }
        
        if($country == true){
            $stmt->bindParam(":country", $filters->country); 
        }
        if($regionset){
            $stmt->bindParam(":region", $filters->region); 
        }

        $stmt->execute();
        $data = $stmt->fetchAll();

        return $this->constructResponseObject($data, "success", $lastcount + 10);
  
    }

    /**
    *@brief Searches for a wine based on the passed in parameter. Finds similarities
    *@param $name name of the wine
    *@param $lastcount = 0 the last served id of wine requests
    *@return string
    */
    public function searchWine($name, $lastcount = 0){

        $pagesize = 10;

        $LIMIT = "LIMIT $pagesize";
        if(isset($lastcount)){
            $LIMIT = "LIMIT " . $lastcount . ", $pagesize";
        }

        if(!isset($name))return $this->constructResponseObject(ERRORTYPES::NONAME->value, "error");

        $FIELDS = "wineID, wine.wineryID, wine_name, varietal, carbonation, sweetness, colour, vintage, year_bottled, wine_imageURL, pointScore, currency, price_amount, alcohol_percentage, winery_name, location.address AS address, region.region_name AS region, region.country AS country";
        $JOIN = "JOIN winery ON wine.wineryID = winery.wineryID JOIN location ON winery_locationID = location.locationID JOIN region ON region.regionID = location.regionID";
        
        $name = strtolower($name);
        $name = "%" . $name . "%";

        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT $FIELDS FROM wine $JOIN WHERE LOWER(wine_name) LIKE :name $LIMIT");
        $stmt->bindParam(':name', $name);

        $stmt->execute();
        $data = $stmt->fetchAll();
        return $this->constructResponseObject($data, "success", $lastcount + $pagesize);
    }

    /**
    *@brief Gets the data of the specific wine id that is passed in
    *@param $id id of wine
    *@return string
    */
    public function openWine($id){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM winery JOIN location ON winery_locationID = location.locationID JOIN region ON location.regionID = region.regionID WHERE region.country LIKE 'South Africa' AND winery.wineryID = ?");
        $stmt->execute(array($id));
        $data = $stmt->fetchAll();

        //get all reviews related to this wine
        $stmt = $conn->prepare('SELECT count(*) AS total FROM review WHERE wineID = ?');
        $stmt->execute(array($id));
        $reviewsCount = $stmt->fetchColumn();

        session_start();
        $_SESSION["WineData"] = $data;
        $_SESSION["ReviewsCount"] = $reviewsCount;
        return $this->constructResponseObject("", "success");
    }

    /**
    *@brief Gets the data of the specific wine id that is passed in
    *@param $req_info an object from the frontend
    *@return string
    */
    public function getWineries($req_info){

        if(isset($req_info->filtercountry)){
            $conn = $this->connectToDatabase();
            $stmt = $conn->prepare("SELECT * FROM winery JOIN location ON winery_locationID = location.locationID JOIN region ON location.regionID = region.regionID WHERE region.country LIKE :country");
            $stmt->bindParam(":country", $req_info->filtercountry);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $this->constructResponseObject($data, "success");
        }
        else{
            $conn = $this->connectToDatabase();
            $stmt = $conn->prepare("SELECT * FROM winery JOIN location ON winery_locationID = location.locationID JOIN region ON location.regionID = region.regionID");
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $this->constructResponseObject($data, "success");
        }
        
    }    

    /**
    *@brief Searches for a winery base don the name and finds similarities
    *@param $name name of winery
    *@return string
    */
    public function searchWinery($name){

        if(!isset($name))return $this->constructResponseObject(ERRORTYPES::NONAME->value, "error");

        $name = strtolower($name);
        $name = "%" . $name . "%";

        $FIELDS = "wineryID, winery_name, winery_imageURL, isVerified, description, winery_websiteURL, longitude, latitude, location.address AS address, region.region_name AS region, region.country AS country";
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT $FIELDS FROM winery JOIN location ON winery_locationID = location.locationID JOIN region ON location.regionID = region.regionID WHERE LOWER(winery_name) LIKE :name");
        $stmt->bindParam(':name', $name);
        
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $this->constructResponseObject($data, "success");
    }

    /**
    *@brief Gets data related to this winery ID
    *@param $id  id of winery
    *@return string
    */
    public function getWinery($id){
        $conn = $this->connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM winery JOIN location ON winery_locationID = location.locationID JOIN region ON location.regionID = region.regionID WHERE winery.wineryID = ?");
        $stmt->execute(array($id));
        $data = $stmt->fetchAll();

        $stmt = $conn->prepare('SELECT count(*) AS total FROM wine WHERE wineryID = ?');
        $stmt->execute(array($id));
        $wineCount = $stmt->fetchColumn();

        $stmt = $conn->prepare('SELECT * FROM wine WHERE wineryID = ? LIMIT 10');
        $stmt->execute(array($id));
        $wines = $stmt->fetchAll();

        session_start();
        $_SESSION["WineryID"] = $id;
        $_SESSION["WineryData"] = $data;
        $_SESSION["WinesCount"] = $wineCount;
        $_SESSION["Wines"] = $wines;
        $_SESSION["Limit"] = 10;

        return $this->constructResponseObject("", "success");
    }

    /**
    *@brief Loads more wines by increasing the limit
    *@param $none
    *@return string
    */
    public function loadMoreWines(){
        session_start();
        $conn = $this->connectToDatabase();
        $val = $_SESSION["Limit"];
        $_SESSION["Limit"] = $val + 10;
        $stmt = $conn->prepare('SELECT * FROM wine WHERE wineryID = ? LIMIT ' . $val + 10);
        $stmt->execute(array($_SESSION["WineryID"]));
        $wines = $stmt->fetchAll();

        $_SESSION["Wines"] = $wines;
        return $this->constructResponseObject($wines, "success");
    }

    /**
    *@brief Gets data related to the currently logged in Manager
    *@param $last_id = 0 last_id of wine loaded on managers page
    *@return string
    */
    public function loadManagersData($last_id = 0){
        session_start();
        $managerkey = $_SESSION["managerkey"];

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT userID FROM winery_manager WHERE userID = ?;");
        $success = $stmt->execute(array($managerkey));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

        if($stmt->rowCount() == 0)return $this->constructResponseObject("No manager exists with your key", "error");

        /////////////
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT winery_name FROM winery JOIN user ON winery.winery_manager = user.userID WHERE user.userID = ? LIMIT 1;");
        $success = $stmt->execute(array($managerkey));
        $result = $stmt->fetchAll();
        //use return data over here
        foreach($result as $valuesToOutput){
            $wineryname = $valuesToOutput['winery_name'];
            break;
        }

        /////////////
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT COUNT(*) AS wine_count FROM wine JOIN winery ON wine.wineryid = winery.wineryid JOIN user ON winery.winery_manager = user.userid WHERE user.userID = ?;");
        $success = $stmt->execute(array($managerkey));
        $wineCount = $stmt->fetchColumn();
        
        ///////////
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT COUNT(*) AS review_count FROM review JOIN wine ON review.wineID = wine.wineID JOIN winery ON wine.wineryID = winery.wineryID JOIN user ON winery.winery_manager = user.userid WHERE user.userID = ?;");
        $success = $stmt->execute(array($managerkey));
        $reviewcount = $stmt->fetchColumn();

        ///////////
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT AVG(points) AS review_count FROM review JOIN wine ON review.wineID = wine.wineID JOIN winery ON wine.wineryID = winery.wineryID JOIN user ON winery.winery_manager = user.userid WHERE user.userID = ?;");
        $success = $stmt->execute(array($managerkey));
        $avgpoints = $stmt->fetchColumn();

        ////
        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT *
        FROM wine
        JOIN winery ON wine.wineryID = winery.wineryID
        JOIN user ON winery.winery_manager = user.userid
        WHERE user.userid = ? AND wine.wineID > ? LIMIT 10");
        $success = $stmt->execute(array($managerkey, $last_id));

        $result = $stmt->fetchAll();
        $arrayValues = [];
        //use return data over here
        foreach($result as $valuesToOutput){
            $WineObject = new stdClass();
            if(isset($valuesToOutput['wineID']))$WineObject->wineID = $valuesToOutput['wineID'];
            if(isset($valuesToOutput['wine_name']))$WineObject->wine_name = $valuesToOutput['wine_name'];
            if(isset($valuesToOutput['varietal']))$WineObject->varietal = $valuesToOutput['varietal'];
            if(isset($valuesToOutput['carbonation']))$WineObject->carbonation = $valuesToOutput['carbonation'];
            if(isset($valuesToOutput['sweetness']))$WineObject->sweetness = $valuesToOutput['sweetness'];
            if(isset($valuesToOutput['colour']))$WineObject->colour = $valuesToOutput['colour'];
            if(isset($valuesToOutput['vintage']))$WineObject->vintage = $valuesToOutput['vintage'];
            array_push($arrayValues, $WineObject);

        }
        $data = array(
            "wineryname" => $wineryname, 
            "wineCount" => $wineCount, 
            "reviewcount"=> $reviewcount, 
            "avgpoints" => $avgpoints,
            "wines" => $arrayValues
        );

        return $this->constructResponseObject($data, "success");

    }

    /**
    *@brief Gets data related to the currently logged in Admin
    *@param $type REQUESTYPE made by the user
    *@param $last_id = 0 last_id of wineries or managers loaded on admin page
    *@return string
    */
    public function getWineriesORManagersAdmin($type, $last_id = 0){
        session_start();
        $adminkey = $_SESSION["adminkey"]; //adminkey should come from session variable

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT adminkey FROM admin WHERE adminkey = ?;");
        $success = $stmt->execute(array($adminkey));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

        if($stmt->rowCount() == 0)return $this->constructResponseObject("No admin exists with your key", "error");

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare(
            $type == REQUESTYPE::GET_WINERY_ADMIN->value ?
            "SELECT wineryID, winery_name, winery_manager FROM winery WHERE wineryID > ? LIMIT 20;" :
            "SELECT wineryID, winery_name, winery_manager FROM winery WHERE winery_manager > ? LIMIT 20;"
        );
        $success = $stmt->execute(array($last_id));

        $result = $stmt->fetchAll();
        $arrayValues = [];
        //use return data over here
        foreach($result as $valuesToOutput){
            $WineryObject = new stdClass();
            if(isset($valuesToOutput['wineryID']))$WineryObject->wineryID = $valuesToOutput['wineryID'];
            if(isset($valuesToOutput['winery_name']))$WineryObject->winery_name = $valuesToOutput['winery_name'];
            if(isset($valuesToOutput['winery_manager']))$WineryObject->winery_manager = $valuesToOutput['winery_manager'];
            array_push($arrayValues, $WineryObject);
        }

        $stmt = $conn->prepare('SELECT count(*) as total from winery');
        $stmt->execute();
        $wineryCount = $stmt->fetchColumn();

        $stmt = $conn->prepare('SELECT count(*) as total from wine');
        $stmt->execute();
        $wineCount = $stmt->fetchColumn();

        $stmt = $conn->prepare('SELECT count(*) as total from winery_manager');
        $stmt->execute();
        $managersCount = $stmt->fetchColumn();

        $stmt = $conn->prepare('SELECT count(*) as total from tourist');
        $stmt->execute();
        $touristCount = $stmt->fetchColumn();

        $data = array(
            "wineryCount" => $wineryCount, 
            "wineCount" => $wineCount, 
            "managersCount"=> $managersCount, 
            "touristCount" => $touristCount,
            "isWineries" => $type == REQUESTYPE::GET_WINERY_ADMIN->value ? true : false,
            "wineries" => $arrayValues
        );

        return $this->constructResponseObject($data, "success");
    }

    /**
    *@brief allows an admin to access a manager or a specific wineries page
    *@param $managerID id of manager
    *@return string
    */
    public function openManagersPage($managerID){
        session_start();
        $_SESSION['managerkey'] = $managerID;
        return $this->constructResponseObject("", "success");
    }

    /**
    *@brief allows an admin to add a winery
    *@param $wineryName name of winery
    *@param $wineryImageURL image url of winery
    *@param $wineryWebsiteURL website url of winery
    *@param $location location of winery
    *@param $country country of winery
    *@param $longitude longitude of winery
    *@param $latitude latitude of winery
    *@param $region region of winery
    *@param $wineryManagerID manager id of winery
    *@param $isverified verification status of winery
    *@param $description description of winery
    *@return string
    */
    public function addWineryAdmin($wineryName, $wineryImageURL, $wineryWebsiteURL, 
        $location, $country, $longitude, $latitude, $region, $wineryManagerID, $isverified, $description){
        session_start();
        $adminkey = $_SESSION["adminkey"]; //adminkey should come from session variable

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT adminkey FROM admin WHERE adminkey = ?;");
        $success = $stmt->execute(array($adminkey));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

        if($stmt->rowCount() == 0)return $this->constructResponseObject("No admin exists with your key", "error");

        /////////////////////////////COUNTRY
        $stmt = $conn->prepare("SELECT country FROM country WHERE country LIKE ?;");
        $success = $stmt->execute(array($country));
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            foreach($result as $valuesToOutput){
                $country = $valuesToOutput['country'];
                break;
            }
        }
        else{
            $stmt = $conn->prepare("INSERT INTO country(country) VALUES(?);");
            $success = $stmt->execute(array($country));
            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

            $stmt = $conn->prepare("SELECT country FROM country WHERE country LIKE ?;");
            $success = $stmt->execute(array($country));
            if($stmt->rowCount() > 0){
                $result = $stmt->fetchAll();
                foreach($result as $valuesToOutput){
                    $countryName = $valuesToOutput['country'];
                    break;
                }
            }
            else return $this->constructResponseObject("Database connection has failed, try again", "error");
        }

        /////////////////////////REGION
        $stmt = $conn->prepare("SELECT regionID FROM region WHERE region_name LIKE ?;");
        $success = $stmt->execute(array($region));
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            foreach($result as $valuesToOutput){
                $regionid = $valuesToOutput['regionID'];
                break;
            }
        }
        else{
            $stmt = $conn->prepare("INSERT INTO region(region_name, country) VALUES(?, ?);");
            $success = $stmt->execute(array($region, $countryName));
            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

            $stmt = $conn->prepare("SELECT regionID FROM region WHERE region_name LIKE ?;");
            $success = $stmt->execute(array($region));
            if($stmt->rowCount() > 0){
                $result = $stmt->fetchAll();
                foreach($result as $valuesToOutput){
                    $regionid = $valuesToOutput['regionID'];
                    break;
                }
            }
            else return $this->constructResponseObject("Database connection has failed, try again", "error");
        }

        /////////////////////////LOCATION
        $stmt = $conn->prepare("SELECT locationID FROM location WHERE address LIKE ?;");
        $success = $stmt->execute(array($location));
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            foreach($result as $valuesToOutput){
                $locationID = $valuesToOutput['locationID'];
                break;
            }
        }
        else{
            $stmt = $conn->prepare("INSERT INTO location(longitude, lattitude, address, regionID) VALUES(?, ?, ?, ?);");
            $success = $stmt->execute(array($longitude, $latitude, $location, $regionid));
            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

            $stmt = $conn->prepare("SELECT locationID FROM location WHERE address LIKE ?;");
            $success = $stmt->execute(array($location));
            if($stmt->rowCount() > 0){
                $result = $stmt->fetchAll();
                foreach($result as $valuesToOutput){
                    $locationID = $valuesToOutput['locationID'];
                    break;
                }
            }
            else return $this->constructResponseObject("Database connection has failed, try again", "error");
        }
        
        //winery

        if($wineryManagerID != null){
            $stmt = $conn->prepare("INSERT INTO winery(winery_name, winery_imageURL, description, winery_websiteURL, winery_locationID, winery_manager, isVerified) VALUES(?,?,?,?,?,?,?);");
            $success = $stmt->execute(array(
                $wineryName, $wineryImageURL, $description, 
                $wineryWebsiteURL, $locationID, $wineryManagerID, $isverified == true ? 1 : 0));
        }
        else{
            $stmt = $conn->prepare("INSERT INTO winery(winery_name, winery_imageURL, description, winery_websiteURL, winery_locationID, isVerified) VALUES(?,?,?,?,?,?);");
            $success = $stmt->execute(array(
                $wineryName, $wineryImageURL, $description, 
                $wineryWebsiteURL, $locationID, 0));
        }
        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
        else return $this->constructResponseObject("Added new winery", "success");
    }

    /**
    *@brief deletes a winery from the backend
    *@param $id id of winery
    *@return string
    */
    public function deleteWineryAdmin($id){
        session_start();
        $adminkey = $_SESSION["adminkey"]; //adminkey should come from session variable

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT adminkey FROM admin WHERE adminkey = ?;");
        $success = $stmt->execute(array($adminkey));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

        if($stmt->rowCount() == 0)return $this->constructResponseObject("No admin exists with your key", "error");

        $stmt = $conn->prepare("DELETE FROM winery WHERE wineryID = ?;");
        $success = $stmt->execute(array($id));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
        return $this->constructResponseObject("deleted winery", "success");
    }

    /**
    *@brief allows a manager to add a wine
    *@param $wine_name wine_name of winery
    *@param $varietal varietal of winery
    *@param $carbonation carbonation of winery
    *@param $sweetness sweetness of winery
    *@param $colour colour of winery
    *@param $vintage vintage of winery
    *@param $year_bottled year_bottled of winery
    *@param $wine_imageURL wine_imageURL of winery
    *@param $pointScore pointScore of winery
    *@param $currency currency of wine
    *@param $price_amount price_amount of wine
    *@param $alcohol_percentage alcohol_percentage of wine
    *@return string
    */
    public function addWine($wine_name, $varietal, $carbonation, $sweetness, $colour, 
        $vintage, $year_bottled, $wine_imageURL, $pointScore, $currency, $price_amount, $alcohol_percentage){
            session_start();
            $managerkey = $_SESSION["managerkey"];

            $conn = $this->connectToDataBase();
            $stmt = $conn->prepare("SELECT userID FROM winery_manager WHERE userID = ?;");
            $success = $stmt->execute(array($managerkey));

            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

            if($stmt->rowCount() == 0)return $this->constructResponseObject("No manager exists with your key", "error");

            ///

            $stmt = $conn->prepare("INSERT INTO wine (wineryID,wine_name,varietal,carbonation,sweetness,colour,vintage,year_bottled,wine_imageURL,pointScore,currency,price_amount,alcohol_percentage) VALUES
             (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
            $success = $stmt->execute(array($managerkey, $wine_name, $varietal, $carbonation, $sweetness, $colour, 
            $vintage, $year_bottled, $wine_imageURL, $pointScore, $currency, $price_amount, $alcohol_percentage));

            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            else return $this->constructResponseObject("", "success");
 
    }

    /**
    *@brief allows a manager to edit a wine
    *@param $wine_name wine_name of winery
    *@param $varietal varietal of winery
    *@param $carbonation carbonation of winery
    *@param $sweetness sweetness of winery
    *@param $colour colour of winery
    *@param $vintage vintage of winery
    *@param $year_bottled year_bottled of winery
    *@param $wine_imageURL wine_imageURL of winery
    *@param $pointScore pointScore of winery
    *@param $currency currency of wine
    *@param $price_amount price_amount of wine
    *@param $alcohol_percentage alcohol_percentage of wine
    *@return string
    */
    public function editWine($wineID, $wine_name, $varietal, $carbonation, $sweetness, $colour, 
        $vintage, $year_bottled, $wine_imageURL, $pointScore, $currency, $price_amount, $alcohol_percentage){
            session_start();
            $managerkey = $_SESSION["managerkey"];

            $conn = $this->connectToDataBase();
            $stmt = $conn->prepare("SELECT userID FROM winery_manager WHERE userID = ?;");
            $success = $stmt->execute(array($managerkey));

            if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

            if($stmt->rowCount() == 0)return $this->constructResponseObject("No manager exists with your key", "error");

            
            if($wine_name != null){
                $stmt = $conn->prepare("UPDATE wine SET wine_name = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($wine_name, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($varietal != null){
                $stmt = $conn->prepare("UPDATE wine SET varietal = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($varietal, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($carbonation != null){
                $stmt = $conn->prepare("UPDATE wine SET carbonation = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($carbonation, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($sweetness != null){
                $stmt = $conn->prepare("UPDATE wine SET sweetness = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($sweetness, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($colour != null){
                $stmt = $conn->prepare("UPDATE wine SET colour = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($colour, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($vintage != null){
                $stmt = $conn->prepare("UPDATE wine SET vintage = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($vintage, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($year_bottled != null){
                $stmt = $conn->prepare("UPDATE wine SET year_bottled = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($year_bottled, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($wine_imageURL != null){
                $stmt = $conn->prepare("UPDATE wine SET wine_imageURL = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($wine_imageURL, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($pointScore != null){
                $stmt = $conn->prepare("UPDATE wine SET pointScore = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($pointScore, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($currency != null){
                $stmt = $conn->prepare("UPDATE wine SET currency = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($currency, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($price_amount != null){
                $stmt = $conn->prepare("UPDATE wine SET price_amount = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($price_amount, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            if($alcohol_percentage != null){
                $stmt = $conn->prepare("UPDATE wine SET alcohol_percentage = ? WHERE wineID = ?;");
                $success = $stmt->execute(array($alcohol_percentage, $wineID));
                if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
            }
            
            return $this->constructResponseObject("", "success");
    }

    /**
    *@brief deletes a wine from the backend
    *@param $id id of wine
    *@return string
    */
    public function deleteWine($id){
        session_start();
        $managerkey = $_SESSION["managerkey"];

        $conn = $this->connectToDataBase();
        $stmt = $conn->prepare("SELECT userID FROM winery_manager WHERE userID = ?;");
        $success = $stmt->execute(array($managerkey));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");

        if($stmt->rowCount() == 0)return $this->constructResponseObject("No manager exists with your key", "error");

        $stmt = $conn->prepare("DELETE FROM wine WHERE wineID = ?;");
        $success = $stmt->execute(array($id));

        if(!$success)return $this->constructResponseObject("Database connection has failed, try again", "error");
        return $this->constructResponseObject("deleted winery", "success");
    }
    
    /**
    *@brief Creates an error based on the passed in parameter error type
    *@param $desc description can be a message or an array object or anything as long as it's JSON encodable. Default value is "Error. Post parameters are missing"
    *@param $status is the status of the response, error or success. Default value is "error"
    *@return string
    */
    private function constructResponseObject($desc = "Error. Post parameters are missing", $status = "error", $lastcount = false){
        $value = array("status"=> $status,"data" => $desc);
        if($lastcount != false){
            $value["lastcount"] = $lastcount;
        }
        $value = json_encode($value);
        return $value == false ? "" : $value;
    }
}

/**
*@brief creates an instance of the api
*/
$apiconfig = api::instance();

/**
*@brief hanldes all POST requests
*/
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $json = file_get_contents('php://input');
    $USERREQUEST = json_decode($json);

    if($USERREQUEST->type == REQUESTYPE::REGISTER->value){
        echo $apiconfig->registerUser($USERREQUEST->username, $USERREQUEST->email, $USERREQUEST->password, $USERREQUEST->isSouthAfrican);
    }
    else if($USERREQUEST->type === REQUESTYPE::INSERT_REVIEW->value) {
        echo $apiconfig->insertReview($USERREQUEST->points, $USERREQUEST->review, $USERREQUEST->username, $USERREQUEST->wineID);
    }
    else if($USERREQUEST->type == REQUESTYPE::LOGIN->value){
        echo $apiconfig->loginUser($USERREQUEST->email, $USERREQUEST->password);
    }
    else if($USERREQUEST->type == REQUESTYPE::LOGIN_ADMIN->value){
        echo $apiconfig->loginAdmin($USERREQUEST->key);
    }
    else if($USERREQUEST->type == REQUESTYPE::LOGIN_MANAGER->value){
        echo $apiconfig->loginManager($USERREQUEST->username, $USERREQUEST->password);
    }
    else if($USERREQUEST->type == REQUESTYPE::LOGOUT->value){
        $apiconfig->logout();
    }
    else if($USERREQUEST->type == REQUESTYPE::DELETE_ACCOUNT->value){
        echo $apiconfig->deleteUser($USERREQUEST->username);
    }
    else if($USERREQUEST->type == REQUESTYPE::UPDATE_USERNAME->value){
        echo $apiconfig->updateUsername($USERREQUEST->CurrUsername, $USERREQUEST->NewUsername);
    }
    else if($USERREQUEST->type == REQUESTYPE::UPDATE_PASSWORD->value){
        echo $apiconfig->updatePassword($USERREQUEST->username, $USERREQUEST->newPswrd);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_USER_REVIEWS->value){
        echo $apiconfig->getUserReviews($USERREQUEST->username);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_REVIEW_COUNT->value){
        echo $apiconfig->getReviewCount($USERREQUEST->username);
    }
    else if($USERREQUEST->type == REQUESTYPE::UPDATE_REVIEW->value){
        echo $apiconfig->updateReview($USERREQUEST->review, $USERREQUEST->reviewID);
    }
    else if($USERREQUEST->type == REQUESTYPE::DELETE_REVIEW->value){
        echo $apiconfig->deleteReview($USERREQUEST->reviewID);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_WINERIES->value){
        echo $apiconfig->getWineries($USERREQUEST);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_WINE->value){
        echo $apiconfig->getWines($USERREQUEST);
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_VARIETAL->value){
        echo $apiconfig->getVarietals();
    }
    else if($USERREQUEST->type == REQUESTYPE::GET_COUNTRY->value){
        echo $apiconfig->getCountries();
    }
    else if($USERREQUEST->type == REQUESTYPE::SEARCH_WINERY->value){
        echo $apiconfig->searchWinery($USERREQUEST->name);
    }
    else if($USERREQUEST->type == REQUESTYPE::SEARCH_WINE->value){
        if(isset($USERREQUEST->lastcount)){
            echo $apiconfig->searchWine($USERREQUEST->name, $USERREQUEST->lastcount);
        }
        else{
            echo $apiconfig->searchWine($USERREQUEST->name);
        }
    }
    else if($USERREQUEST->type == REQUESTYPE::OPEN_WINERY_ADMIN->value){
        echo $apiconfig->openManagersPage($USERREQUEST->managerID);
    }
    else if($USERREQUEST->type == REQUESTYPE::ADD_WINERY_ADMIN->value){
        echo $apiconfig->addWineryAdmin($USERREQUEST->wineryName, $USERREQUEST->wineryImageURL,
            $USERREQUEST->wineryWebsiteURL, $USERREQUEST->location, $USERREQUEST->country,
            $USERREQUEST->longitude, $USERREQUEST->latitude, $USERREQUEST->region,
            $USERREQUEST->wineryManagerID, $USERREQUEST->isverified, $USERREQUEST->description
        );
    }
    else if($USERREQUEST->type == REQUESTYPE::ADD_WINE->value){
        echo $apiconfig->addWine($USERREQUEST->wine_name, $USERREQUEST->varietal, $USERREQUEST->carbonation,
            $USERREQUEST->sweetness, $USERREQUEST->colour, $USERREQUEST->vintage, $USERREQUEST->year_bottled, 
            $USERREQUEST->wine_imageURL, $USERREQUEST->pointScore, $USERREQUEST->currency, $USERREQUEST->price_amount,
            $USERREQUEST->alcohol_percentage
        );
    }
    else if($USERREQUEST->type == REQUESTYPE::EDIT_WINE->value){
        echo $apiconfig->editWine($USERREQUEST->wineID,$USERREQUEST->wine_name, $USERREQUEST->varietal, $USERREQUEST->carbonation,
            $USERREQUEST->sweetness, $USERREQUEST->colour, $USERREQUEST->vintage, $USERREQUEST->year_bottled, 
            $USERREQUEST->wine_imageURL, $USERREQUEST->pointScore, $USERREQUEST->currency, $USERREQUEST->price_amount,
            $USERREQUEST->alcohol_percentage
        );
    }
    else echo $json;
}
/**
*@brief hanldes all GET requests
*/
else if($_SERVER["REQUEST_METHOD"] == "GET"){
    if($_GET['type'] == REQUESTYPE::GET_WINERY_ADMIN->value){
        echo $apiconfig->getWineriesORManagersAdmin(REQUESTYPE::GET_WINERY_ADMIN->value, isset($_GET['last_id']) ? $_GET['last_id'] : 0);
    }
    else if($_GET['type'] == REQUESTYPE::GET_MANAGERS_ADMIN->value){
        echo $apiconfig->getWineriesORManagersAdmin(REQUESTYPE::GET_MANAGERS_ADMIN->value, isset($_GET['last_id']) ? $_GET['last_id'] : 0);
    }
    else if($_GET['type'] == REQUESTYPE::DELETE_WINERY_ADMIN->value){
        echo $apiconfig->deleteWineryAdmin($_GET['wineryID']);
    }
    else if($_GET['type'] == REQUESTYPE::GET_WINERIES->value){
        echo $apiconfig->getWineries(array());
    }
    else if($_GET['type'] == REQUESTYPE::SEARCH_WINERY->value){
        echo $apiconfig->searchWinery($_GET['name']);
    }
    else if($_GET['type'] == REQUESTYPE::OPEN_WINERY->value){
        echo $apiconfig->getWinery($_GET['id']);
    }
    else if($_GET['type'] == REQUESTYPE::GET_WINE->value){
        echo $apiconfig->getWines(isset($_GET['lastcount']) ? array("lastcount" => $_GET['lastcount']) : array());
    }
    else if($_GET['type'] == REQUESTYPE::SEARCH_WINE->value){
        echo $apiconfig->searchWine($_GET['name'], isset($_GET['lastcount']) ? $_GET['lastcount'] : 0);
    }
    else if($_GET['type'] == REQUESTYPE::OPEN_WINE->value){
        echo $apiconfig->openWine($_GET['id']);
    }
    else if($_GET['type'] == REQUESTYPE::LOAD_MORE_WINES->value){
        echo $apiconfig->loadMoreWines();
    }
    else if($_GET['type'] == REQUESTYPE::GET_WINE_REVIEWS->value){
        echo $apiconfig->getWineReviews($_GET['wineID']);
    }
    else if($_GET['type'] == REQUESTYPE::LOAD_MANAGER_DATA->value){
        echo $apiconfig->loadManagersData($_GET['last_id']);
    }
    else if($_GET['type'] == REQUESTYPE::DELETE_WINE->value){
        echo $apiconfig->deleteWine($_GET['wineID']);
    }
}
