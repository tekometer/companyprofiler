function FetchCtrl($scope, $http, $templateCache) {
  $scope.method = 'GET';
  //$scope.url = 'http-hello.html';

  $scope.fetch = function() {
    $scope.code = null;
    $scope.response = null;

    $http({method: $scope.method, url: "http://54.213.11.100/getCompanyJSON.php?query="+$scope.url, cache: $templateCache}).
      success(function(data, status) {
        $scope.status = status;
        $scope.data = data;
	
	//Width and height
	var w = 500;
	var h = 100;
	var dataset = [];
	//alert(data[0].pagerank + ' ' +data[0].total_money_raised);
	for(var i=0; i < data.length; i++)
	{
		dataset.push([data[i].pagerank/100, data[i].total_money_raised/1000000]);
	}
	//alert(dataset[0]);
	//var dataset = [
	//		[5, 20], [480, 90], [250, 50], [100, 33], [330, 95],
	//		[410, 12], [475, 44], [25, 67], [85, 21], [220, 88]];																																  	
	//Create SVG element
	var svg = d3.select("body")
			.append("svg")
			.attr("width", w)
			.attr("height", h);
			
	svg.selectAll("circle")
		.data(dataset)
		.enter()																					      .append("circle")
		.attr("cx", function(d) {
			return d[0];
		})
		.attr("cy", function(d) {
			return d[1];
		})
		.attr("r", 5);
      }).
      error(function(data, status) {
        $scope.data = data || "Request failed";
        $scope.status = status;
    });
  };

  $scope.updateModel = function(method, url) {
    $scope.method = method;
    $scope.url = url;
  };
}

