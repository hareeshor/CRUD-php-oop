<?php
class Product{
 
    // database connection and table name
    private $conn;
    private $table_name = "products";
 
    // object properties
    public $id;
    public $name;
    public $price;
    public $description;
    public $category_id;
    public $timestamp;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create product
    function create(){
 
        // to get time-stamp for 'created' field
        $this->getTimestamp();
 
        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name = ?, price = ?, description = ?, category_id = ?, created = ?";
 
        $stmt = $this->conn->prepare($query);
 
        // posted values
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
 
        // bind values
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->price);
        $stmt->bindParam(3, $this->description);
        $stmt->bindParam(4, $this->category_id);
        $stmt->bindParam(5, $this->timestamp);
 
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
 
    }
	// used for the 'created' field when creating a product
	function getTimestamp(){
		date_default_timezone_set('Asia/Manila');
		$this->timestamp = date('Y-m-d H:i:s');
	}
	
	function readAll($page, $from_record_num, $records_per_page){
 
    $query = "SELECT
                id, name, description, price, category_id
            FROM
                " . $this->table_name . "
            ORDER BY
                name ASC
            LIMIT
                {$from_record_num}, {$records_per_page}";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
 
    return $stmt;
	}
	
	// used for paging products
	public function countAll(){
	 
		$query = "SELECT id FROM " . $this->table_name . "";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		$num = $stmt->rowCount();
	 
		return $num;
	}
	
	function readOne(){
 
		$query = "SELECT
					name, price, description, category_id
				FROM
					" . $this->table_name . "
				WHERE
					id = ?
				LIMIT
					0,1";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
	 
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
	}
	function update(){
 
		$query = "UPDATE
					" . $this->table_name . "
				SET
					name = :name,
					price = :price,
					description = :description,
					category_id  = :category_id
				WHERE
					id = :id";
	 
		$stmt = $this->conn->prepare($query);
	 
		// posted values
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->id=htmlspecialchars(strip_tags($this->id));
	 
		// bind parameters
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':category_id', $this->category_id);
		$stmt->bindParam(':id', $this->id);
	 
		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	// delete the product
	function delete(){
	 
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
	 
		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
}
?>