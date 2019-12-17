<?php

class UserModel extends DB
{
    private $tb_user_gv = 'tb_user_gv';
    private $tb_user_sv = 'tb_user_sv';
    private $tb_ds_sv = 'tb_ds_sv';
    private $tb_ds_lop = 'tb_ds_lop';
    private $tb_ds_diemdanh = 'tb_ds_diemdanh';
    private $tb_ds_cauhoi = 'tb_ds_cauhoi';
    private $tb_ds_cautraloi = 'tb_ds_cautraloi';
    private $tb_ketqua = 'tb_ketqua';

    public function GetUserGv($ma)
    {
        $sql = " SELECT * FROM $this->tb_user_gv WHERE user_gv = '$ma' ";

        $res = $this->Query($sql);

        $data = NULL;

        if ($res->num_rows == 1) // Ton tai du lieu ng dung dang nhap
        {
            $data = $res->fetch_assoc();
        }
        else $data = false;

        return $data;
    }

    public function EditInfoGv($ma, $usn, $hoTen, $gt, $ns)
    {
        $sql = " UPDATE $this->tb_user_gv 
                 SET user_gv = '$usn', hoTen_gv = '$hoTen', gioiTinh_gv = $gt, ngaySinh_gv = '$ns' 
                 WHERE user_gv = '$ma' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function GetUserSv($ma)
    {
        $sql = " SELECT * FROM $this->tb_user_sv WHERE MaSV = '$ma' ";

        $res = $this->Query($sql);

        $data = NULL;

        if ($res->num_rows == 1) // Ton tai du lieu ng dung dang nhap
        {
            $data = $res->fetch_assoc();
        }
        else $data = false;

        return $data;
    }

    public function GetInfoSv($masv)
    {
        $sql = " SELECT MaSV, hoTen_sv, gioiTinh_sv, ngaySinh_sv, $this->tb_ds_lop.MaLop, tenLop
                FROM $this->tb_ds_sv 
                LEFT JOIN $this->tb_ds_lop
                ON $this->tb_ds_lop.MaLop = $this->tb_ds_sv.MaLop
                WHERE MaSV = '$masv' ";

        $res = $this->Query($sql);

        $data = NULL;

        if ($res->num_rows == 1) // Ton tai du lieu ng dung dang nhap
        {
            $data = $res->fetch_assoc();
        }
        else $data = false;

        return $data;
    }

    public function UpdatePassSv($masv, $value)
    {
        $sql = " UPDATE $this->tb_user_sv
                SET pass_sv = '$value' 
                WHERE MaSV = '$masv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function UpdatePassGv($user, $value)
    {
        $sql = " UPDATE $this->tb_user_gv
                SET pass_gv = '$value' 
                WHERE user_gv = '$user' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function GetDsLop()
    {
        $sql = " SELECT * FROM $this->tb_ds_lop ";

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }

    public function AddInfoSv($masv, $hoTen, $gt, $ngaySinh, $maLop)
    {
        $sql = " INSERT INTO $this->tb_ds_sv
                  VALUES ('$masv', '$hoTen', $gt, '$ngaySinh', '$maLop') ";

        $res = $this->Query($sql);
        return $res;
    }

    public function AddUserSv($masv)
    {
        $sql = " INSERT INTO $this->tb_user_sv
                  VALUES ('$masv', '12345') ";

        $res = $this->Query($sql);
        return $res;
    }

    public function AddDiemDanhSv($masv)
    {
        $sql = " INSERT INTO $this->tb_ds_diemdanh (MaSV) 
                VALUES ('$masv') ";

        $res = $this->Query($sql);
        return $res;

    }

    public function GetAllDsSv($str)
    {
        $sql = " SELECT MaSV, hoTen_sv, gioiTinh_sv, ngaySinh_sv, tenLop
                FROM $this->tb_ds_sv 
                LEFT JOIN $this->tb_ds_lop
                ON $this->tb_ds_lop.MaLop = $this->tb_ds_sv.MaLop " .$str;

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }

    public function GetDsMaSv()
    {
        $sql = " SELECT MaSV FROM $this->tb_ds_sv ";

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }

    public function EditInfoSv($msv, $ht, $gt, $ns, $ml)
    {
        $sql = " UPDATE $this->tb_ds_sv 
                 SET hoTen_sv = '$ht', gioiTinh_sv = $gt, ngaySinh_sv = '$ns', MaLop = '$ml' 
                 WHERE MaSV = '$msv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function DelInfoSv($msv)
    {
        $sql = " DELETE FROM $this->tb_ds_sv WHERE MaSV = '$msv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function DelDiemDanhSv($msv)
    {
        $sql = " DELETE FROM $this->tb_ds_diemdanh WHERE MaSV = '$msv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function DelUserSv($msv)
    {
        $sql = " DELETE FROM $this->tb_user_sv WHERE MaSV = '$msv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function GetDsDiemDanh($str)
    {
        $sql = " SELECT $this->tb_ds_diemdanh.*, hoTen_sv 
                FROM $this->tb_ds_diemdanh 
                LEFT JOIN $this->tb_ds_sv
                ON $this->tb_ds_diemdanh.MaSV = $this->tb_ds_sv.MaSV " .$str ;

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }

    public function GetDiemDanhSv($masv)
    {
        $sql = " SELECT *
                FROM $this->tb_ds_diemdanh 
                WHERE MaSV = '$masv' ";

        $res = $this->Query($sql);

        $data = NULL;

        if ($res->num_rows == 1) // Ton tai du lieu ng dung dang nhap
        {
            $data = $res->fetch_assoc();
        }
        else $data = false;

        return $data;
    }

    public function SaveDiemDanh($masv, $col, $val)
    {
        $sql = " UPDATE $this->tb_ds_diemdanh
                 SET $col = $val
                 WHERE MaSV = '$masv' ";

        $res = $this->Query($sql);
        return $res;
    }

    public function DelCauHoi()
    {
        $sql = " DELETE FROM $this->tb_ds_cauhoi ";

        $res = $this->Query($sql);
        return $res;
    }

    public function SaveCauHoi($stt, $nd, $daa, $dab, $dac, $dad, $da)
    {
        $sql = " INSERT INTO $this->tb_ds_cauhoi 
                 VALUES ($stt, '$nd', '$daa', '$dab', '$dac', '$dad', '$da') ";

        $res = $this->Query($sql);

        return $res;
    }

    public function GetBaiTap()
    {
        $sql = " SELECT * FROM $this->tb_ds_cauhoi ";

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }

    public function GetCauHoi($ma)
    {
        $sql = " SELECT * FROM $this->tb_ds_cauhoi 
                WHERE stt = $ma ";

        $res = $this->Query($sql);

        $data = NULL;

        if ($res->num_rows == 1) // Ton tai du lieu ng dung dang nhap
        {
            $data = $res->fetch_assoc();
        }
        else $data = false;

        return $data;
    }

    public function EditCauHoi($ma, $nd, $daa, $dab, $dac, $dad, $da)
    {
        $sql = " UPDATE $this->tb_ds_cauhoi 
                 SET noiDung = '$nd', Da_A = '$daa', Da_B = '$dab', Da_C = '$dac', Da_D = '$dad', dap_an_gv = '$da' 
                 WHERE stt = $ma ";

        $res = $this->Query($sql);

        return $res;
    }

    public function SaveDiemSv($msv, $diem)
    {
        $sql = " INSERT INTO $this->tb_ketqua 
                VALUES ('$msv', $diem) ";

        $res = $this->Query($sql);

        return $res;
    }

    public function GetMsvDiem()
    {
        $sql = " SELECT MaSV FROM $this->tb_ketqua ";

        $res = $this->Query($sql);

        $data = [];

        while ($row = $res->fetch_assoc()) // Ton tai du lieu ng dung dang nhap
        {
            $data[] = $row;
        }

        return $data;
    }
}

?>

