<?php
require __DIR__."/../../../../classes/phpspreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$inputFileType = 'Xlsx';
$inputFileName = 'gl_report_journal_entry_template_v01.xlsx';
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

$dr_report_ttl = 0;
$cr_report_ttl = 0;
$report_ttl = 0;
$i_count=1;
$excel_row = 6;
foreach ($arr_report as $report): 
	
	$excel_row++;

	$report_ttl += $report['amount'];
	$report_ttl = round($report_ttl,2);
	if($report['amount'] > 0) {
		$dr_report_ttl += $report['amount'];
		$dr_report_ttl = round($dr_report_ttl,2);
	} else {
		$cr_report_ttl += ($report['amount'] );
		$cr_report_ttl = round($cr_report_ttl,2);
	}	
	
	$sheet->setCellValue(('A'.$excel_row), $i_count++);
	$sheet->setCellValue(('B'.$excel_row), (toDMY($report['journal_date'])));	
	$sheet->setCellValue(('C'.$excel_row), ($report['journal_code']));	
	$sheet->setCellValue(('D'.$excel_row), ($report['chart_code']));
	$sheet->setCellValue(('E'.$excel_row), ($report['type_name']));	
	$sheet->setCellValue(('F'.$excel_row), ($report['chart_name']));	
	$sheet->setCellValue(('G'.$excel_row), ($report['description']));	
	$sheet->setCellValue(('H'.$excel_row), ($report['amount']));	
	
	
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
$sheet->setCellValue(('G'.$excel_row), ('Dr. Total:'));	
$sheet->setCellValue(('H'.$excel_row), ($dr_report_ttl));	
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);

$excel_row++;
$sheet->setCellValue(('G'.$excel_row), ('Cr. Total:'));	
$sheet->setCellValue(('H'.$excel_row), ($cr_report_ttl));	
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);

$excel_row++;
$sheet->setCellValue(('G'.$excel_row), ('Balance Total:'));	
$sheet->setCellValue(('H'.$excel_row), ($report_ttl));	
$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);



$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$dt = new DateTime();
$path = __DIR__.DIR_EXCEL_OUTPUT;
$file_name = 'gl_report_journal_entry_'.$dt->format('Y-m-d_H_i_s').'.xlsx';
$writer->save($path.$file_name);

// clean the output buffer
ob_clean();

header ("Content-Type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"".$file_name."\"");
readfile($path.$file_name);

exit();

?>