<?php
$tpl= new template;
$tpl->define_theme("index.tpl");
$tpl->define_tag("{menu}",$menu);
$tpl->define_tag("{isi}",$isi);
$tpl->define_tag("{sess}",$sess);
$tpl->parse();
$tpl->printproses();
?>