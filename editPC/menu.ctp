<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
    <style>
      #container{
        position:relative;
        height:700px;
      }
      #directions{
        position:absolute;
        width: 23%;
        right:1%;
        height: 690px;
        overflow:auto;
      }
      #googleMap{
        border: 1px dashed #C0C0C0;
        width: 75%;
        height: 700px;
      }
      #gmap3-menu{
      background-color: #FFFFFF;
      width:170px;
      padding:0px;
      border:1px;
      cursor:pointer;
      border-left:1px solid #cccccc;
      border-top:1px solid #cccccc;
      border-right:1px solid #676767;
      border-bottom:1px solid #676767;
      }
      #gmap3-menu .item{
      font-family: arial,helvetica,sans-serif;
      font-size: 12px;
      text-align:left;
      line-height: 30px;
      border-left:0px;
      border-top:0px;
      border-right:0px;
      padding-left:30px;
      background-repeat: no-repeat;
      background-position: 4px center;
      }
      #gmap3-menu .item.itemA{
        background-image: url(images/icon_greenA.png);
      }
      #gmap3-menu .item.itemB{
        background-image: url(images/icon_greenB.png);
      }
      #gmap3-menu .item.zoomIn{
        background-image: url(images/zoomin.png);
      }
      #gmap3-menu .item.zoomOut{
        background-image: url(images/zoomout.png);
      }
      #gmap3-menu .item.centerHere{
        background-image: url(images/zoomout.png);
      }
      #gmap3-menu .item.hover{
        background-color: #d6e9f8;
      }
      #gmap3-menu .item.separator{
        border-bottom:1px solid #cccccc;
      }
    </style>
    
    <script type="text/javascript">
      $(function(){
      
        var $map = $("#googleMap"), 
          menu = new Gmap3Menu($map),            
          current; 

        // MENU : ITEM 3
        menu.add("Zoom in", "zoomIn", 
          function(){
            var map = $map.gmap3("get");
            map.setZoom(map.getZoom() + 1);
            menu.close();
          });
        
        // MENU : ITEM 4
        menu.add("Zoom out", "zoomOut",
          function(){
            var map = $map.gmap3("get");
            map.setZoom(map.getZoom() - 1);
            menu.close();
          });*/
        
        // MENU : ITEM 5
        menu.add("Center here", "centerHere", 
          function(){
              $map.gmap3("get").setCenter(current.latLng);
              menu.close();
          });
        
        // INITIALIZE GOOGLE MAP
        $map.gmap3({
          map:{
            options:{
              center:[48.85861640881589, 2.3459243774414062],
              zoom: 5
            },
            events:{
              rightclick:function(map, event){
                current = event;
                menu.open(current);
              },
              click: function(){
                menu.close();
              },
              dragstart: function(){
                menu.close();
              },
              zoom_changed: function(){
                menu.close();
              }
            }
          },
          // add direction renderer to configure options (else, automatically created with default options)
          directionsrenderer:{
            divId:"directions",
            options:{
              preserveViewport: true,
              markerOptions:{
                visible: false
              }
            }
          }
        });
      });
    </script>  
  </head>
    
  
    <div id="container">
      <div id="directions"></div>
      <div id="googleMap"></div>
    </div>
    <pre>    
    <?php print_r($detallado)?>
    <pre>
  

