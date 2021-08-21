<?php
echo '<ul class="tabrow">';	
	
echo '<li '.(($tab=='general' ||  $tab=='')?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=general&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page).'">General</a></li>';

echo '<li '.($tab=='media'?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=media&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page).'">Article Media</a></li>';



	
echo '</ul>';

?>



