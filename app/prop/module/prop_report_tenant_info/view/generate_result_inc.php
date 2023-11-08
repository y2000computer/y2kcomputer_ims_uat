<?php
require __DIR__.'/../../../../template/report_header_inc.php';
echo '<DIV id="BodyDiv">';
?>

	<table class="searchResult" border="0" cellspacing="0" cellpadding="0">
		<tbody>
		
		<tr>
		<th colspan="5" align="left">Report: Tenant Information Report</th>
		</tr>

		<tr>
		<th colspan="5" align="left">Building: <?php echo $general["eng_name"];?></th>
		</tr>

		<tr>
		<th colspan="5" align="left">Report Date: <?php echo date('d/m/Y  H:i:s');?></th>
		</tr>

		<td colspan="5" align="left">&nbsp;</td>
		</tr>		

		<tr>
		<th style="text-align:left" width ="5%'"><?php echo _t("No");?></th>
		<th style="text-align:left" width ="20%">Building</th>
		<th style="text-align:left" width ="15%">Tenant Code</th>
		<th style="text-align:left" width ="">Name</th>
		<th style="text-align:left" width ="">Add(1)</th>
		<th style="text-align:left" width ="">Add(2)</th>
		<th style="text-align:left" width ="">Add(3)</th>
		<th style="text-align:left" width ="">Ref No.</th>
		<th style="text-align:left" width ="">Shop No.</th>
		<th style="text-align:left" width ="">Rent Bill Date</th>
		<th style="text-align:left" width ="">Rent Amount</th>
		<th style="text-align:left" width ="">Maint. Bill Date</th>
		<th style="text-align:left" width ="">Maint. Amount</th>
		<th style="text-align:left" width ="">Print Type</th>

		</tr>	

		<?php
			$i_count=1 ;
			foreach ($arr_report as $report): 
				echo '<tr>';
				echo '<td>'.$i_count++.'</td>';
				echo '<td>'.f_html_escape($report['build_eng_name']).'</td>';
				echo '<td>'.f_html_escape($report['tenant_code']).'</td>';
				echo '<td>'.f_html_escape($report['eng_name']).'</td>';
				echo '<td>'.f_html_escape($report['add_1']).'</td>';
				echo '<td>'.f_html_escape($report['add_2']).'</td>';
				echo '<td>'.f_html_escape($report['add_3']).'</td>';
				echo '<td>'.f_html_escape($report['ref_no']).'</td>';
				echo '<td>'.f_html_escape($report['shop_no']).'</td>';
				echo '<td>'.f_html_escape(YMDtoDMY($report['rent_date'])).'</td>';
				echo '<td>'.f_html_escape($report['rent_amount']).'</td>';
				echo '<td>'.f_html_escape(YMDtoDMY($report['maint_date'])).'</td>';
				echo '<td>'.f_html_escape($report['maint_amount']).'</td>';
				echo '<td>'.f_html_escape($report['ptype']).'</td>';
				
				echo '</tr>';
			endforeach; 	
		?>								

		<td colspan="14" align="left">&nbsp;</td>
		</tr>		

		
		<td colspan="14" align="left">*End of Report*</td>
		</tr>		
		
		
		</tbody>
	</table>
						



<?php
require __DIR__.'/../../../../template/report_footer_inc.php';
?>
