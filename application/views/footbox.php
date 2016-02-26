<div class="footbox">
    <h2>Kontak Kami</h2>
    <ul>
      <li>Alamat: <?= $contact->alamat ?> <?= $contact->kode_pos ?></li>
      <li>Telp: <?= $contact->telp ?></li>
      <li>Fax: <?= $contact->fax ?></li>
      <li>Email: <?= $contact->email ?></li>
      <li class="last">Website: <?= $contact->website ?></li>
    </ul>
</div>
<div class="footbox">
    <h2>Program Studi</h2>
    <ul style="list-style-type: square; margin-left: 15px;">
        <?php 
            $prodi = $this->m_main->get_list_prodi()->result();
            foreach ($prodi as $data) { ?>
        <li><b><a style="color: #ccc;" href="<?= base_url('main/detailprodi/'.$data->id.'/'.  post_slug($data->judul)) ?>"><?= $data->link ?></a></b></li>
        <?php } ?>
    </ul>
</div>
<div class="footbox">
    <h2>Peta AKN</h2>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><div style="overflow:hidden;height:160px;width:400px;"><div id="gmap_canvas" style="height:160px;width:400px;"></div><style>#gmap_canvas img{max-width:none!important;background:none!important}</style><a class="google-map-code" href="http://www.themecircle.net" id="get-map-data">themecircle</a></div><script type="text/javascript"> function init_map(){var myOptions = {zoom:8,center:new google.maps.LatLng(-7.0150523,109.50597770000002),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(-7.0150523, 109.50597770000002)});infowindow = new google.maps.InfoWindow({content:"<b>AKN Kajen</b><br/>Jalan Bahurekso No. 1<br/> Pekalongan" });google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);</script>
</div>