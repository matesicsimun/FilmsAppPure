<?php
declare(strict_types = 1);

/**
 * Ispisuje siguran string od HTML koda
 * @param string $v
 * @return string
 */
function __($v){
    return htmlentities($v, ENT_QUOTES, "utf-8");
}

/**
 * Iz URL-a dohvaca parametar imena $v
 * Ukoliko parametara nema vraca null
 * @param string $v
 * @param type $d
 */
function get(string $v, $d = null){
    return $_GET[$v] ?? $d;
}

/**
 * @param string $email
 * @param string $password
 * @return array|null
 */
function checkUserPwdAndGetData(string $email, string $password){
    $lines = getLines("users.txt");

    foreach($lines as $line){
        $lineParts = explode(";", $line);
        $currEmail = $lineParts[3];
        $currPwd = $lineParts[4];

        if ($email === $currEmail){
            if (password_verify($password, trim($currPwd))){
                return $lineParts;
            }
        }

    }
    return null;
}

/**
 * Iz tijela HTTP zahtjeva dohvaca parametar imena $v
 * Ukoliko parametra nema vraca null.
 * @param string $v
 * @param type $d
 */
function post($v, $d = null){
    if (isset($_POST["$v"])){
        return settype($_GET["$v"], $d);
    }
}

/**
 * Provjera je li zahtjev POST.
 * Ako je zahtjev POST, provjerava se postoji
 * li parametar naziva $key.
 * Ako parametar ne postoji
 * @param null $key
 */
function isPost($key = null){
    if (null === $key){
        return count($_POST) > 0;
    }

    return null !== post($key);
}

/**
 * Provjera je li parametar null ili prazan.
 * @param $param
 * @return bool
 */
function paramExists($param){
    if (null !== $param && !empty($param)) return true;
    return false;
}

function redirect($url){
    header("Location: " . $url);
    die();
}

/**
 * Checks if the user is logged in.
 * @return bool
 */
function isLoggedIn(){
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true;
}

/**
 * Gets the user id from the session cookie.
 * @param string $v
 * @return string
 */
function userID(string $v):string{
    if (isLoggedIn()){
        if (isset($_SESSION['userId'])){
            return $_SESSION['userId'];
        }
    }
}

/**
 * Appends the user data to the file that acts as a database
 * @param int $id
 * @param string $name
 * @param string $surname
 * @param string $email
 * @param string $password
 * @param string $filename
 * @return int
 */
function saveUser(int $id, string $name, string $surname, string $email, string $password, string $filename): int{
    $hash = password_hash(trim($password), PASSWORD_DEFAULT);

    //check if user with email exists
    if (userExists($email, $filename)){
        return -1;
    }

    $line = $id . ";" . $name . ";" . $surname . ";" . $email . ";" . $hash;
    writeToFile($line, $filename);

    return 0;
}

/**
 * Checks if the user has been noted in the file that acts as a database
 * @param string $email
 * @param string $fileName
 * @return bool
 */
function userExists(string $email, string $fileName): bool{
    $lines = getLines($fileName);

    foreach ($lines as $line){
        $emailFromFile = explode(";", $line)[3];

        if($email === $emailFromFile){
            return true;
        }
    }

    return false;

}

/**
 * Appends a line to the file.
 * @param $line
 * @param $filename
 */
function writeToFile($line, $filename){
    $fileHandle = fopen($filename, "a");
    fwrite($fileHandle, $line);
    fwrite($fileHandle, "\n");
    fclose($fileHandle);
}

/**
 * Updates the file that acts as database of homeworks with a new entry
 * @param $userId
 * @param $hwFileName
 * @param $fileName
 */
function updateHomeworks($userId, $hwFileName, $fileName){
    $newId = getNewId("homeworks.txt");
    $lineToSave = $newId.";".$userId.";".$hwFileName;
    writeToFile($lineToSave, $fileName);
}


/**
 * @param string $fileName
 * @param string $userId
 * @return array|null
 */
function getHomeworkData(string $fileName, string $userId ){
    $lines = getLines($fileName);
    foreach ($lines as $line){
        $line = trim($line);

        $lineParts = explode(";",$line);
        if ($lineParts[1] == $userId){
            return $lineParts;
        }
    }

    return null;
}


function getNewId(string $fileName): int{

    if (filesize($fileName)){
        $fileHandle = fopen($fileName, "r");
        $cursor = -1;

        fseek($fileHandle, $cursor, SEEK_END);
        $char = fgetc($fileHandle);

        while ($char === "\n" || $char === "\r") {
            fseek($fileHandle, $cursor--, SEEK_END);
            $char = fgetc($fileHandle);
        }

        $line = '';

        while ($char !== false && $char !== "\n" && $char !== "\r") {
            $line = $char . $line;
            fseek($fileHandle, $cursor--, SEEK_END);
            $char = fgetc($fileHandle);
        }

        $id = explode(";", $line)[0];
        fclose($fileHandle);

        return intval($id)+1;

    }

    //if the file was empty, this is the first user to be added
    return 0;
}

/**
 * Returns all lines of text from the file described by $fileName
 * @param {string} $fileName
 * @return {array} $lines
 */
function getLines($fileName){
    $handle = fopen($fileName, "r");
    $lines = [];
    if ($handle){
        while (($line = fgets($handle)) !== false){
            $lines[] = $line;
        }
    }
    fclose($handle);

    return $lines;
}

function getQuestions($fileName){
    $questions = array();
    $ID = 0;
    foreach (getLines($fileName) as $line){
        $questions[] = getQuestion($line, $ID);
        $ID++;
    }

    return $questions;
}

function getQuestion($line, $ID){
    //split the line into the question part and the answers part
    $lineParts = explode(":", $line);
    $question_text = $lineParts[0];
    $answer_part = $lineParts[1];

    //split the answers part into correct answers part and possible answer part
    $answer_parts = explode("=", $answer_part);
    $correct_answers_part = $answer_parts[1];
    $offered_answers_part = $answer_parts[0];

    //check if offered answers are empty - if so, the question is of type "NADOPUNA"
    if (empty($offered_answers_part)){
        $correct_answers_part = trim($correct_answers_part);
        $question = ["ID" => $ID,  "type"=>"NADOPUNA", "text"=>$question_text, "offered"=>[], "true"=>[$correct_answers_part]];

    }else{
        $offered_answers_arr = explode(",", $offered_answers_part);

        //check how many true answers
        $correct_answers_arr = explode(",", $correct_answers_part);

        //trim the answers in the arrays
        $correct_answers_arr = array_map('trim', $correct_answers_arr);
        $offered_answers_arr = array_map('trim', $offered_answers_arr);

        if (count($correct_answers_arr) > 1){

            //type is MULTIPLE CORRECT
            $question = ["ID" => $ID, "type"=>"MULTI", "text"=>$question_text, "offered"=>$offered_answers_arr, "true"=>$correct_answers_arr];
        }
        else{
            $question = ["ID" => $ID, "type"=>"MULTI_ONE", "text"=>$question_text, "offered"=>$offered_answers_arr, "true"=>$correct_answers_arr];
        }
    }


    return $question;
}

function getQuestionByType($questions, $type){
    $qs = array();
    foreach ($questions as $question){
        if ($question["type"] === $type){
            $qs[] = $question;
        }
    }

    return $qs;
}

function show_image($file_type, $image){
    $format = "Content-Type: image/" . $file_type;
    header($format);

    switch($file_type){
        case "jpeg":
            imagejpeg($image);
            break;
        case "png":
            imagepng($image);
            break;
        case "gif":
            imagegif($image);
            break;
        default:
            imagepng($image);
    }

    imagedestroy($image);
}