
<?php
 //include('../pages/functions.php');
 //include('../pages/mgt_portal.php');

$username=$_POST['searchValue'];


  try {
			$conn=new PDO('mysql:host=localhost;dbname=unixender', 'root', "");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql= "SELECT id, username, category  FROM detail WHERE username LIKE :username";
			$stmt= $conn->prepare($sql);
			$stmt->bindparam(":username", $username, PDO::PARAM_STR);
			$stmt->execute();		
			if($rows=$stmt->fetchAll()){
				$count=0;		
			foreach ($rows as $row) {
			$count++;      
      		echo 'Search result<br><table class="table table-sm table-hover">
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
			}else{
				echo('<div style="font-style: italic; padding-left:20px; color: red">No Match found</div>');
			}
			
			//	echo "<br> username: ". $row["username"]. " category: ". $row["category"]." Message id: ". $row["id"];
			//}
		} catch (PDOException $e) {
			
		//	die("Error searching for user id");
			die("Query Failed: ".$e->getMessage());
		}