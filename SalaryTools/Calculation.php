<?php
/**
 *
 * @author SkyeInfo
 * @lastModifyTime 2017/8/24
 * @lastModify SkyeInfo
 */
$totalMoney       = intval($_POST['totalMoney']);
$housingFundMoney = intval($_POST['housingFundMoney']);
$housingFundRate  = intval($_POST['housingFundRate'])/100;
$insuranceMoney   = intval($_POST['insuranceMoney']);
$leaveDay         = isset($_POST['leaveDay']) ? intval($_POST['leaveDay']) : 0;

//事假扣款
$leaveMoney = $totalMoney * ($leaveDay / 21.75);

$housingFundMoney = ($totalMoney - $leaveMoney) < 10000 ? ($totalMoney - $leaveMoney) : 10000;

//住房公积金
$housingFundSelf = $housingFundMoney * $housingFundRate;
$housingFundCompany = $housingFundSelf;

//五险
//养老
$pensionSelf = $insuranceMoney * 0.08;
$pensionCompany = $insuranceMoney * 0.2;

//医疗
$medicalSelf = $insuranceMoney * 0.02;
$medicalCompany = $insuranceMoney * 0.1;

//失业
$lostWorkSelf = $insuranceMoney * 0.002;
$lostWorkCompany = $insuranceMoney * 0.01;

//工伤
$injuryCompany = $insuranceMoney * 0.005;

//生育
$birthCompany = $insuranceMoney * 0.008;

$noTaxes = $totalMoney - $housingFundSelf - $pensionSelf - $medicalSelf - $lostWorkSelf - $leaveMoney - 3500;

if ($noTaxes <= 1500){
    $taxes = $noTaxes * 0.03 - 0;
}elseif ($noTaxes > 1500 && $noTaxes <=4500){
    $taxes = $noTaxes * 0.1 - 105;
}elseif ($noTaxes > 4500 && $noTaxes <= 9000){
    $taxes = $noTaxes * 0.2 - 555;
}elseif ($noTaxes > 9000 && $noTaxes <= 35000){
    $taxes = $noTaxes * 0.25 - 1005;
}elseif ($noTaxes > 35000 && $noTaxes <= 55000){
    $taxes = $noTaxes * 0.3 - 2755;
}elseif ($noTaxes > 55000 && $noTaxes <= 80000){
    $taxes = $noTaxes * 0.35 - 5505;
}elseif ($noTaxes > 80000){
    $taxes = $noTaxes * 0.45 - 13505;
}

$realMoneySelf = $totalMoney - $housingFundSelf - $pensionSelf - $medicalSelf -$lostWorkSelf - $taxes - $leaveMoney;

$SelfMoney = array(
    '到手工资' => $realMoneySelf,
    '自己交的公积金' => $housingFundSelf,
    '到手工资+公积金' => $realMoneySelf + $housingFundSelf + $housingFundCompany,
    '养老保险' => $pensionSelf + $pensionCompany,
    '医疗保险' => $medicalSelf + $medicalCompany,
    '失业保险' => $lostWorkSelf + $lostWorkCompany,
    '工伤保险' => $injuryCompany,
    '生育保险' => $birthCompany,
    '缴税'    => $taxes,
    '事假扣钱' => $leaveMoney
);
$CompanyMoney = array(
    '工资总数' => $totalMoney,
    '公司缴公积金' => $housingFundCompany,
    '养老保险' => $pensionCompany,
    '医疗保险' => $medicalCompany,
    '失业保险' => $lostWorkCompany,
    '工伤保险' => $injuryCompany,
    '生育保险' => $birthCompany,
    '总支出'   => $totalMoney + $housingFundCompany + $pensionCompany + $medicalCompany + $lostWorkCompany + $injuryCompany + $birthCompany
);

$data = array(
    'status' => 1,
    'SelfMoney' => $SelfMoney,
    'CompanyMoney' => $CompanyMoney
);

echo json_encode($data);
