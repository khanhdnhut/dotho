<div class="container">
    <div style="margin: 0px; background: transparent url('<?php echo PUBLIC_IMG . "sondong/botslwrap-bg.gif"; ?>') repeat-x scroll 0px 0px; height: 239px;" class="row">
        <div class="color-link-footer">
            <div style="text-rendering: optimizelegibility; font-size: 13px; color: rgb(197, 197, 197); padding-left: 25px; padding-top: 10px;" class="col-sm-4">
                <div itemscope="" itemtype="http://schema.org/Organization">
                    <h3 style="font-weight: bold; color: rgb(255, 255, 255); font-size: 14px;">LIÊN HỆ</h3>

                    <p><strong><?php echo DEFAULT_SITE_NAME; ?></strong></p>

                    <p><?php echo TITLE_ADDRESS; ?>: <?php echo DEFAULT_ADDRESS; ?></p>

                    <p><?php echo TITLE_PHONE; ?>: 
                        <a href="tel:<?php echo DEFAULT_PHONE_VALUE; ?>" style="color: rgb(197, 197, 197);">
                            <?php echo DEFAULT_PHONE; ?>
                        </a> - <?php echo TITLE_HOTLINE; ?>: 
                        <a href="tel:<?php echo DEFAULT_PHONE_VALUE_2; ?>" style="color: rgb(197, 197, 197);">
                            <?php echo DEFAULT_PHONE_2; ?>
                        </a></p>

                    <p>Email: <a href="mailto:<?php echo DEFAULT_EMAIL; ?>" style="color: white;"><?php echo DEFAULT_EMAIL; ?></a></p>
                </div>

            </div>
            <div class="col-sm-8" style="padding-top:10px;">
                <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
                <div style='overflow:hidden;height:219px;width:100%;'>
                    <div id='gmap_canvas' style='height:219px;width:100%;'></div>
                    <div>
                        <small>
                            <a href="http://embedgooglemaps.com">									embed google maps							</a>
                        </small>
                    </div>
                    <div>
                        <small>
                            <a href="http://www.autohuren.world/locaties/amsterdam/">auto huren amsterdam</a>
                        </small>
                    </div>
                    <style>#gmap_canvas img{max-width:none!important;background:none!important}</style>
                </div>
                <script type='text/javascript'>
                    function init_map()
                    {
                        var myOptions = {
                            zoom: 13,
                            center: new google.maps.LatLng(<?php echo DEFAULT_GEO_POSITION; ?>),
                            mapTypeId: google.maps.MapTypeId.ROADMAP};
                        map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                        marker = new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(<?php echo DEFAULT_GEO_POSITION; ?>)});
                        infowindow = new google.maps.InfoWindow({content: '<strong><?php echo DEFAULT_MAP_TITLE; ?></strong>'});
                        google.maps.event.addListener(marker, 'click', function () {
                            infowindow.open(map, marker);
                        });
                        infowindow.open(map, marker);
                    }
                    google.maps.event.addDomListener(window, 'load', init_map);
                </script>
            </div>
        </div>
    </div>