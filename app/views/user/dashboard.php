<?php require_once APPROOT . '/views/includes/header.php'; 
 $statsData = $data["StatsTable"];?>





 
<div class="page-wrapper">
   
        
    
    <!-- PAGE CONTENT-->
    <div class="page-content--bgf7 text-center">
    <div class="container">
    <div class="row h-100 ml-1 ">
    <div class="col-sm-12 col-md-6 col-lg-6 my-auto" >
    <section class="statistic statistic2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div > 
  
                                <h2 class="number"><?=$statsData->confirmed+$statsData->recovered+$statsData->deceased?></h2>
                                <span class="desc"><strong>Total</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div >
                                <h2 class="number"><?=$statsData->confirmed?></h2>
                                <span class="desc"><strong>Active</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div >
                                <h2 class="number"><?=$statsData->recovered?></h2>
                                <span class="desc"><strong>Recovered</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div >
                                <h2 class="number"><?=$statsData->deceased?></h2>
                                <span class="desc"><strong>Deaths</strong></span>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 my-auto" >
        <section class="statistic statistic2">
                <div class="container ">
                    <div class="row ">
                        <div class="col-md-6 col-lg-6">
                            <div > 
                                 <h2 class="number"><?=$statsData->quarantined_home?></h2>
                                <span ><strong>Home Quarantine</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div >
                                <h2 class="number"><?=$statsData->quarantined_government?></h2>
                                <span ><strong>Institutional Quarantine</strong></span>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </section>
        </div>
    </div>
<hr>
  <div class="row h-100 ml-1">
 
  
    <div class="col-sm-12 col-md-6 col-lg-6 my-auto" >
    
            <?php include 'map.php';
             ?>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 my-auto" > 
    
    <div id="map" style="width:100%; height:450px;">
    </div>
  </div>
  
    </div>
    <hr>
    <div class="row">
   
    <div class="col-sm-12 col-md-6 col-lg-6 my-auto" style="padding:20px" > <h2>Covid Chart</h2><hr> <a  class="clickerx" data-toggle="modal" data-target="#largeModl" cursor="pointer"><canvas id="xChart" width="600" height="600"></canvas> </a>
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6 my-auto" style="padding:20px" ><h2>Quarantine Chart</h2><hr><a  class="clickery" data-toggle="modal" data-target="#largeModl" cursor="pointer"><canvas id="yChart" width="600" height="600"></canvas></a> 
  </div>
    </div>
  </div>

   
  
              
  <?php $x =$data["table"];  require_once APPROOT . '/views/user/chart.php'; ?>





  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v1.9.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.9.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/es6-promise@4.2.4/dist/es6-promise.auto.min.js"></script>
<script src="https://unpkg.com/@mapbox/mapbox-sdk/umd/mapbox-sdk.min.js"></script>

<script>
   var myVar;
    mapboxgl.accessToken = 'pk.eyJ1IjoibmlraGlsYmhhdHQiLCJhIjoiY2s4bDllM2ZxMDFoNjNmcW8xaTc2aDlkYyJ9.tXFt2KSNEvK38YNSfZ92MQ';
    var mapboxClient = mapboxSdk({ accessToken: mapboxgl.accessToken });
    var map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/mapbox/outdoors-v10',
                        center: [79.37760,30.00000],
                        zoom: 7,
                        interactive:false
                        });
    map.scrollZoom.disable();
    var hotspotlist=[]
    fetch("<?php echo URLROOT;?>public/hotspotdata.json")
        .then(response=>response.json())
        .then(rsp=>{
        
        rsp.forEach(element=>{
                    var district=element.district;
                    var city=element.city;
                    var date=element.date;
                    var active=element.active;
                    var recovered=element.recovered;
                    var death=element.deceased;
                    var hq=element.homequarantine;
                    var fq=element.facilityquarantine;
                    var long=element.longitude;
                    var lat=element.latitude;
                    hotspotlist.push([long,lat,district,city,active,recovered,death,hq,fq,date]);
        });
        });  

    function loadHotspot()
    {
        console.log(hotspotlist)
        var i;
        for(i=0;i<hotspotlist.length;i++)
        {
            var el=document.createElement('div');
            var el1=document.createElement('div');
            var el2=document.createElement('div');
            el.className='ring-container';
            el1.className='ringring';
            el2.className='circle';
            el.appendChild(el1);
            el.appendChild(el2);
            new mapboxgl.Marker(el).setLngLat([hotspotlist[i][0],hotspotlist[i][1]])
                                .setPopup(new mapboxgl.Popup({})
                                .setHTML('<h5>' +hotspotlist[i][3]+', '+hotspotlist[i][2] + '</h5><p style="color:black;"> Active='+hotspotlist[i][4]+'</p><p style="color:black;"> Recovered='+hotspotlist[i][5]+'</p><p style="color:black;"> Death='+hotspotlist[i][6]+'</p><p style="color:black;">Home Quarantine='+hotspotlist[i][7]+'</p><p style="color:black;">Fascility Quarantine=' + hotspotlist[i][8] + '</p>'))
                                .addTo(map);
        }
    }
    function loadFunction()
        {
            if(Array.isArray(hotspotlist)&& hotspotlist.length)
            {
                clearInterval(myVar);
                setTimeout(loadHotspot,1000);
            }
            else
            {
                myVar=setInterval(loadFunction,5000);
            }
        }

    setTimeout(loadFunction, 1500); 
</script>










<div class="xcols modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="mediumModalLabel">Medium Modal</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<?php include "tableM.html"; ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary">Confirm</button>
						</div>
					</div>
				</div>
            </div>
            


<div class="ycols modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="mediumModalLabel">Medium Modal</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<?php include "tableQ.html"; ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary">Confirm</button>
						</div>
					</div>
				</div>
            </div>
            




            <script>

$(document).ready(function(){
     
$('.clickerx').click(function(){
 

  $('.xcols').modal('show'); 
   
  // AJAX request

});
$('.clickery').click(function(){
 

 $('.ycols').modal('show'); 
  
 // AJAX request

});
});
  
    </script>






        <?php require_once APPROOT . '/views/includes/footer.php'; ?>
     
