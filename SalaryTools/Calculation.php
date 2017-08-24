<?php
/**
 *
 * @author SkyeInfo
 * @lastModifyTime 2017/8/24
 * @lastModify SkyeInfo
 */
class Calculation{

    public function getRealMoney()
    {
        $userInfo = $_POST['totalMoney'];
        echo json_encode($userInfo);
    }
}
