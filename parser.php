<?PHP
   $fileString = "\n";
   $array = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
   
   $date = time();
   echo $date;
   mkdir($date);
   foreach($array as $letter){
          $filename = "./$date/CrunchBaselinks_";
          $filename .= $letter;
	  
	  $url = "http://www.crunchbase.com/companies?c=";
          $url .= $letter;
	  $input = @file_get_contents($url) or die("Could not access file: $url");
	  $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	 
	  if(preg_match_all("/$regexp/siU", $input, $matches)) {
	    // $matches[2] = array of link addresses
	    // $matches[3] = array of link text - including HTML code
	  }
	  
	  foreach($matches[2] as $item){
	  	$fileString .= $item;
	  	$fileString .= "\n";
	  }
	  //should write this 
          //echo $fileString;
          file_put_contents($filename, $fileString);
          $fileString = "\n";
  }
  
?>
