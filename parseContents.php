<?
//parse contents based on the new line character
$array = array('t','u','v','w','x','y','z');
foreach($array as $letter){

$fileName = "/home/nofilter/public_html/nofiltermoment.com/scripts/CrunchBaselinks_";
$fileName .= $letter;
$fileString = file_get_contents($fileName);


$linkArray = preg_split("/[\n]/", $fileString);

//echo print_r($linkArray);

$linkArray = preg_grep("/^\/company*\//", $linkArray);

foreach($linkArray as $thisLink){
$link = "http://www.crunchbase.com/v/1";
//echo $linkArray[66];
$link .= $thisLink;
$link .= ".js";


//echo $link;

$input = @file_get_contents($link) or die("Could not access file: $url");

$object = json_decode($input);

//print_r($object);

$homepage = $object->{'homepage_url'};
$url = "http://data.alexa.com/data?cli=10&dat=snbamz&url=";
$url .= $homepage;

$xmlstr = @file_get_contents($url) or die("Could not access file: $url");
// $xml = simplexml_load_string($xmlstr);
$data_array = simplexml_load_string($xmlstr);
  $encoded = json_encode($data_array);
  $decoded = json_decode($encoded);
  
  $name = substr($thisLink, 9);
  $overview =  $object->{'overview'};
//  print_r($decoded);
//  echo "<br>";
  $testObject = $decoded->{'SD'};
  if(is_array($testObject)){
  	$pagerank = $decoded->{'SD'}[1]->{'POPULARITY'}->{'@attributes'}->{'TEXT'};
  	$backlinks = $decoded->{'SD'}[0]->{'LINKSIN'}->{'@attributes'}->{'NUM'};
  }
  else{
  	$backlinks = $decoded->{'SD'}->{'LINKSIN'}->{'@attributes'}->{'NUM'};
  	$pagerank = 0;
  }
  
  
  //echo $homepage;
  //echo "<br>";
  //echo $name;
  //echo "<br>";
  //echo $overview;
  //echo "<br>";
  //echo  $pagerank;
  //echo "<br>";
  //echo $backlinks;
  
  
//connect to the database
$server = 'localhost';
$dbuser = 'nofilter_tekom';
$dbpass = 'Rduce6646#';
$dbname = 'nofilter_tekometer';

$conn = mysql_connect($server,$dbuser,$dbpass) or die(mysql_error());
mysql_select_db($dbname,$conn);


  $homepage = mysql_real_escape_string($homepage);
  $name = mysql_real_escape_string($name);
  $overview = mysql_real_escape_string($overview);
  $pagerank = mysql_real_escape_string($pagerank);
  $backlinks = mysql_real_escape_string($backlinks);

//echo "INSERT INTO Company (Name,Link,Summary,PageRank,BackLinkCount)
//	VALUES ('$name','$homepage','$overview','$pagerank','$backlinks')";
	
mysql_query("INSERT INTO Company (Name,Link,Summary,PageRank,BackLinkCount)
	VALUES ('$name','$homepage','$overview','$pagerank','$backlinks')");



mysql_close($conn);
}

sleep(10);

}
?>