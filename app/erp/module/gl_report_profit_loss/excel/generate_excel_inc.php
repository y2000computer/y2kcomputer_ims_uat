<?php
require __DIR__."/../../../../classes/phpspreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$inputFileType = 'Xlsx';
$inputFileName = 'gl_report_profit_loss_template_v01.xlsx';
$sheetname = ['sheet1'];

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__."/".$inputFileName);
$sheet = $spreadsheet->getActiveSheet();


$sheet->setCellValue('C2', $_SESSION["target_comp_name"]);
$sheet->setCellValue('C3', date('d/m/Y  H:i:s'));
$date_range_show = $json_search_items['criteria']['journal_date_from'] . '  to ' ;
$date_range_show .=  $json_search_items['criteria']['journal_date_to'] ;
$sheet->setCellValue('C4', $date_range_show);


$styleArray = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			'color' => array('argb' => '000000'),
		),
	),
);

//Report footer
$report_brought_forward_grand_ttl = 0;
$report_previous_balance_grand_ttl = 0;			
$report_current_period_balance_grand_ttl = 0;
$report_ending_balance_grand_ttl = 0;



$i_count=1;
$excel_row = 6;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
$type_code='130';  //Income
$arr_charts_income=$dmReport->charts_type($type_code, $json_searchphrase);

$excel_row++;

$sheet->setCellValue(('A'.$excel_row), ('Income'));
$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);



$report_brought_forward_ttl = 0;
$report_previous_balance_ttl = 0;			
$report_current_period_balance_ttl = 0;
$report_ending_balance_ttl = 0;
foreach ($arr_charts_income as $report): 
	
	$excel_row++;

	$report_brought_forward = $report['brought_forward'];
	/*
	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/

	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']);

	/*
	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/
	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']);


	$report_ending_balance = $report_brought_forward + $report_previous_balance + $report_current_period_balance;

	$report_brought_forward = round($report_brought_forward,2);
	$report_previous_balance = round($report_previous_balance,2);
	$report_current_period_balance = round($report_current_period_balance,2);
	$report_ending_balance = round($report_ending_balance,2);
	
	$report_brought_forward_ttl += $report_brought_forward;
	$report_previous_balance_ttl += $report_previous_balance;
	$report_current_period_balance_ttl += $report_current_period_balance;
	$report_ending_balance_ttl += $report_ending_balance;

	$report_brought_forward_ttl = round($report_brought_forward_ttl,2);
	$report_previous_balance_ttl = round($report_previous_balance_ttl,2);
	$report_current_period_balance_ttl = round($report_current_period_balance_ttl,2);
	
	
	//$sheet->setCellValue(('A'.$excel_row), $i_count++);
	$sheet->setCellValue(('B'.$excel_row), ($report['chart_code']));
	$sheet->setCellValue(('C'.$excel_row), ($report['type_name']));	
	$sheet->setCellValue(('D'.$excel_row), ($report['chart_name']));	
	$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward));	
	$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance));	
	$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance));	
	$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance));	
	
	
	$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	

	
endforeach; 	

//Print report balance:

$excel_row++;
$sheet->setCellValue(('D'.$excel_row), ('Total:'));	
$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward_ttl));	
$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance_ttl));	
$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance_ttl));	
$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance_ttl));	
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	
//sum of grand total
$report_brought_forward_grand_ttl += $report_brought_forward_ttl;
$report_previous_balance_grand_ttl += $report_previous_balance_ttl;			
$report_current_period_balance_grand_ttl += $report_current_period_balance_ttl;
$report_ending_balance_grand_ttl += $report_ending_balance_ttl;

$report_brought_forward_grand_ttl = round($report_brought_forward_grand_ttl,2);
$report_previous_balance_grand_ttl = round($report_previous_balance_grand_ttl,2);
$report_current_period_balance_grand_ttl = round($report_current_period_balance_grand_ttl,2);
$report_ending_balance_grand_ttl = round($report_ending_balance_grand_ttl,2);
	

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
$type_code='140';  //Expense
$arr_charts_expense=$dmReport->charts_type($type_code, $json_searchphrase);

$excel_row++;

$sheet->setCellValue(('A'.$excel_row), ('Expense'));
$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);



$report_brought_forward_ttl = 0;
$report_previous_balance_ttl = 0;			
$report_current_period_balance_ttl = 0;
$report_ending_balance_ttl = 0;
foreach ($arr_charts_expense as $report): 
	
	$excel_row++;

	$report_brought_forward = $report['brought_forward'];

	/*
	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/
	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']);

	/*
	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/

	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']);
	

	$report_ending_balance = $report_brought_forward + $report_previous_balance + $report_current_period_balance;

	$report_brought_forward = round($report_brought_forward,2);
	$report_previous_balance = round($report_previous_balance,2);
	$report_current_period_balance = round($report_current_period_balance,2);
	$report_ending_balance = round($report_ending_balance,2);
	
	$report_brought_forward_ttl += $report_brought_forward;
	$report_previous_balance_ttl += $report_previous_balance;
	$report_current_period_balance_ttl += $report_current_period_balance;
	$report_ending_balance_ttl += $report_ending_balance;

	$report_brought_forward_ttl = round($report_brought_forward_ttl,2);
	$report_previous_balance_ttl = round($report_previous_balance_ttl,2);
	$report_current_period_balance_ttl = round($report_current_period_balance_ttl,2);
	
	
	//$sheet->setCellValue(('A'.$excel_row), $i_count++);
	$sheet->setCellValue(('B'.$excel_row), ($report['chart_code']));
	$sheet->setCellValue(('C'.$excel_row), ($report['type_name']));	
	$sheet->setCellValue(('D'.$excel_row), ($report['chart_name']));	
	$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward));	
	$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance));	
	$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance));	
	$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance));	
	
	
	$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	

	
endforeach; 	

//Print report balance:

$excel_row++;
$sheet->setCellValue(('D'.$excel_row), ('Total:'));	
$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward_ttl));	
$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance_ttl));	
$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance_ttl));	
$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance_ttl));	
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	
//sum of grand total
$report_brought_forward_grand_ttl += $report_brought_forward_ttl;
$report_previous_balance_grand_ttl += $report_previous_balance_ttl;			
$report_current_period_balance_grand_ttl += $report_current_period_balance_ttl;
$report_ending_balance_grand_ttl += $report_ending_balance_ttl;

$report_brought_forward_grand_ttl = round($report_brought_forward_grand_ttl,2);
$report_previous_balance_grand_ttl = round($report_previous_balance_grand_ttl,2);
$report_current_period_balance_grand_ttl = round($report_current_period_balance_grand_ttl,2);
$report_ending_balance_grand_ttl = round($report_ending_balance_grand_ttl,2);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
$type_code='150';  //Cost of Sales
$arr_charts_cost_of_sales=$dmReport->charts_type($type_code, $json_searchphrase);

$excel_row++;

$sheet->setCellValue(('A'.$excel_row), ('Cost of Sales'));
$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);



$report_brought_forward_ttl = 0;
$report_previous_balance_ttl = 0;			
$report_current_period_balance_ttl = 0;
$report_ending_balance_ttl = 0;
foreach ($arr_charts_cost_of_sales as $report): 
	
	$excel_row++;

	$report_brought_forward = $report['brought_forward'];

	/*
	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/
	$report_previous_balance = $dmReport->get_previous_balance($report['chart_id'],$json_search_items['criteria']);
	
	/*
	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']['journal_date_from'],$json_search_items['criteria']['journal_date_to']);
	*/
	$report_current_period_balance = $dmReport->get_current_period_balance($report['chart_id'],$json_search_items['criteria']);

	$report_ending_balance = $report_brought_forward + $report_previous_balance + $report_current_period_balance;

	$report_brought_forward = round($report_brought_forward,2);
	$report_previous_balance = round($report_previous_balance,2);
	$report_current_period_balance = round($report_current_period_balance,2);
	$report_ending_balance = round($report_ending_balance,2);
	
	$report_brought_forward_ttl += $report_brought_forward;
	$report_previous_balance_ttl += $report_previous_balance;
	$report_current_period_balance_ttl += $report_current_period_balance;
	$report_ending_balance_ttl += $report_ending_balance;

	$report_brought_forward_ttl = round($report_brought_forward_ttl,2);
	$report_previous_balance_ttl = round($report_previous_balance_ttl,2);
	$report_current_period_balance_ttl = round($report_current_period_balance_ttl,2);
	
	
	//$sheet->setCellValue(('A'.$excel_row), $i_count++);
	$sheet->setCellValue(('B'.$excel_row), ($report['chart_code']));
	$sheet->setCellValue(('C'.$excel_row), ($report['type_name']));	
	$sheet->setCellValue(('D'.$excel_row), ($report['chart_name']));	
	$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward));	
	$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance));	
	$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance));	
	$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance));	
	
	
	$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	

	
endforeach; 	

//Print report balance:

$excel_row++;
$sheet->setCellValue(('D'.$excel_row), ('Total:'));	
$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward_ttl));	
$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance_ttl));	
$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance_ttl));	
$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance_ttl));	
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	
	
//sum of grand total
$report_brought_forward_grand_ttl += $report_brought_forward_ttl;
$report_previous_balance_grand_ttl += $report_previous_balance_ttl;			
$report_current_period_balance_grand_ttl += $report_current_period_balance_ttl;
$report_ending_balance_grand_ttl += $report_ending_balance_ttl;

$report_brought_forward_grand_ttl = round($report_brought_forward_grand_ttl,2);
$report_previous_balance_grand_ttl = round($report_previous_balance_grand_ttl,2);
$report_current_period_balance_grand_ttl = round($report_current_period_balance_grand_ttl,2);
$report_ending_balance_grand_ttl = round($report_ending_balance_grand_ttl,2);
	

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Print grand total
	
$pl_wording = 'Loss';
if($report_ending_balance_grand_ttl <=0) {
$pl_wording = 'Profit';
} else {
	$pl_wording = 'Loss';
}
		
$excel_row++;		
$excel_row++;
$sheet->setCellValue(('D'.$excel_row), ($pl_wording.' Total:'));	
$sheet->setCellValue(('E'.$excel_row), ($report_brought_forward_grand_ttl));	
$sheet->setCellValue(('F'.$excel_row), ($report_previous_balance_grand_ttl));	
$sheet->setCellValue(('G'.$excel_row), ($report_current_period_balance_grand_ttl));	
$sheet->setCellValue(('H'.$excel_row), ($report_ending_balance_grand_ttl));	
$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
		
		



$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$dt = new DateTime();
$path = __DIR__.DIR_EXCEL_OUTPUT;
$file_name = 'gl_report_general_ledger_'.$dt->format('Y-m-d_H_i_s').'.xlsx';
$writer->save($path.$file_name);

// clean the output buffer
ob_clean();


header ("Content-Type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"".$file_name."\"");
readfile($path.$file_name);

exit();




?>