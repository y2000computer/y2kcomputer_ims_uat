<?php
require __DIR__."/../../../../classes/phpspreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$inputFileType = 'Xlsx';
$inputFileName = 'prop_report_rent_payment_template_v01.xlsx';
$sheetname = ['sheet1'];

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__."/".$inputFileName);
$sheet = $spreadsheet->getActiveSheet();


$sheet->setCellValue('C2', $general["eng_name"]);
$sheet->setCellValue('C3', date('d/m/Y  H:i:s'));


$styleArray = array(
	'borders' => array(
		'outline' => array(
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			'color' => array('argb' => '000000'),
		),
	),
);

$report_ttl = 0;
$i_count=1;
$excel_row = 5;
foreach ($arr_report as $report): 
	
	$excel_row++;

	$report_ttl += $report['amount'];
	$report_ttl = round($report_ttl,2);
	
	$sheet->setCellValue(('A'.$excel_row), $i_count++);
	$sheet->setCellValue(('B'.$excel_row), ($report['build_eng_name']));
	$sheet->setCellValue(('C'.$excel_row), (YMDtoDMY($report['payment_date'])));	
	$sheet->setCellValue(('D'.$excel_row), ($report['payment_code']));	
	$sheet->setCellValue(('E'.$excel_row), ($report['tenant_code']));	
	$sheet->setCellValue(('F'.$excel_row), ($report['tenant_eng_name']));	
	$sheet->setCellValue(('G'.$excel_row), ($report['ref_no']));	
	$sheet->setCellValue(('H'.$excel_row), ($report['shop_no']));	
	$sheet->setCellValue(('I'.$excel_row), (YMDtoDMY($report['inv_date'])));	
	$sheet->setCellValue(('J'.$excel_row), ($report['inv_code']));	
	$sheet->setCellValue(('K'.$excel_row), (YMDtoDMY($report['period_date_from'])));	
	$sheet->setCellValue(('L'.$excel_row), (YMDtoDMY($report['period_date_to'])));	
	$sheet->setCellValue(('M'.$excel_row), ($report['inv_amount']));	
	$sheet->setCellValue(('N'.$excel_row), ($report['amount']));	

	
	$sheet ->getStyle(('A'.$excel_row.':A'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('B'.$excel_row.':B'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('C'.$excel_row.':C'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('D'.$excel_row.':D'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('E'.$excel_row.':E'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('F'.$excel_row.':F'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('G'.$excel_row.':G'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('H'.$excel_row.':H'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('I'.$excel_row.':I'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('J'.$excel_row.':J'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('K'.$excel_row.':K'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('L'.$excel_row.':L'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('M'.$excel_row.':M'.$excel_row))->applyFromArray($styleArray);
	$sheet ->getStyle(('N'.$excel_row.':N'.$excel_row))->applyFromArray($styleArray);

	
endforeach; 	

//Print report balance:
$excel_row++;
$sheet->setCellValue(('M'.$excel_row), ('Report Total:'));	
$sheet->setCellValue(('N'.$excel_row), ($report_ttl));	
$sheet ->getStyle(('M'.$excel_row.':M'.$excel_row))->applyFromArray($styleArray);
$sheet ->getStyle(('N'.$excel_row.':N'.$excel_row))->applyFromArray($styleArray);



$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$dt = new DateTime();
$path = __DIR__.DIR_EXCEL_OUTPUT;
$file_name = 'prop_report_rent_payment_'.$dt->format('Y-m-d_H_i_s').'.xlsx';
$writer->save($path.$file_name);

// clean the output buffer
ob_clean();


header ("Content-Type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"".$file_name."\"");
readfile($path.$file_name);

exit();




?>