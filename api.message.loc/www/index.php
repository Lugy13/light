<?php
        header('Access-Control-Allow-Origin: http://localhost:3000');
        
	function connect() {
	    $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "message_api";
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                return array(
				'error' => 'DB fail'
                );
            }
	    return $conn;
	}

	function apiGetMes() {
            $conn = connect();
            $messages_list;
            
            $sql = "SELECT * FROM messages";
            $messages = $conn->query($sql);

            if ($messages->num_rows > 0) {
                while($row = $messages->fetch_assoc()) {
                    $message = $row;
                    $messages_list[] = $message;
                }
                $conn->close();
            }
            
            return $messages_list;
        }
        
        function apiSendMes() {
            $conn = connect();
            if(isset($_GET['m_text']))
            {
                $text = $_GET['m_text'];
                unset($_GET['m_text']);
            } else {
                return false;
            }
            $sql = "INSERT INTO messages (text) values('".$text."')";
            $conn->query($sql);
            
            $messages_list = apiGetMes();
            return $messages_list;
        }
        
        function  apiSendCom() {
            $conn = connect();
            if(isset($_GET['m_text']) && isset($_GET['m_id']))
            {
                $text = $_GET['m_text'];
                unset($_GET['m_text']);
                $message_id = $_GET['m_id'];
                unset($_GET['m_id']);
            } else {
                return false;
            }
            $sql = "INSERT INTO messages (text, message_id) values('".$text."', '".$message_id."')";
            $conn->query($sql);
            
            $messages_list = apiGetMes();
            return $messages_list;
        }
        
        if(isset($_GET['func']))
        {
            if(function_exists($_GET['func']))
            {
                $data = $_GET['func']();
                echo json_encode($data);
            } else {
                echo json_encode("Error");
            }
        }
?>
