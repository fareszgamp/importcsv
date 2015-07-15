<?php
class Csvimport 
{
    protected $connection = null;

    public function connect(){
		
        $this->connection = new PDO("mysql:host=localhost;dbname=progfeladat", "root", "123456",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }
	public function __construct(){
		
		$this->connect();
	}
	
	
    public function getProductid($productid){
		
        $query = $this->connection->prepare("SELECT * FROM product where externalid=:pid");
		$data = [":pid"=>$productid];
        $query->execute($data);
		$result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
	
	public function ImportCsvData($filearray){
		
		for($i=0;$i<count($filearray);$i++){
			if(is_file($filearray[$i])){
				$filename = basename($filearray[$i]);
				if(preg_match("/[-](.*)[.]/", $filename, $langarray)){
					$langid = $langarray[1];
				}
				else $langid = "hu";
				
				ini_set('auto_detect_line_endings',TRUE);
				if (($handle = fopen($filearray[$i],"r")) !== false){
				
					while(!feof($handle)){
						$row = fgetcsv($handle, 1000, ";");
						if($filearray[$i] == "csv/product.csv"){
							
							$query = $this->connection->prepare("INSERT INTO product (id, externalid, price) VALUES (
																	:id,
																	:externalid,
																	:price
																	)"
																);

													$data = [
															":id"=>null,
															":externalid"=>$row[0],
															":price"=>$row[1]
															];

													$query->execute($data);
						}
						else{
							
							$pid = $row[0];
							$productarray = $this->getProductid($row[0]);

							$query = $this->connection->prepare("INSERT INTO producttext VALUES(
																	:externalId,
																	:languageId,
																	:name,
																	:description
																	)"
																);

													$data = [
															":externalId"=>$productarray["id"],
															":languageId"=>$langid,
															":name"=>$row[1],
															":description"=>$row[2]
															];

													$query->execute($data);
													
						}
					}
					fclose($handle);
					ini_set('auto_detect_line_endings',false);
				}
				else return print "Hiba a file-ban";
			}
			else return print "Nem megfelelő formátum";
		}
	}

	
}
?>