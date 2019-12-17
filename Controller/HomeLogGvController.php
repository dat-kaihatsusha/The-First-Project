<?php

require_once app_path.'/Model/UserModel.php';

class HomeLogGvController extends ControllerBase
{
    public function HomeLogGv()
    {
        $data = [];
        $this->RenderView('homeloggv.home-log-gv', $data);
    }

    public function ShowInfoGv()
    {
        session_start();
        $infoGv = NULL;

        $ma = $_SESSION['auth_gv']['user_gv'];
        $objDb = new UserModel();
        $infoGv = $objDb->GetUserGv($ma);
        unset($infoGv['pass_gv']);

        $this->RenderView('homeloggv.info-gv', $infoGv);
    }

    public function EditInfoGv()
    {
        session_start();

        if (isset($_POST['sub']))
        {
            $dem = 1;
            $err = [];
            $usn = $_POST['txt_usn'];
            $hoTen = $_POST['txt_name'];
            $gt = $_POST['gt'];
            $ns = $_POST['ns'];

            $chk_usn = '/^[A-Za-z0-9]{5,20}$/';

            if (!preg_match($chk_usn, $usn))
            {
                $dem = 0;
                $err[] = "Tên tài khoản chỉ được chưa các ký tự: A-Z, a-z, 0-9 và phải dài từ 5-20 ký tự";
            }

            $chk_hoTen = '/^[ A-Za-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD]{2,30}$/';

            if (!preg_match($chk_hoTen,$hoTen))
            {
                $dem = 0;
                $err[] = 'Họ đệm chỉ nhập chuỗi tiếng việt có dấu hoặc không dấu, không được nhập kí tự đặc biệt và nhập từ 2 đến 30 kí tự.';
            }

            if ($dem == 1)
            {
                $ma = $_SESSION['auth_gv']['user_gv'];

                $objDb = new UserModel();
                $res = $objDb->EditInfoGv($ma, $usn, $hoTen, $gt, $ns);

                if ($res == true)
                {
                    $infoGv = NULL;

                    $ma = $_SESSION['auth_gv']['user_gv'];
                    $objDb = new UserModel();
                    $infoGv = $objDb->GetUserGv($ma);
                    unset($infoGv['pass_gv']);

                    $this->RenderView('homeloggv.info-gv', $infoGv);
                }
            }
            else
            {
                $err[] = "Lỗi!!!";
            }

            if (!empty($err))
            {
                $infoGv = NULL;

                $ma = $_SESSION['auth_gv']['user_gv'];
                $objDb = new UserModel();
                $infoGv = $objDb->GetUserGv($ma);
                unset($infoGv['pass_gv']);
                $infoGv['loi'] = $err;

                $this->RenderView('homeloggv.edit-info-gv', $infoGv);
            }
        }
        else
        {
            $infoGv = NULL;

            $ma = $_SESSION['auth_gv']['user_gv'];
            $objDb = new UserModel();
            $infoGv = $objDb->GetUserGv($ma);
            unset($infoGv['pass_gv']);

            $this->RenderView('homeloggv.edit-info-gv', $infoGv);
        }
    }

    public function UpdatePassGv()
    {
        session_start();
        $updGv = NULL;
        $this->RenderView('homeloggv.update-pass-gv', $updGv);

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
                $ma = $_SESSION['auth_gv']['user_gv'];
                $objDb = new UserModel();
                $updSv = $objDb->GetUserGv($ma);

                if ($updSv['pass_gv'] == $opass)
                {
                    unset($updSv['pass_gv']);

                    $res = $objDb->UpdatePassGv($ma, $npass2);

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

    public function AddSv()
    {
        $err = $msg = [];

        if (isset($_POST['sub']))
        {
            $dem = 1;
            $chk = 1;
            $masv = $_POST['txt_masv'];
            $hoTen = $_POST['txt_hoTen'];
            $gt = $_POST['gioiTinh'];
            $bday = $_POST['ngaySinh'];
            $lop = $_POST['slx_lop'];

            // Kiem tra hop le du lieu nguoi dung nhap vao

            $chk_msv = '/^[A-Za-z0-9]{3}$/';

            if (!preg_match($chk_msv, $masv))
            {
                $dem = 0;
                $err[] = "Ma SV phai co 3 ky tu";
            }

            $chk_hoTen = '/^[ A-Za-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD]{2,30}$/';

            if (!preg_match($chk_hoTen,$hoTen))
            {
                $dem = 0;
                $err[] = 'Họ đệm chỉ nhập chuỗi tiếng việt có dấu hoặc không dấu, không được nhập kí tự đặc biệt và nhập từ 2 đến 30 kí tự.';
            }

            if ($gt!= 0 && $gt != 1)
            {
                $dem = 0;
                $err[] = 'Giới tính phải được chọn trên giao diện';
            }

            if ($bday == "")
            {
                $dem = 0;
                $err[] = 'Ngày sinh phải được chọn trên giao diện';
            }

            if ($lop == "")
            {
                $dem = 0;
                $err[] = 'Lớp phải được chọn trên giao diện';
            }

            if ($dem == 1)
            {
                $dtAdd = [];
                $dtMsv = NULL;

                $objDb = new UserModel();
                $dtAdd['list_lop'] = $objDb->GetDsLop();
                $dtMsv = $objDb->GetDsMaSv();

                foreach ($dtMsv as $row)
                {
                    if ($masv == $row)
                    {
                        $chk = 0;
                    }
                }

                if ($chk == 1)
                {
                    $res1 = $objDb->AddInfoSv($masv, $hoTen, $gt, $bday, $lop);
                    $res2 = $objDb->AddUserSv($masv);
                    $res3 = $objDb->AddDiemDanhSv($masv);

                    if ($res1 == true && $res2 == true && $res3 == true)
                    {
//                    header("Location: " .base_path .'/?ct=log-in&act=log-in');
                        $msg[] = "Thêm thành công";
                    }
                    else $err[] = "Lỗi thêm sinh viên";
                }
                else
                {
                    $err[] = "Mã SV đã tồn tại";
                }
            }

            if (!empty($err) || !empty($msg))
            {
                $dtAdd = [];

                $objDb = new UserModel();
                $dtAdd['list_lop'] = $objDb->GetDsLop();
                $dtAdd['loi'] = $err;
                $dtAdd['mess'] = $msg;

                $this->RenderView('homeloggv.add-sv', $dtAdd);
            }

        }
        else
        {
            $dtAdd = [];

            $objDb = new UserModel();
            $dtAdd['list_lop'] = $objDb->GetDsLop();

            $this->RenderView('homeloggv.add-sv', $dtAdd);
        }
    }


    public function ShowDsSv()
    {
        if (isset($_POST['sub']))
        {
            $strWhere = '';
            $arrwhere = [];
            $msv = $ten = $maLop = '';

            if (isset($_POST['txt_masv']) && strlen($_POST['txt_masv']) > 0)
            {
                $msv = trim($_POST['txt_masv']);
                $arrwhere[] = " MaSV LIKE '%{$msv}%' ";
            }

            if (isset($_POST['txt_name']) && strlen($_POST['txt_name']) > 0)
            {
                $ten = trim($_POST['txt_name']);
                $arrwhere[] = " hoTen_sv LIKE '%{$ten}%' ";
            }

            if (isset($_POST['slx_lop']) && strlen($_POST['slx_lop']) > 0)
            {
                $maLop = trim($_POST['slx_lop']);
                $arrwhere[] = " tb_ds_sv.MaLop LIKE '%{$maLop}%' ";
            }

            if (!empty($arrwhere))
            {
                $strWhere = ' WHERE '.implode(' AND ', $arrwhere);
            }
            else $strWhere = '';

            $dtDssv = [];

            $objDb = new UserModel();
            $dtDssv['dssv'] = $objDb->GetAllDsSv($strWhere);
            $dtDssv['list_lop'] = $objDb->GetDsLop();
            $dtDssv['maLop_slt'] = $maLop;
            $dtDssv['msv_slt'] = $msv;
            $dtDssv['ten_slt'] = $ten;

            $this->RenderView('homeloggv.ds-sv', $dtDssv);
        }
        else
        {
            $dtDssv = [];

            $objDb = new UserModel();
            $dtDssv['dssv'] = $objDb->GetAllDsSv('');
            $dtDssv['list_lop'] = $objDb->GetDsLop();

            $this->RenderView('homeloggv.ds-sv', $dtDssv);
        }
    }

    public function EditInfoSv()
    {
        $ma = @$_GET['masv'];
        $err = [];

        if (isset($_POST['sbm']))
        {
            $dem = 1;

            $hoTen = $_POST['txt_ten'];
            $gTinh = $_POST['gt'];
            $nSinh = $_POST['ns'];
            $mlop = $_POST['slx_lop'];

            $chk_hoTen = '/^[ A-Za-zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD]{2,30}$/';

            if (!preg_match($chk_hoTen,$hoTen))
            {
                $dem = 0;
                $err[] = 'Họ đệm chỉ nhập chuỗi tiếng việt có dấu hoặc không dấu, không được nhập kí tự đặc biệt và nhập từ 2 đến 30 kí tự.';
            }

            if ($gTinh!= 0 && $gTinh != 1)
            {
                $dem = 0;
                $err[] =  'Giới tính phải được chọn trên giao diện';
            }

            if ($nSinh == "")
            {
                $dem = 0;
                $err[] = 'Ngày sinh phải được chọn trên giao diện';
            }

            if ($mlop == "")
            {
                $dem = 0;
                $err[] = 'Lớp phải được chọn trên giao diện';
            }

            if ($dem == 1)
            {
                $objDb = new UserModel();
                $res = $objDb->EditInfoSv($ma, $hoTen, $gTinh, $nSinh, $mlop);

                if ($res == true)
                {
                    header("Location: " .base_path .'/?ct=home-log-gv&act=show-ds-sv');
                }
            }

            if (!empty($err))
            {
                $err[] = "Lỗi sửa sinh viên" ;
                $data = [];

                $objDb = new UserModel();
                $data['sv_slt'] = $objDb->GetInfoSV($ma);
                $data['dsmsv'] = $objDb->GetDsMaSv();
                $data['list_lop'] = $objDb->GetDsLop();
                $data['loi'] = $err;
                $this->RenderView('homeloggv.edit-info-sv', $data);
            }
        }
        else
        {
            $data = [];
            $objDb = new UserModel();
            $data['sv_slt'] = $objDb->GetInfoSV($ma);
            $data['dsmsv'] = $objDb->GetDsMaSv();
            $data['list_lop'] = $objDb->GetDsLop();
            $this->RenderView('homeloggv.edit-info-sv', $data);
        }
    }

    public function DelInfoSv()
    {
        $ma = @$_GET['masv'];

        $objDb = new UserModel();
        $res1 = $objDb->DelDiemDanhSv($ma);

        if ($res1 == true)
        {
            $res2 = $objDb->DelUserSv($ma);

            if ($res2 == true)
            {
                $res3 = $objDb->DelInfoSv($ma);

                if ($res3 == true)
                {
                    $dtDssv = [];

                    $objDb = new UserModel();
                    $dtDssv['dssv'] = $objDb->GetAllDsSv('');
                    $dtDssv['list_lop'] = $objDb->GetDsLop();

                    $this->RenderView('homeloggv.ds-sv', $dtDssv);
                }
            }
        }
    }

    public function DiemDanh()
    {
        if (isset($_POST['search']))
        {
            $arrWhere = [];
            $strWhere = '';

            $msv = $hten = $maLop = '';

            if (isset($_POST['search_msv']) && strlen($_POST['search_msv']) > 0)
            {
                $msv = trim($_POST['search_msv']);
                $arrWhere[] = " tb_ds_diemdanh.MaSV LIKE '%{$msv}%' ";
            }

            if (isset($_POST['search_name']) && strlen($_POST['search_name']) > 0)
            {
                $hten = trim($_POST['search_name']);
                $arrWhere[] = " tb_ds_sv.hoTen_sv LIKE '%{$hten}%' ";
            }

            if (isset($_POST['slx_lop']) && strlen($_POST['slx_lop']) > 0)
            {
                $maLop = trim($_POST['slx_lop']);
                $arrWhere[] = " tb_ds_sv.maLop LIKE '%{$maLop}%' ";
            }

            if (!empty($arrWhere))
            {
                $strWhere = ' WHERE '.implode(' AND ', $arrWhere);
            }
            else $strWhere = '';

            $data = [];
            $objDb = new UserModel();
            $data['dsdd'] = $objDb->GetDsDiemDanh($strWhere);
            $data['list_lop'] = $objDb->GetDsLop();
            $data['maLop_slt'] = $maLop;

            $this->RenderView('homeloggv.diem-danh', $data);
        }

        else if (isset($_POST['sub']))
        {
            $chk = NULL;
            $dt = [];
            $objDb = new UserModel();

            unset($_POST['sub']);

            if (empty($_POST))
            {
                $data = [];
                $strwhere = "";
                $objDb = new UserModel();
                $data['dsdd'] = $objDb->GetDsDiemDanh($strwhere);
                $data['list_lop'] = $objDb->GetDsLop();

                $this->RenderView('homeloggv.diem-danh', $data);
            }

            $dem = 0;
            foreach ($_POST as $key => $row)
            {
                $chk = 1;
                $dt[$dem] = $objDb->GetDiemDanhSv($key); // $key là mã sv
                $col = NULL;
                $val = NULL;

                if ($row == 0)
                {
                    $dt[$dem]['sb_dung_gio'] += 1;
                    $col = 'sb_dung_gio';
                    $val = $dt[$dem]['sb_dung_gio'];
                }
                else if ($row == 1)
                {
                    $dt[$dem]['sb_di_muon'] += 1;
                    $col = 'sb_di_muon';
                    $val = $dt[$dem]['sb_di_muon'];
                }
                else if ($row == 2)
                {
                    $dt[$dem]['sb_vang_cp'] += 1;
                    $col = 'sb_vang_cp';
                    $val = $dt[$dem]['sb_vang_cp'];
                }
                else if ($row == 3)
                {
                    $dt[$dem]['sb_vang_kp'] += 1;
                    $col = 'sb_vang_kp';
                    $val = $dt[$dem]['sb_vang_kp'];
                }
                $dem++;

                $res = $objDb->SaveDiemDanh($key, $col, $val);

                if ($res == false)
                {
                    $chk = 0;
                    break;
                }
            }

            if ($chk == 1)
            {
                header("Location: " .base_path .'/?ct=home-log-gv&act=home-log-gv');
            }
        }
        else
        {
            $data = [];
            $strwhere = "";
            $objDb = new UserModel();
            $data['dsdd'] = $objDb->GetDsDiemDanh($strwhere);
            $data['list_lop'] = $objDb->GetDsLop();

            $this->RenderView('homeloggv.diem-danh', $data);
        }

    }

    public function DelBaiTap()
    {
        $objDb = new UserModel();
        $res = $objDb->DelCauHoi();

        if ($res == 1)
        {
            $link = base_path .'/?ct=home-log-gv&act=giao-bai&stt=1';
            header("Location: " .$link);
        }
    }

    public function GiaoBai()
    {
        $err = [];
        $data = NULL;
        $objDb = new UserModel();
        $data = $objDb->GetBaiTap();

        $id = NULL;

        if (isset($_POST['sub']))
        {
            $id = @$_GET['stt'];
            $chk = 1;

            foreach ($data as $row)
            {
                if ($row['stt'] == $id)
                {
                    $chk = 0;
                    $err[] = "Câu hỏi đã tồn tại. Cần xóa bài tập cũ!";
                    break;
                }
            }

            if ($chk == 1)
            {
                $temp = $id;

                $id++;

                $data = [];
                $data['stt'] = $temp;

                $stt = $temp;
                $ndung = $_POST['ndung'];
                $daa = $_POST['daa'];
                $dab = $_POST['dab'];
                $dac = $_POST['dac'];
                $dad = $_POST['dad'];
                $da = $_POST['slx_da'];

                $objDb = new UserModel();
                $res = $objDb->SaveCauHoi($stt, $ndung, $daa, $dab, $dac, $dad, $da);

                if ($res == true)
                {
                    $link = base_path .'/?ct=home-log-gv&act=giao-bai&stt=' .$id;
                    header("Location: " .$link);
                }

                if ($temp == 3)
                {
                    header("Location: " .base_path .'/?ct=home-log-gv&act=bai-tap');
                }
            }
            else
            {
                $id = @$_GET['stt'];
                $data = [];
                $data['stt'] = $id;
                $data['loi'] = $err;
                $this->RenderView('homeloggv.giao-bai', $data);
            }
        }
        else
        {
            $id = @$_GET['stt'];
            $data = [];
            $data['stt'] = $id;
            $this->RenderView('homeloggv.giao-bai', $data);
        }
    }

    public function BaiTap()
    {
        $data = NULL;
        $objDb = new UserModel();
        $data = $objDb->GetBaiTap();

        $this->RenderView('homeloggv.bai-tap', $data);
    }

    public function EditBaiTap()
    {
        @$stt = $_GET['stt'];

        if (isset($_POST['sub']))
        {
            $ma = $stt;

            $nd = $_POST['ndung'];
            $daa = $_POST['daa'];
            $dab = $_POST['dab'];
            $dac = $_POST['dac'];
            $dad = $_POST['dad'];
            $da = $_POST['slx_da'];

            $objDb = new UserModel();
            $res = $objDb->EditCauHoi($ma, $nd, $daa, $dab, $dac, $dad,$da);

            if ($res == true)
            {
                header("Location: " .base_path .'/?ct=home-log-gv&act=bai-tap');
            }
        }
        else
        {
            $data = NULL;

            $objDb = new UserModel();
            $data= $objDb->GetCauHoi($stt);

            $this->RenderView('homeloggv.edit-bai-tap', $data);
        }
    }
}


?>
