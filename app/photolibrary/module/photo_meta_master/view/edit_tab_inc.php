<?php
echo '<ul class="tabrow">';	
	
echo '<li '.(($tab=='general' ||  $tab=='')?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=general&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page).'">Meta Tag Category</a></li>';
	
echo '<li '.($tab=='subcategory'?'class="selected"':'').'>';
echo '<a href="'.actionURL('edit','?tab=subcategory&item_id='.$item_id.'&lot_id='.$lot_id.'&page='.$page).'">Sub Tag Category</a></li>';
	
echo '</ul>';

?>



