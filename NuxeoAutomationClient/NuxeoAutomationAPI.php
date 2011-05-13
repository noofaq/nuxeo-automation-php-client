<?php
	
	include ('../NuxeoAutomationClient/NuxeoAutomationUtilities.php');
	
	/**
 	 * phpAutomationClient class
 	 *
 	 * Class which initializes the php client with an URL
 	 * 
 	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
 	 */
	class PhpAutomationClient {
		private $url;
		
		public function PhpAutomationCLient($url = 'http://localhost:8080/nuxeo/site/automation'){
			$this->url = $url;
		}
		
		/**
	 	 * getSession function
	 	 *
	 	 * Open a session from a phpAutomationClient
	 	 * 
	 	 * @var        $username : username for your session
	 	 * 			   $password : password matching the usename
	 	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
	 	 */
		public function GetSession($username = 'Administrator', $password = 'Administrator'){
			$this->session = $username . ":" . $password;
			$session = new Session($this->url, $this->session);
			return $session;
		}
	}
	
	/**
 	 * Session class
 	 *
 	 * Class which stocks username,password, and open requests
 	 * 
 	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
 	 */
	class Session {
		
		private $urlLoggedIn;
		private $headers;
		private $requestContent;
		
		public function Session( $url, $session, $headers = "Content-Type:application/json+nxrequest") {
			$this->urlLoggedIn = str_replace("http://", "", $url);
			if (strpos($url,'https') !== false){
				$this->urlLoggedIn = "https://" . $session . "@" . $this->urlLoggedIn;
			}elseif(strpos($url,'http') !== false){
				$this->urlLoggedIn = "http://" . $session . "@" . $this->urlLoggedIn;
			}else{
				throw Exception;
			}
			$this->headers = $headers;
		}
		
		/**
	 	 * newRequest function
	 	 *
	 	 * Create a request from a session
	 	 * 
	 	 * @var        $requestType : type of request you want to execute (such as Document.Create 
	 	 * 			  for exemple)
	 	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
	 	 */
		public function NewRequest($requestType){
			$newRequest = new Request($this->urlLoggedIn, $this->headers, $requestType);
			return $newRequest;
		}
	}
	
	/**
	 * Document class
	 *
	 * hold a return document
	 * 
	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
	 */
	class Document extends Documents{
		
		Private $object;
		Private $properties;
		
		Public function Document ($newDocument = NULL) {
			$this->object = $newDocument;
			if (array_key_exists('properties', $this->object))
				$this->properties = $this->object['properties'];
			else
				$this->properties = null;				
		}
		
		public function GetUid(){
			return $this->object['uid'];
		}
		
		public function GetPath(){
			return $this->object['path'];
		}
		
		public function GetType(){
			return $this->object['type'];
		}
		
		public function GetState(){
			return DateConverterNuxeoToPhp($this->object['state']);
		}
		
		public function GetTitle(){
			return $this->object['title'];
		}
		
		Public function Output(){
			$value = sizeof($this->object);
			
			for ($test = 0; $test < $value-1; $test++){
				echo '<td> ' . current($this->object) . '</td>';
				next($this->object);
			}
			
			if ($this->properties !== NULL){
				$value = sizeof($this->properties);
				for ($test = 0; $test < $value; $test++){
					echo '<td>' . key($this->properties) . ' : ' . 
					current($this->properties) . '</td>';
					next($this->properties);
				}
			}
		}
		
		public function OutputSelect(){
			echo '<option value="' . $this->GetPath() . '">' . $this->GetTitle() . '</option>';
		}
		
		public function GetContent(){
			return $this->object;
		}
		
		public function Get($schemaNamePropertyName){
			if (array_key_exists($schemaNamePropertyName, $this->properties)){
				echo key($this->properties) .  ' : ' . current($this->properties) . '<br />';
			}
		}
	}
	
	/**
	 * Documents class
	 *
	 * hold an Array of Document
	 * 
	 * @author     Arthur GALLOUIN for NUXEO agallouin@nuxeo.com
	 */
	class Documents {
		
		private $documentsList;
		
		public function Documents($newDocList){
			$this->documentsList = null;
			$test = true;
			if (!empty($newDocList['entries'])){
				while (false !== $test) {
					$this->documentsList[] = new Document(current($newDocList['entries']));
					$test = each($newDocList['entries']);
				}
				$test = sizeof($this->documentsList);
				unset($this->documentsList[$test-1]);
			}
			elseif(!empty($newDocList['uid'])){
				$this->documentsList[] = new Document($newDocList);
			}elseif(is_array($newDocList)){
				echo 'file not found';
			}else{
				return $newDocList;
			}
		}
		
		public function Output(){
			$value = sizeof($this->documentsList);
			echo '<table>';
			echo '<tr><TH>Entity-type</TH><TH>Repository</TH><TH>uid</TH><TH>Path</TH>
			<TH>Type</TH><TH>State</TH><TH>Title</TH><TH>Download as PDF</TH>';
			for ($test = 0; $test < $value; $test ++){
				echo '<tr>';
				current($this->documentsList)->Output();
				echo '<td><form id="test" action="../tests/B5bis.php" method="post" >';
				echo '<input type="hidden" name="a_recup" value="'. 
				current($this->documentsList)->GetPath(). '"/>';
				echo '<input type="submit" value="download"/>';
				echo '</form></td></tr>';
				next($this->documentsList);
			}
			echo '</table>';
		}
		
		public function OutputSelect(){
			$value = sizeof($this->documentsList);
			echo '<select name="DocList">';
			for ($test = 0; $test < $value; $test ++){
				current($this->documentsList)->OutputSelect();
				next($this->documentsList);
			}
			echo '</select>';
		}
		
		public function GetContent(){
			return $this->documentsList;
		}
		
		public function OutputDocument($number){
			$value = sizeof($this->documentsList);
			if ($number < $value AND $number >= 0)
				return $this->documentsList[$number];
			else
				return null;
		}
		
		public function GetDocumentList(){
			return $this->documentsList;
		}
	}
	
	
?>