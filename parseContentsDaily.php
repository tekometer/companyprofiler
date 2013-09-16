<?PHP

include 'parser.php';

//connect to the database
//$server = 'localhost';
//$dbuser = 'nofilter_tekom';
//$dbpass = 'Rduce6646#';
//$dbname = 'nofilter_tekometer';

//$conn = mysql_connect($server,$dbuser,$dbpass) or die(mysql_error());
//mysql_select_db($dbname,$conn);
//$results = mysql_query("SELECT Name FROM Company WHERE 1");
//$nameArray = array();
//while($queryResults = mysql_fetch_assoc($results)){
//	array_push($nameArray, $queryResults['Name']);
//}

//parse contents based on the new line character
//$newArrayResults = array();
$array = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
$dbhost = 'localhost';
$dbname = 'company';

	// Connect to test database
$m = new Mongo("mongodb://$dbhost");
$db = $m->$dbname;

// Get the users collection
$c_testcompany = $db->company;

	// Insert this new document into the users collection

//$prevResults = array();
foreach($array as $letter){
$fileName = "./CrunchBaselinks_";
$fileName .= $letter;
$fileString = file_get_contents($fileName);
$linkArray = preg_split("/[\n]/", $fileString);
$linkArray = preg_grep("/^\/company*\//", $linkArray);
//$newLinks = array();
//$companyArray = array();
//$linkResultArray = array();

//foreach($linkArray as $company){
	//check DB and add if needed
//	$link = $company;
//	$company = substr($company, 9);
//        array_push($companyArray, $company);
        //array_push($newArrayResults, $company);
	//ompany = mysql_real_escape_string($company);
	//$result = mysql_query("SELECT Name FROM Company WHERE Name='$company'");
	//$num_results = mysql_num_rows($result);
        //$test = array_search($company, $nameArray);
	//if($test == FALSE || $test == 0){
	//	echo "\n$company";
	//	array_push($newLinks, $link);
//	}

//}


//$difference = array_diff($companyArray, $nameArray);
//if($letter != 'a'){
//    $difference = array_diff($difference, $prevResults);
//}
//print_r($difference);

//$finalArray = array();
//$prevResults = array();
//foreach($difference as $value){
//        array_push($prevResults, $value);
//	$temp = "/company/";
//        $temp .= $value;
//        array_push($finalArray, $temp);
//}

//print_r($finalArray);

foreach($linkArray as $thisLink){
$link = "http://api.crunchbase.com/v/1";
//echo $linkArray[66];
$link .= $thisLink;
$link .= ".js?api_key=nvkdrsprbjdrhexembfgpasp";
echo $link;
echo "\n";
$input = @file_get_contents($link);
if($input != FALSE){
$object = json_decode($input);
$homepage = $object->{'homepage_url'};
//echo "\n$homepage";
$url = "http://data.alexa.com/data?cli=10&dat=snbamz&url=";
$url .= $homepage;

$xmlstr = @file_get_contents($url);
if($xmlstr != FALSE){
$data_array = simplexml_load_string($xmlstr);
  $encoded = json_encode($data_array);
  $decoded = json_decode($encoded);
  
  $name = substr($thisLink, 9);
  $overview =  $object->{'overview'};
  $testObject = $decoded->{'SD'};
  $pageRank = 0;
  $backlinks = 0;
  if(is_array($testObject)){
  	$pagerank = $decoded->{'SD'}[1]->{'POPULARITY'}->{'@attributes'}->{'TEXT'};
  	$backlinks = $decoded->{'SD'}[0]->{'LINKSIN'}->{'@attributes'}->{'NUM'};
  }
  else{
  	$backlinks = $decoded->{'SD'}->{'LINKSIN'}->{'@attributes'}->{'NUM'};
  	$pagerank = 0;
  }



  //$homepage = mysql_real_escape_string($homepage);
  //$name = mysql_real_escape_string($name);
  //$overview = mysql_real_escape_string($overview);
  //$pagerank = mysql_real_escape_string($pagerank);
  //$backlinks = mysql_real_escape_string($backlinks);
$object->{'pagerank'} = $pagerank;
$object->{'backlinks'} = $backlinks;

//$encodedFinalJSON = json_encode($object);

//echo $object;
//echo "\n";


$c_testcompany->save($object);

//echo "INSERT INTO Company (Name,Link,Summary,PageRank,BackLinkCount)
//	VALUES ('$name','$homepage','$overview','$pagerank','$backlinks')";
//echo "\n";
	
//mysql_query("INSERT INTO Company (Name,Link,Summary,PageRank,BackLinkCount)
//	VALUES ('$name','$homepage','$overview','$pagerank','$backlinks')");


}

}
 
}

}

//mysql_close($conn);

?>
