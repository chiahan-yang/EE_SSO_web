<?php

namespace App\Services;

use SoapClient;
use Exception;

class SSOService
{
    protected $wsdlUrl = "http://sso.nsysu.edu.tw/ssoWebservice/wsso.wsdl";

    /**
     * 驗證並取得完整的 SSO 使用者資訊映射表
     */
    public function authenticate($username, $password)
    {
        try {
            $client = new SoapClient($this->wsdlUrl, [
                'trace' => true, 
                'cache_wsdl' => WSDL_CACHE_NONE,
                'connection_timeout' => 5
            ]);

            // 執行驗證
            $auth = $client->authUser2($username, $password, "ENC", "1;2");

            if ($auth) {
                // 抓取所有欄位
                $infoStr = $client->getAttr2($username, $password, "ENC", "1;2", "EMPNO;NAME;IDNO;PKIND;GRPNO;UNICOD1;DPT_DESC1;UNICOD2;DPT_DESC2;LEAVE;TITCOD;TITLE;EMAIL;POFTEL");
                $ssoData = explode(";", $infoStr);

                // 完整映射表（保留你原本所有的 Key）
                $ssoMap = [
                    '員工編號 (EMPNO)' => $ssoData[0] ?? '',
                    '姓名 (NAME)' => $ssoData[1] ?? '',
                    '身分證號 (IDNO)' => $ssoData[2] ?? '',
                    '人員類別 (PKIND)' => $ssoData[3] ?? '',
                    '群組代碼 (GRPNO)' => $ssoData[4] ?? '',
                    '單位代碼1 (UNICOD1)' => $ssoData[5] ?? '',
                    '單位名稱1 (DPT_DESC1)' => $ssoData[6] ?? '',
                    '單位代碼2 (UNICOD2)' => $ssoData[7] ?? '',
                    '單位名稱2 (DPT_DESC2)' => $ssoData[8] ?? '',
                    '離職註記 (LEAVE)' => $ssoData[9] ?? '',
                    '職稱代碼 (TITCOD)' => $ssoData[10] ?? '',
                    '職稱名稱 (TITLE)' => $ssoData[11] ?? '',
                    'Email (EMAIL)' => $ssoData[12] ?? '',
                    '辦公室電話 (POFTEL)' => $ssoData[13] ?? '',
                ];

                return [
                    'success' => true,
                    'account' => $ssoData[0] ?? $username, // 員編作為系統帳號
                    'name'    => $ssoData[1] ?? $username,
                    'email'   => $ssoData[12] ?? null,
                    'map'     => $ssoMap // 這裡傳回完整陣列供 Controller 使用
                ];
            }
        } catch (Exception $e) {
            // Log::error("SSO Connection Error: " . $e->getMessage());
        }

        return ['success' => false];
    }
}