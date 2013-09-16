<?PHP

#echo "I want to do something visual";

$query = $_REQUEST['query'];

if(isset($query) == false)
{
	$query = "medical";
}

$dbhost = "localhost";
$dbname = "company";

$connection = new MongoClient();
$collection = $connection->company->company;

$fields = array('homepage_url', 'name', 'pagerank', 'category_code', 'backlinks', 'total_money_raised');

$regex = new MongoRegex("/$query/");

$query = array('overview'=>$regex, 'pagerank'=>array('$ne'=>0), 'total_money_raised'=>array('$ne'=>"$0"));

$documents = $collection->find($query, $fields);

$documents->sort(array('pagerank'=>1));

$documents->limit(200);

$million = 1E6;
$thousand = 1E3;

$json_document = array();

foreach($documents as $document)
{
	$total_raised = $document['total_money_raised'];
	if(preg_match("/[$].*M/i", $total_raised))
	{
		$total_raised = preg_replace("/M/i", "", $total_raised);
		$total_raised = preg_replace("/[$]/", "", $total_raised);
		//$total_raised = preg_replace("/[.]/", "", $total_raised);
		$total_raised = $total_raised * $million;
		$document['total_money_raised'] = $total_raised;
		array_push($json_document, $document);
		//print_r($document);
	}
	else if(preg_match("/[$].*K/i", $total_raised))
	{
		$total_raised = preg_replace("/K/i", "", $total_raised);
		$total_raised = preg_replace("/[$]/", "", $total_raised);
		$total_raised = $total_raised * $thousand;
		$document['total_money_raised'] = $total_raised;
		array_push($json_document, $document);
		//print_r($document);
	}
	#$total_raised = preg_replace("/M/i", $million, $total_raised);
	#$total_raised = preg_replace("/K/i", $thousand, $total_raised);
	#$total_raised = preg_replace("/[.]/", "", $total_raised);
	//$total_raised = preg_replace("/[$]/", "", $total_raised);
	//echo "\n";
	//echo $total_raised;
	//$document['total_money_raised'] = $total_rasied;
	//print_r($document);
}

$json_document = json_encode($json_document);
print($json_document);
//foreach($json_document as $document)
//{
//	print_r($document);
//}

#echo $document;

#var_dump($document);


#echo "Where is my document?";

?>

