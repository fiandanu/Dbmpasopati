<?php

namespace App\Http\Controllers\user\upt;

use App\Http\Controllers\Controller;
use App\Models\User\Upt;

class DashboardUptController extends Controller
{
    public function index()
    {
        // Statistik PKS
        $pksData = $this->getPksStatistics();

        // Statistik SPP
        $sppData = $this->getSppStatistics();

        // Statistik VPAS
        $vpasData = $this->getVpasStatistics();

        // Statistik REGULER
        $regulerData = $this->getRegulerStatistics();

        // dd($pksData, $sppData, $vpasData, $regulerData);

        return view('db.pageKategoriUpt', compact('pksData', 'sppData', 'vpasData', 'regulerData'));
    }

    private function getPksStatistics()
    {
        $total = Upt::whereHas('uploadFolderPks')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Upt::with('uploadFolderPks')->whereHas('uploadFolderPks')->get();

        foreach ($data as $item) {
            $uploadFolder = $item->uploadFolderPks;

            if (!$uploadFolder) {
                $belumUpload++;
                continue;
            }

            $hasPdf1 = !empty($uploadFolder->uploaded_pdf_1);
            $hasPdf2 = !empty($uploadFolder->uploaded_pdf_2);

            if ($hasPdf1 && $hasPdf2) {
                $sudahUpload++;
            } elseif ($hasPdf1 || $hasPdf2) {
                $sebagian++;
            } else {
                $belumUpload++;
            }
        }

        return [
            'total' => $total,
            'belum_upload' => $belumUpload,
            'sebagian' => $sebagian,
            'sudah_upload' => $sudahUpload,
        ];
    }

    private function getSppStatistics()
    {
        $total = Upt::whereHas('uploadFolderSpp')->count();

        $belumUpload = 0;
        $sebagian = 0;
        $sudahUpload = 0;

        $data = Upt::with('uploadFolderSpp')->whereHas('uploadFolderSpp')->get();

        foreach ($data as $item) {
            $uploadFolder = $item->uploadFolderSpp;

            if (!$uploadFolder) {
                $belumUpload++;
                continue;
            }

            $uploadedFolders = 0;
            for ($i = 1; $i <= 10; $i++) {
                $column = 'pdf_folder_' . $i;
                if (!empty($uploadFolder->$column)) {
                    $uploadedFolders++;
                }
            }

            if ($uploadedFolders == 0) {
                $belumUpload++;
            } elseif ($uploadedFolders == 10) {
                $sudahUpload++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_upload' => $belumUpload,
            'sebagian' => $sebagian,
            'sudah_upload' => $sudahUpload,
        ];
    }

    private function getVpasStatistics()
    {
        $optionalFields = [
            'pic_upt',
            'no_telpon',
            'alamat',
            'jumlah_wbp',
            'jumlah_line',
            'provider_internet',
            'kecepatan_internet',
            'tarif_wartel',
            'status_wartel',
            'akses_topup_pulsa',
            'password_topup',
            'akses_download_rekaman',
            'password_download',
            'internet_protocol',
            'vpn_user',
            'vpn_password',
            'jumlah_extension',
            'no_pemanggil',
            'email_airdroid',
            'password',
            'pin_tes'
        ];

        $total = Upt::where('tipe', 'vpas')->count();

        $belumUpdate = 0;
        $sebagian = 0;
        $sudahUpdate = 0;

        $data = Upt::with('dataOpsional')->where('tipe', 'vpas')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional) {
                $belumUpdate++;
                continue;
            }

            $filledFields = 0;
            foreach ($optionalFields as $field) {
                if (!empty($dataOpsional->$field)) {
                    $filledFields++;
                }
            }

            $totalFields = count($optionalFields);

            if ($filledFields == 0) {
                $belumUpdate++;
            } elseif ($filledFields == $totalFields) {
                $sudahUpdate++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sebagian' => $sebagian,
            'sudah_update' => $sudahUpdate,
        ];
    }

    private function getRegulerStatistics()
    {
        $optionalFields = [
            'pic_upt',
            'no_telpon',
            'alamat',
            'jumlah_wbp',
            'jumlah_line',
            'provider_internet',
            'kecepatan_internet',
            'tarif_wartel',
            'status_wartel',
            'akses_topup_pulsa',
            'password_topup',
            'akses_download_rekaman',
            'password_download',
            'internet_protocol',
            'vpn_user',
            'vpn_password',
            'jumlah_extension',
            'no_extension',
            'extension_password',
            'pin_tes'
        ];

        $total = Upt::where('tipe', 'reguler')->count();

        $belumUpdate = 0;
        $sebagian = 0;
        $sudahUpdate = 0;

        $data = Upt::with('dataOpsional')->where('tipe', 'reguler')->get();

        foreach ($data as $item) {
            $dataOpsional = $item->dataOpsional;

            if (!$dataOpsional) {
                $belumUpdate++;
                continue;
            }

            $filledFields = 0;
            foreach ($optionalFields as $field) {
                if (!empty($dataOpsional->$field)) {
                    $filledFields++;
                }
            }

            $totalFields = count($optionalFields);

            if ($filledFields == 0) {
                $belumUpdate++;
            } elseif ($filledFields == $totalFields) {
                $sudahUpdate++;
            } else {
                $sebagian++;
            }
        }

        return [
            'total' => $total,
            'belum_update' => $belumUpdate,
            'sebagian' => $sebagian,
            'sudah_update' => $sudahUpdate,
        ];
    }
}
