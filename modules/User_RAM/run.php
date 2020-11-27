<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 30.01.20
 * Time: 11:11
 */
$vakansy_id = '28a01647-2fa4-c0de-709d-5da47cf58f60';
$vakansy_id = '59800791-d44b-2fcb-8e60-5de7b0376a3c';
print_array($_REQUEST);
if($_REQUEST['rec']=='1') {
    //$vakansy_id = '28a01647-2fa4-c0de-709d-5da47cf58f60';
    print_array($vakansy_id);
    $seedURAM = new User_RAM();
    $seedURAM->name = create_guid();
    $seedURAM->parent_id = $vakansy_id;
    $seedURAM->parent_type = 'HRPAC_VACANCY';
    $seedURAM->user_id_c = '1';
    $seedURAM->save();
    sleep(2);
    print_array($seedURAM->id);
    $seedVacancy = BeanFactory::getBean('HRPAC_VACANCY', $vakansy_id);
    $seedVacancy->load_relationship('user_ram');
//$seedVacancy->user_ram->add($seedURAM->id);
    $relate = $seedVacancy->user_ram->get();
    print_array($relate);
} elseif ($_REQUEST['get']=='1') {
    $seedVacancy = BeanFactory::getBean('HRPAC_VACANCY', $vakansy_id);
    $seedVacancy->load_relationship($_REQUEST['rel_nam']);
//$seedVacancy->user_ram->add($seedURAM->id);
    $relate = $seedVacancy->{$_REQUEST['rel_nam']}->get();
    print_array($relate);
} elseif ($_REQUEST['rec']=='2') {
    $seedURAM = new User_RAM();
    $seedURAM->name = create_guid();
    $seedURAM->parent_id = $vakansy_id;
    $seedURAM->parent_type = 'HRPAC_VACANCY';
    $seedURAM->user_id_c = '1';
    $seedURAM->save();
    print_array($seedURAM->id);
} elseif ($_REQUEST['rec']=='3') {
    $seedURAM = new User_RAM();
    $seedURAM->name = create_guid();
    $seedURAM->user_id_c = '1';
    $seedURAM->save();
    print_array($seedURAM->id);
    $seedVacancy = BeanFactory::getBean('HRPAC_VACANCY', $vakansy_id);
    $seedVacancy->load_relationship('user_ram');
    $seedVacancy->user_ram->add($seedURAM->id);
}