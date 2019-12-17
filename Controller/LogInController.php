<?php

require_once app_path.'/Model/UserModel.php';

class LogInController extends ControllerBase
{
    public function LogIn()
    {
        $dem = 1;
        $err = [];

        session_start();

        if (isset($_SESSION['auth_gv']))
        {
            header("Location: " .base_path .'/?ct=home-log-gv&act=home-log-gv');
        }
        else if (isset($_SESSION['auth_sv']))
        {
            header("Location: " .base_path .'/?ct=home-log-sv&act=home-log-sv');
        }
//        else $this->RenderView('login.log-in', $dataSv);

        if (isset($_POST['sub']))
        {
            $usn = $_POST['username'];
            $pass = $_POST['pwd'];

            // 1. Ktra thong tin ma sv nhap vao

            $check_user = '/^[A-Za-z0-9_]{3,10}$/';

            if (!preg_match($check_user, $usn))
            {
                $dem = 0;
                $err[] = "Tên đăng nhập phải có 3 kí tự";
            }

            if (strlen($pass) < 3)
            {
                $dem = 0;
                $err[] ="Password phải có 3 kí tự";
            }

            if ($dem === 1)
            {
                $objDb = new UserModel();
                $dataGv = $objDb->GetUserGv($usn);
                $dataSv = $objDb->GetUserSv($usn);

                if ($dataSv <> false || $dataGv <> false)
                {
                    if ($usn == $dataGv['user_gv'])
                    {
                        unset($dataSv);
                        if ($dataGv['pass_gv'] == $pass)
                        {
                            unset($dataGv['pass_gv']);

                            $_SESSION['auth_gv'] = $dataGv;

                            header("Location: " . base_path . '/?ct=home-log-gv&act=home-log-gv');
                        }

                        else $err[] ="Sai Pass";
                    }

                    else if ($usn == $dataSv['MaSV'])
                    {
                        unset($dataGv);
                        if ($dataSv['pass_sv'] == $pass)
                        {
                            unset($dataSv['pass_sv']);

                            $_SESSION['auth_sv'] = $dataSv;

                            header("Location: " . base_path . '/?ct=home-log-sv&act=home-log-sv');
                        }

                        else $err[] ="Sai Pass";
                    }
                }
                else $err[] ="Khong ton tai user: $usn";
            }

            if (!empty($err))
            {
                $dataSv = [];
                $dataSv['loi'] = $err;
                $this->RenderViewLogin('login.log-in', $dataSv);
            }
//            else
//            {
//                header("Location: " . base_path . '/?ct=home-log-sv&act=home-log-sv');
//            }
        }
        else
        {
            $dataSv = [];
            $this->RenderViewLogin('login.log-in', $dataSv);
        }
    }

    public function LogOut()
    {
        session_start();
        session_unset();
        header("Location: " .base_path);
    }
}

?>

