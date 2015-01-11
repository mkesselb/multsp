/**
 * 
 */

var colors = new Array();
colors[0] = "#FF0000";
colors[1] = "#BB0000";
colors[2] = "#EE0000";
colors[3] = "#AA0000";
colors[4] = "#DD0000";
colors[5] = "#990000";
colors[6] = "#CC0000";
colors[7] = "#880000";

function drawPieCategories(data){
	 	var pieData = new Array();
	 	var i = 0;
	 	for (var key in data) {
	 		  if (data.hasOwnProperty(key)) {
	 			 pieData[i] = {
	 					value: data[key],
	   					color: colors[i%6],
	   					highlight: colors[i%6],
	   					label: key
	   				};
	 			 i++;
	 		  }
	 	}
      
          			
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myPie = new Chart(ctx).Pie(pieData);
    	
}

function drawPieUsers(data){
 	var pieData = new Array();
 	var i = 0;
 	for (var key in data) {
 		  if (data.hasOwnProperty(key)) {
 			 pieData[i] = {
 					value: data[key],
   					color: colors[i],
   					highlight: colors[i],
   					label: key
   				};
 			 i++;
 		  }
 	}
  
      			
    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myPie = new Chart(ctx).Pie(pieData);
	
}