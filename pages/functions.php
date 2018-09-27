	<?php
		  $dbname = "root";
		  $dbpass =  "";
		  $dbname="unixender";

	function is_logged_in(){
		return isset($_SESSION['username']);
	}
	function statusColor($msg_status){
		if ($msg_status=="unread") {
			$stat="status: unread";
			return $stat;
		}else{
			$stat="status: read";
			return $stat;
		}
	}
	function paginate(){

	}
	function getInboxSum($receiver){
//SELECT COUNT(*) FROM messages WHERE msg_status= 'unread' AND category='messages' AND msg_receiver='std_okey'
		//SELECT * FROM `messages` WHERE msg_status='unread' AND msg_receiver='mgt_chigo'
              try {
                $conn=new PDO('mysql:host=localhost;dbname=unixender', 'root','');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql= "SELECT * FROM messages WHERE msg_status='unread' AND msg_receiver= :msg_receiver AND msg_type='Message'";
                $stmt=$conn->prepare($sql);
                $stmt->bindValue(":msg_receiver", $receiver);
                $stmt->execute();
                $rowCount = $stmt->fetch(PDO::FETCH_NUM);
                $row= $rowCount[0];
                if ($row<1) {
                  return print_r('');
                }elseif ($row==1) {
                	return print_r($row.' new message');
                }elseif ($row>1) {
                	return print_r($row.' new messages');
                }else{}
               // }
              } catch (PDOException $e) {
                die("Query Failed: ".$e->getMessage());
              }
	}

	function searchUserID($username){
		
		/**
		Search for user ids and return the id, username and category
		returns $row, an array of the needed values for searching for ids
		*/
		try {
			$conn=new PDO('mysql:host=localhost;dbname=unixender', 'root', "");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql= "SELECT id, username, category  FROM detail WHERE username LIKE :username";
			$stmt= $conn->prepare($sql);
			$stmt->bindparam(":username", $username, PDO::PARAM_STR);
			$stmt->execute();		
			$count=0;		
			foreach ($stmt->fetchAll() as $row) {
			$count++;      
      		echo '<br><br><br><table class="table table-sm table-hover">
					  <thead class="tb-head">
					    <tr>
					      <th scope="col">s/n</th>
					      <th scope="col">Username</th>
					      <th scope="col">Category</th>
					      <th scope="col">id</th>
					    </tr>
					  </thead>
					  <tbody id="tb-text">
					    <tr>'
					    		.'<td>'.$count.'</td>
					    		<td>'.$row["username"].'</td>
					    		<td>'.$row["category"].'</td>
					    		<td>'.$row["id"].'</td> 
					    </tr>
					  </tbody>
					</table>';
				}
							
			//	echo "<br> username: ". $row["username"]. " category: ". $row["category"]." Message id: ". $row["id"];
			//}
		} catch (PDOException $e) {
			die("Query Failed: ".$e->getMessage());
		}
	}
	class dbcon{
		public $dbname = "root";
		public $dbpass =  "";
		function getUserCategortyByUsername($username){
			$conn=new mysqli('localhost','root','','unixender');
			$result = $conn->query("SELECT category FROM detail WHERE username = '$username'");
			$row = $result->fetch_assoc();
			return $row['category'];
		}
		function getUserIdByUsername($username){
			$conn=new mysqli('localhost','root','','unixender');
			$result = $conn->query("SELECT id FROM detail WHERE username = '$username'");
			$row = $result->fetch_assoc();
			return $row['id'];
		}
		function sendMesgDB($username, $msg_uid, $recipId, $key,$mssg_title,$msg_body,$type){
			try {
		//Encrypt Message
				$enc_msg_body=Custom_Crypto::encrypt($msg_body, $key);
				$enc_mssg_title=Custom_Crypto::encrypt($mssg_title, $key);
				$category = $this->getUserCategortyByUsername($username);

				$conn=new mysqli('localhost','root','','unixender');

				$sql="INSERT INTO messages (msg_sender, msg_uid,msg_receiver, msg_key_dec_key, msg_title,msg_body,date_sent, category,msg_type) VALUES('$username', '$msg_uid', '$recipId', '$key', '$enc_mssg_title', '$enc_msg_body',NOW(), '$category','$type')";
      /*$stmt=$conn->prepare($sql);
      $stmt->bindParam(":msg_sender", $_SESSION['username'], PDO::PARAM_STR );
      $stmt->bindParam(":$msg_uid", $msg_uid, PDO::PARAM_STR );
      $stmt->bindParam(":msg_receiver", $recipId, PDO::PARAM_STR);
      $stmt->bindParam(":msg_key_dec_key", $key, PDO::PARAM_STR);
      $stmt->bindParam(":msg_title", $enc_mssg_title, PDO::PARAM_STR);
      $stmt->bindParam(":msg_body", $enc_msg_body, PDO::PARAM_STR);
      $stmt->bindValue(":date_sent", date('Y-m-d H:i:s'));
      $stmt->bindParam(":category", $category, PDO::PARAM_STR);*/
      if ($conn->query($sql)) {
      	return "Success";
      }else{
      	return "Failure";
      }
    //  alert("successfully uploaded");

  } catch (Exception $e) {
  	die($e);
  }
}

function val_user_creds($username, $password){
		//validate that against the records


	try {
		$conn=new PDO('mysql:host=localhost;dbname=unixender', $dbname,$dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt=$conn->prepare('SELECT * FROM detail WHERE username=:username AND password=:password');
		$stmt->bindparam('username',$username,PDO::PARAM_STR);
		$stmt->bindparam('password',$password, PDO::PARAM_STR);
		$stmt->execute();
		while ($row=$stmt->fetch()) {
			$category=$row['category'];
		}
	} catch (PDOException $e) {
		$e->getMessage();
	}
}
function getUserId($username){
	try {
		$conn=new PDO('mysql:host=localhost;dbname=unixender', 'root','');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt=$conn->prepare('SELECT id FROM detail WHERE username=:username');
		$stmt->bindparam('username',$username,PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		return $row[0];
	} catch (Exception $e) {
		$e->getMessage();
	}
}
function getMsgKey($id){
	try {
		$conn=new PDO('mysql:host=localhost;dbname=unixender', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt=$conn->prepare('SELECT msg_dspkey FROM msg_keys WHERE id=:id');
		$stmt->bindparam('id', $id, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		return  $row[0];
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

function dbconnect(){
	$dbname   = "root";
	$dbpass =  "";
	return $conn= new PDO('mysql:host=localhost;dbname=unixender', $username,$password);
}

/*function validateuser($username,$password){
	try {
		$dbname   = "root";
		$dbpass =  "";
		$conn=new PDO('mysql:host=localhost;dbname=unixender', $dbname,$dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt=$conn->prepare('SELECT * FROM detail WHERE username=:username AND password=:password');
		$stmt->bindparam('username',$username,PDO::PARAM_STR);
		$stmt->bindparam('password',$password, PDO::PARAM_STR);
		$stmt->execute();
		$row=$stmt->fetch();
		if (count($row)>0) {
			header("Location: pages/stf_portal.php");
		}else{
			$category = 'failure';
		}
	} catch (PDOException $e) {
		$e->getMessage();
	}
	switch ($category) {
		case 'mgt':
		return 'mgt';
		break;
		case 'staff':
		return 'staff';
		break;
		case 'student':
		return 'student';
		break;
		case 'failure';
		return 'index';
		default:
		return 'index';
		break;
	}
}*/
}






//======================================Encryption Module================================================
//$enc=Custom_Crypto::encrypt('Kelechi', '4ab@');
//echo $enc.'<br>';
//$dec= Custom_Crypto::decrypt($enc,'4ab@');
//echo $dec;
class Custom_Crypto
{
	const METHOD = 'aes-256-ctr';

    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
    	$nonceSize = openssl_cipher_iv_length(self::METHOD);
    	$nonce = openssl_random_pseudo_bytes($nonceSize);

    	$ciphertext = openssl_encrypt(
    		$message,
    		self::METHOD,
    		$key,
    		OPENSSL_RAW_DATA,
    		$nonce
    	);

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
    	if ($encode) {
    		return base64_encode($nonce.$ciphertext);
    	}
    	return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
    	if ($encoded) {
    		$message = base64_decode($message, true);
    		if ($message === false) {
    			throw new Exception('Encryption failure');
    		}
    	}

    	$nonceSize = openssl_cipher_iv_length(self::METHOD);
    	$nonce = mb_substr($message, 0, $nonceSize, '8bit');
    	$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

    	$plaintext = openssl_decrypt(
    		$ciphertext,
    		self::METHOD,
    		$key,
    		OPENSSL_RAW_DATA,
    		$nonce
    	);

    	return $plaintext;
    }
}

