<?php
$templateCache = true;
$sourceCache = true;
if($templateCache == true){
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LMES ICEP</title>
    <link rel="stylesheet" href="/sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.css" />
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1//jquery-ui.min.js"></script>
    
	<script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/highcharts.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/modules/exporting.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/highcharts-more.js"></script>
    <script type="text/javascript" src="/iframes/common/js/lmes.js"></script>
	
	<style>
      .ui-autocomplete{max-height:100px, overflow-y:auto;overflow-x:hidden;}
      html .ui-autocomplete{height:100px} /* IE6 does not support max-height */
      .ui-widget{font-size:12px;}
	  #viewData {
		font-family: Verdana;
		font-size: 10px;
		cursor: pointer;
	  }
	  .highcharts-tooltip span {
		background-color:white;
		z-index:9999!important;
		/*border: 1px solid #000000;*/
		margin:0;
		/*padding: 5px;*/
		top: 0;
		left:0;
		
	}
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
	  //if (!console){ var console = { log: function() {}};}
	  
  
         var thisLMECode = "19";
        var addedLMECode = -1;
		var plotCounter = -1;
		var categoryPlots = [thisLMECode];
		var outdata = '../'+"data"+'/data.csv';		
		
		Highcharts.SVGRenderer.prototype.symbols.cross = function (x, y, w, h) {
        return ['M', x, y, 'L', x + w, y + h, 'M', x + w, y, 'L', x, y + h, 'z'];
    };
    if (Highcharts.VMLRenderer) {
        Highcharts.VMLRenderer.prototype.symbols.cross = Highcharts.SVGRenderer.prototype.symbols.cross;
    }
		

		function genChart() {
			
		
		 var options = {
             credits:{enabled:false},
             chart: {renderTo: 'container', type: 'column', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-75},
                     theme:{fill: 'white', stroke:'red', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                    }
             },
			legend:{align:'center', layout:'horizontal', itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
             title: { text: '', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             xAxis: { type: 'linear', title:{text:'LME number'}, categories: categoryPlots},
             yAxis: { title: { text: 'Count Density (counts/km<sup>2</sup>)' , useHTML:true}, floor:0 },
             series: [],
			 
             plotOptions:{
				column:{
					animation:false,
					events: {
						legendItemClick: function () {
							//return false;
						}
					}
				}
			 },
			 tooltip:{
				valueDecimals: 2,
				useHTML: true,
				style: {'z-index':9999999},
				positioner: function (labelWidth, labelHeight, point) {
					return { x: point.plotX, y: point.plotY-20 };
				},
				formatter: function() {
					var tt = this.point.category+" ";
					jQuery.each(availableTags, function(index, value){
						if(value.indexOf(tt) != -1){
							tt = value;
						}
					});
					return '<b>LME# '+tt+'</b><br />'+this.point.series.name+': '+this.y.toFixed(2)+' counts/km<sup>2</sup>';
				}
			}};

			var options2 = {
             credits:{enabled:false},
             chart: {renderTo: 'container2', type: 'column', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-75},
                     theme:{fill: 'white', stroke:'red', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                    }
             },
			legend:{align:'center', layout:'horizontal', itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
             title: { text: '', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             xAxis: { type: 'linear', title:{text:'LME number'}, categories: categoryPlots},
             yAxis: { title: { text: 'Weight Density (g/km<sup>2</sup>)' , useHTML:true}, floor:0 },
             series: [],
			 
             plotOptions:{
				column:{
					animation:false,
					events: {
						legendItemClick: function () {
							//return false;
						}
					}
				}
			 },
			 tooltip:{
				valueDecimals: 2,
				useHTML: true,
				style: {'z-index':9999999},
				positioner: function (labelWidth, labelHeight, point) {
					return { x: point.plotX, y: point.plotY-20 };
				},
				formatter: function() {
					var tt = this.point.category+" ";
					jQuery.each(availableTags, function(index, value){
						if(value.indexOf(tt) != -1){
							tt = value;
						}
					});
					return 'LME '+tt+'<br />'+this.point.series.name+': '+this.y.toFixed(2)+' g/km<sup>2</sup>';
				}
			}};

  
  
  
  
  plotCounter++;
  
  var microC={name:'Micro count density', data:[], color: lineColors[0]};
  var microW={name:'Micro weight density', data:[], color: lineColors[0]};
  var macroC={name:'Macro count density', data:[], color: lineColors[1]};
  var macroW={name:'Macro weight density', data:[], color: lineColors[1]};
  var totalC={name:'Total count density', data:[], color: lineColors[2]};
  var totalW={name:'Total weight density', data:[], color: lineColors[2]};
  
  
  var rand = Math.floor(Math.random()*999999999);
  $.get(outdata<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
 
 
     var lines = data.split('\n');
	 var plotLME={};
             iplot=0;
	 $.each(lines, function(lineNo, line) {
		if (line) { // ignore empty line (else lines are not drawn)
          var items = line.split(',');
  
        for(var u=0;u<categoryPlots.length;u++){	
			if(items[0] == categoryPlots[u]){
				microC.data.push(parseFloat(items[2]));
				microW.data.push(parseFloat(items[3]));
				macroC.data.push(parseFloat(items[4]));
				macroW.data.push(parseFloat(items[5]));
				totalC.data.push(parseFloat(items[6]));
				totalW.data.push(parseFloat(items[7]));
			}
		}
       }
	 });
	
	options.series.push(microC);
	options2.series.push(microW);
	options.series.push(macroC);
	options2.series.push(macroW);
	options.series.push(totalC);
	options2.series.push(totalW);
	
	
	if(chart != false) {
		chart.destroy();
	}
	if(chart2 != false) {
		chart2.destroy();
	}
		chart = new Highcharts.Chart(options);
		chart2 = new Highcharts.Chart(options2);
 });
 }
 var chart = false;
 var chart2 = false;
 //end f
 //Init Chart
 genChart();
	//Check if we have access to parent document (normally not if the iframe is loaded from a different host
	var sameHost = false;
	try{
		parent.document;
		sameHost = true;
	}catch(e){
		iFrame = null;
	}
	//Define the behaviour of the View Data link according to the host permissions
	$('#viewData').click(function(){
		var sourceURL = "http://onesharedocean.org/?q=data#243";
		if(sameHost){
			window.parent.window.location = sourceURL;
		} else {
			copyToClipboard(sourceURL);
		}
	});
	//If host allows it, resize the frame from within
	if(sameHost){
		if (window.frameElement != null) {
			var iFrame = parent.document.getElementById(window.frameElement.getAttribute('id'));
			if(iFrame != null){
				iFrame.style.height = '500px';
			}
		}
	}
 
	    //add the jquery search
	    $(function() {
		


			$('#addPlot')
			.click(function(){
				$('#resetPlot').attr('disabled', false);
				$('#tags').prop('value', '');
				$('#tags').focus();
				addedLMECode = $('#addPlot').attr('rel');
				categoryPlots.push(addedLMECode);
				genChart();
				
				if (plotCounter == 2) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					categoryPlots = [thisLMECode];
					genChart(thisLMECode);
					
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
				});
			
			
			var comboText = "Type LME code or name";
			var maxComboText = 'Maximum number of datasets reached';
			$( "#tags" ).css('color', '#c0c0c0');

			$( "#tags" )
			
			.click(function(){
				if(this.value == comboText){
					this.value = "";
					$( "#tags" ).css('color', '#000000');
				}
			})
			.blur(function(){
				if(this.value ==""){
					$( "#tags" ).css('color', '#c0c0c0');
					$('#addPlot').attr('disabled', true);
					this.value = comboText;
				}
			})
			.autocomplete({
              source: availableTags,
			  open: function(e, ui) {
                    var list = '';
                    var results = $('ul.ui-autocomplete.ui-widget-content a');
                    results.each(function() {
                        list += $(this).html() + '<br />';
                    });
                    $('#results').html(list);
                },
              select: function(e, ui) {
                  var lmename = ui.item.value.split(' ');
                  var lmecode = lmename.shift();
                  $('#addPlot').attr('rel',lmecode);
				  //document.getElementById('addPlot').innerHTML=lmecode;
			  
				  document.getElementById('addPlot').disabled = false;
				  
				  
	          addedLMECode=lmecode;
              }
            });
	    });

 var availableTags=[ "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland" ];
		
      }); //get
   
  </script>

</head>
  <body>

      <div class="ui-widget" style="margin:auto; width:500px">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
	<input id="addPlot" type="button" value="add LME" disabled="disabled"></input>
	<input id="resetPlot" type="button" value="Reset plot" disabled="disabled"></input>
      </div>

	  
	  
	<table style="wisth:100%">
		<tr>
			<td colspan=2 style="font: 14px Verdana, sans-serif; text-align: center">
				Plastics Model Distribution (Greenland Sea)
			</td>
		</tr>
		<tr>
			<td>
				<div id="container" style="width:430px; height:400px;"></div>
			</td>
			<td>
				<div id="container2" style="width:430px; height:400px;"></div>
			</td>
		</tr>
	</table>

    <div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
    </body>
</html>
