<?php
echo '<ul class="tabrow">';	
	
echo '<li '.(($tab=='general' ||  $tab=='')?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=general&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize).'">General</a></li>';
	
echo '<li '.($tab=='document'?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=document&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize).'">Document</a></li>';
	
echo '</ul>';

?>



