<?php
$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-Equiv="Cache-Control" content="no-cache"/>
    <meta http-Equiv="Pragma" Content="no-cache"/>
    <meta http-Equiv="Expires" content="0"/>
    <title></title>
    <link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css">
	<link rel="stylesheet" href="layersTable.css" type="text/css" />
    <style>
     #map-id {
       width: 700px;
       height: 400px;
       float:left;
       margin-left: 60px !important;
     }
     #layerswitcher.olControlLayerSwitcher{
       font-size:12px !important;
       font-family:sans-serif !important;
       font-weight: normal;
     }
     .olControlLayerSwitcher .layersDiv{
       background-color:#c0c0c0 !important;
       margin: 1.5em !important;
       padding-top:0;
       margin-top:0 !important;
     }
     #infoArrangement, #infoGeneral{
       font-size:12px !important;
       font-weight:normal;
       font-family:sans-serif !important;
     }
     .olControlLayerSwitcher {
       float:left;
       position: relative !important;
       top: 0 !important;
       right: 0;
       width: 12em !important;
     }
     
    </style>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
	 <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
	<script>
	jQuery(document).ready(function(){
		
	<?php if(!$geoserver_on){ ?>	
			jQuery('#map-id').html('<div style="text-align:center;width:400px;margin:auto;font-family:sans-serif"><h3>The map service is currently down.<br/>Please try again in a few minutes.</h3><span style="font-size:100px;color:red">&#8856</span></div>').height(300);
			return false;
	<?php } ?>
		
		//Check if we have access to parent document (normally not if the iframe is loaded from a different host
   var sameHost = false;
   try{
	 parent.document;
	 sameHost = true;
   }catch(e){
	 iFrame = null;
   }
   
//////////////////////////////////////////////////////////////
      var thisServer=window.location.hostname;

      var extent = new OpenLayers.Bounds(-180,-90,180,90);
      var minResolution=360/400.0;
      var maxResolution=5/400.0;
      var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),'ascending':false});
      var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});
      var options = {minResolution:minResolution, maxResolution:maxResolution,
      controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule]};
      
      var map = new OpenLayers.Map("map-id", options);
      layersSwitcher.maximizeControl();

      var world=new OpenLayers.Layer.WMS(
      "Countries (background)",
      "http://onesharedocean.org/geoserver/general/wms",
      {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname', format:'image/png'},
      {singleTile:true, isBaseLayer:true, visibility:true}
      );
      
      var worldtop=new OpenLayers.Layer.WMS(
      "Countries",
      "http://onesharedocean.org/geoserver/general/wms",
      {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
      {singleTile:true, isBaseLayer:false, visibility:true, opacity:1}
      );

      var lmes=new OpenLayers.Layer.WMS(
      "LMEs",
      "http://onesharedocean.org/geoserver/ocean/wms",
      {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels'},
      {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
      );

      var eez = new OpenLayers.Layer.WMS(
      "EEZ",
      "http://onesharedocean.org/geoserver/ocean/wms",
      {layers:"ocean:OBIS_eezs", transparent:true, styles:'eez_nofill_contour_orange_labels'},
      {isBaseLayer:false, opacity:1, visibility:false}
      )

      var iccat=new OpenLayers.Layer.WMS(
      "ICCAT",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_ICCAT", transparent:true, styles:'iccat_baltic_sea'},
      {singleTile:true, visibility:true, opacity:1, layerId:'ICCAT', displayInLayerSwitcher:false}
      );
      
      var ccsbt=new OpenLayers.Layer.WMS(
      "CCSBT",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_CCSBT", transparent:true, styles:'blue_0025ee_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'CCSBT', displayInLayerSwitcher:false}
      );

      var cecaf=new OpenLayers.Layer.WMS(
      "CECAF",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_CECAF", transparent:true, styles:'lime_dff400_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'CECAF', displayInLayerSwitcher:false}
      );
       
      var comhafat=new OpenLayers.Layer.WMS(
      "COMHAFAT",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_COMHAFAT", transparent:true, styles:'orange_ff7c00_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'COMHAFAT', displayInLayerSwitcher:false}
      );

      var corep=new OpenLayers.Layer.WMS(
      "COREP",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_COREP", transparent:true, styles:'purple_ee00e7_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'COREP', displayInLayerSwitcher:false}
      );

      var srfc=new OpenLayers.Layer.WMS(
      "SRFC",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_SRFC", transparent:true, styles:'violet_5c00ee_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'SRFC', displayInLayerSwitcher:false}
      );

      var abidjan=new OpenLayers.Layer.WMS(
      "Abidjan",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:Abidjan", transparent:true, styles:'abidjan_southeast_atlantic'},
      {singleTile:true, visibility:true, opacity:1, layerId:'Abidjan', displayInLayerSwitcher:false}
      );

      var seafo = new OpenLayers.Layer.WMS(
      "SEAFO",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_SEAFO", transparent:true, styles:'red_ff0038_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'SEAFO', displayInLayerSwitcher:false}
      );

      var fcwc = new OpenLayers.Layer.WMS(
      "FCWC",
      "http://onesharedocean.org/geoserver/arrangements/wms",
      {layers:"arrangements:RFB_FCWC", transparent:true, styles:'orange_ffaf00_transparent'},
      {singleTile:true, visibility:true, opacity:1, layerId:'FCWC', displayInLayerSwitcher:false}
      );

      map.addLayers([worldtop, ccsbt, cecaf, comhafat, corep, iccat, srfc, lmes, abidjan, seafo, fcwc, eez, world]);
      map.setLayerIndex(world, 0);
      map.setLayerIndex(iccat, 1);
      map.setLayerIndex(comhafat, 2);
      map.setLayerIndex(cecaf, 3);
      map.setLayerIndex(ccsbt, 4);
      map.setLayerIndex(abidjan, 5);
      map.setLayerIndex(srfc, 6);
      map.setLayerIndex(corep, 7);
      map.setLayerIndex(seafo, 8);
      map.setLayerIndex(fcwc, 9);
      map.setLayerIndex(worldtop, 10);
      map.setLayerIndex(lmes, 11);
      map.setLayerIndex(eez, 12);
      map.zoomToExtent([-70,-70,35,35]);
	  
	  	  var infoGnrl = new OpenLayers.Control.WMSGetFeatureInfo({
	       url:'http://onesharedocean.org/geoserver/ocean/wms',
	       title:'identify feature by clicking',
	       output:'features', infoFormat:'application/vnd.ogc.gml',
	       format: new OpenLayers.Format.GML,
	       eventListeners: {
	        getfeatureinfo: function(event) {
				eezName='';
				lmeName='';
				if (typeof(event.features[0])=='undefined'){document.getElementById('infoGeneral').innerHTML='&nbsp;'; return};
				for (ii=0; ii< event.features.length; ii++){
					//console.log(event.features[ii].attributes);
					thisEEZNAME=event.features[ii].attributes['eez'];
					thisLMENAME=event.features[ii].attributes['LME_NAME'];
					if (typeof(thisEEZNAME)!='undefined') {if (eezName=='') {eezName=thisEEZNAME} else {eezName+=', '+thisEEZNAME}};
					if (typeof(thisLMENAME)!='undefined') {if (eezName=='') {eezName=thisEEZNAME} else {lmeName+=', '+thisLMENAME}};
				}
	          document.getElementById('infoGeneral').innerHTML=eezName;
	        }
	       }
	    });
		
		uneprsid={'2053':'Abidjan', '170':'Antigua', '994':'Bucharest', '2041':'Helsinki', '2054':'Lima', '1125':'Jeddah', '1119':'Kuwait', '2051':'Noumea', '510':'Cartagena', '2049':'Barcelona', '1960':'Nairobi'};
		var infoArrangement = new OpenLayers.Control.WMSGetFeatureInfo({
	       url:'http://onesharedocean.org/geoserver/arrangements/wms',
	       title:'identify feature by clicking',
	       output:'features', infoFormat:'application/vnd.ogc.gml',
	       format: new OpenLayers.Format.GML,
	       eventListeners: {
	        getfeatureinfo: function(event) {
				rfbName='';
				if (typeof(event.features[0])=='undefined'){document.getElementById('infoArrangement').innerHTML='&nbsp;'; return};
				for (ii=0; ii< event.features.length; ii++){
					console.log(event.features[ii].attributes);
					thisRFBNAME=event.features[ii].attributes['RFB'];
					thisUnepRSID=event.features[ii].attributes['UNEP_RS_ID'];
					if (typeof(thisUnepRSID)!='undefined') {if (rfbName=='') {rfbName=uneprsid[thisUnepRSID]} else {rfbName+=', '+uneprsid[thisUnepRSID]}}
					if (typeof(thisRFBNAME)!='undefined'){if (rfbName=='') {rfbName=thisRFBNAME} else { rfbName+=', '+thisRFBNAME}};
					if (typeof(thisOther)!='undefined'){if (rfbName=='') {rfbName=thisRFBNAME} else {rfbName+=', '+thisOther}};
				}
	          document.getElementById('infoArrangement').innerHTML=rfbName;
	        }
	       }
	    });
	  
	  map.addControl(infoGnrl);
	  infoGnrl.activate();
	  map.addControl(infoArrangement);
	  infoArrangement.activate();
///////////////////////////////////////////////////////////   
   
   		<?php include('/data/iframes/oo/arrangements/layersTable.js'); ?>
		
	});
    </script>
  </head>
  <body>
    <div id="tableInfo"></div>
	<div style="width:700px; margin:auto; float:left">
      <div id="layerSelector" style="float:right">
	    <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="firstTD" style="background-color:#fff;"></td>
              <td theme="integration"></td>
              <td theme="fisheries"></td>
              <td theme="pollution"></td>
              <td theme="biodiversity"></td>
              <td theme="climate"></td>
              <td theme="abnj"></td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>

    <div style="clear:both"></div><br/>


    <div id="infoArrangement">&nbsp;</div>
    <div id="infoGeneral">&nbsp;</div>
    <div>
      <div id="map-id" ></div>
      <div id="layerswitcher" class="olControlLayerSwitcher"></div>
    </div>

  </body>
</html>
