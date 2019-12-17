<?php

require_once app_path.'/Model/UserModel.php';

class HomeLogSvController extends ControllerBase
{
    public function HomeLogSv()
    {
        $data = [];
        $this->RenderView('homelogsv.home-log-sv', $data);
    }

    public function ShowInfoSV()
    {
        session_start();
        $infoSv = NULL;

        $ma = $_SESSION['auth_sv']['MaSV'];
        $objDb = new UserModel();
        $infoSv = $objDb->GetInfoSV($ma);

        $this->RenderView('homelogsv.info-sv', $infoSv);
    }

    public function UpdateInfoSv()
    {
        session_start();
        $updSv = NULL;
        $this->RenderView('homelogsv.update-info', $updSv);

        if (isset($_POST['sub']))
        {
            $dem = 1;
            $opass = $_POST['old_p'];
            $npass = $_POST['new_p'];
            $npass2 = $_POST['new_p2'];

            // Ktra ky tu ng dung nhap

            $chk = '/^[A-Za-z0-9]{5,20}$/';

            if (!preg_match($chk, $opass))
            {
                $dem = 0;
                echo "<p style='color: red; background-color: yellow;'>" ."Pass cu khong duoc chua ky tu dac biet" ."</p>";
            }

            if (!preg_match($chk, $npass))
            {
                $dem = 0;
                echo "<p style='color: red; background-color: yellow;'>" ."Pass moi khong duoc chua ky tu dac biet" ."</p>";
            }

            if (!preg_match($chk, $npass2))
            {
                $dem = 0;
                echo "<p style='color: red; background-color: yellow;'>" ."Pass moi 2 khong duoc chua ky tu dac biet" ."</p>";
            }

            if ($npass2 <> $npass)
            {
                $dem = 0;
                echo "<p style='color: red; background-color: yellow;'>" ."Pass moi 2 phai giong pass moi" ."</p>";
            }

            if ($dem === 1)
            {
                $ma = $_SESSION['auth_sv']['MaSV'];
                $objDb = new UserModel();
                $updSv = $objDb->GetUserSv($ma);

                if ($updSv['pass_sv'] == $opass)
                {
                    unset($updSv['pass_sv']);

                    $res = $objDb->UpdatePassSv($ma, $npass2);

                    if ($res == true)
                    {
                        header("Location: " .base_path .'/?ct=log-in&act=log-out');
                    }
                    else echo "<p style='color: red; background-color: yellow;'>" ."Loi ghi CSDL" ."</p>";
                }
                else echo "<p style='color: red; background-color: yellow;'>" ."Nhap sai Pass cu" ."</p>";
            }
        }
    }

    public function BaiTap()
    {

//        echo "<pre>";
//        print_r($dsmsv);

//        echo "<pre>";
//        print_r($data['bt']);

//        echo "{$data['bt'][1]['dap_an_gv']}";

        if (isset($_POST['sub']))
        {
            $objDb = new UserModel();
            $data['bt'] = $objDb->GetBaiTap();
            $dsmsv = $objDb->GetMsvDiem();
            $err = [];
            session_start();
            $chk = 1;
            $ma = $_SESSION['auth_sv']['MaSV'];

            foreach ($dsmsv as $row)
            {
                if ($row['MaSV'] == $ma)
                {
                    $chk = 0;
                    break;
                }
            }

            if ($chk == 1)
            {
                $diem = 0;
                for ($i = 0 ; $i < 3 ; $i++)
                {
                    $da = $_POST[$i+1];
                    $dap_an = $data['bt'][$i]['dap_an_gv'];

//                    echo $dap_an;
                    if ($da == $dap_an)
                    {
                        $diem += 1;
                    }
                }

//                echo $diem;

                $res = $objDb->SaveDiemSv($ma, $diem);

                if ($res == true)
                {
//                    header("Location: " .base_path .'/?ct=home-log-sv&act=home-log-sv');
                    $data['diem'] = $diem;
                    $this->RenderView('homelogsv.bai-tap', $data);
                }
                else
                {
                    $err[] = "Có Lỗi!";
                }
            }
            else
            {
                $err[] = "Điểm sinh viên đã tồn tại!";
            }

            if (!empty($err))
            {
                $data = [];
                $objDb = new UserModel();
                $data['bt'] = $objDb->GetBaiTap();
                $data['loi'] = $err;

                $this->RenderView('homelogsv.bai-tap', $data);
            }
        }
        else
        {
            $data = [];
            $objDb = new UserModel();
            $data['bt'] = $objDb->GetBaiTap();
            $data['loi'] = [];

            $this->RenderView('homelogsv.bai-tap', $data);
        }
    }

}


?>
