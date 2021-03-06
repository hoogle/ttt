<?php
class L10n extends Controller
{
    private $_browser_lang = "";

    public function __construct()
    {
        parent::Controller();
        $accept_lang_arr = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        list($lang_lang, $lang_country) = explode("-", $accept_lang_arr[0]);
        $this->_browser_lang = ( ! isset($lang_country)) ? "en_US" : mb_strtolower($lang_lang) . "_" . mb_strtoupper($lang_country);
    }

    public function index()
    {
        $this->load->library('session');
        $lang_perm = $this->session->userdata('lang_perm');
        $userid = $this->session->userdata('user_id');
        $is_login = ($userid) ? 1 : 0;
        $data = array(
            'userid' => $userid,
            'is_login' => $is_login,
            'lang_perm' => ($is_login) ? $lang_perm: NULL,
            'use_lang' => $this->_browser_lang,
        );
        $this->load->library("layout", "layout_main");
        if ( ! $is_login)
        {
            $this->layout->view("l10n/l10n/index", $data);
        }
        else
        {
            echo modules::run("l10n/lang/changes", $data);
        }
    }

    public function permission()
    {
        $this->load->library('session');
        $lang_perm = $this->session->userdata('lang_perm');
        $level = $this->session->userdata('level');
        $userid = $this->session->userdata('user_id');
        $is_login = ($userid) ? 1 : 0;
        $this->load->library("layout", "layout_main");
        if ( ! $is_login)
        {
            $data = array(
                "lang_perm" => NULL,
                "go_url" => ",l10n,permission",
            );
            $this->layout->view("l10n/login/please_login", $data);
        }
        else
        {
            if ( ! $level)
            {
                $this->load->model("l10n_model");
                $lang_arr = $this->l10n_model->load_languages();

                $data = array(
                    "use_lang" => $this->_browser_lang,
                    "userid" => $userid,
                    "level" => $level,
                    "lang_perm" => $lang_perm,
                    "lang_arr" => $lang_arr,
                );
                $this->layout->view("l10n/l10n/permission", $data);
            }
            else
            {
                $data = array (
                    "error_str" => "Permission denied, you have no create new layer permission!",
                    "userid" => $userid,
                    "lang_perm" => NULL,
                );
                $this->load->library("layout", "layout_main");
                $this->layout->view("l10n/lang/error", $data);
            }
        }
    }
}

?>
