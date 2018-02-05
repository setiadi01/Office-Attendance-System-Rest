<?php

namespace App\Transaction;

use Illuminate\Support\Facades\DB;
use App\System\System;

class ReportWebTransaction
{
	
	public static function getDetailCheckinList($input){

        $dateNow = System::date();
        $startDate = $input['startDate'];
        $endDate = $input['endDate'];
        $limit = $input['limit'];
        $offset = $input['offset'];

        if(!is_null($limit) && $limit!='' && $limit!=-99) {
            $limit = ' LIMIT '.$limit;
        } else {
            $limit = '';
        }

        if(!is_null($offset) && $offset!='' && $offset!=-99) {
            $offset = ' OFFSET '.$offset;
        } else {
            $offset = '';
        }

        $list  = DB::select("WITH one_month_absen AS (
                                SELECT A.user_id, B.string_date, A.full_name, B.flg_holiday
                                FROM t_user A, dt_date B
                                WHERE B.string_date BETWEEN '$startDate' AND '$endDate'
                                AND A.user_id NOT IN (1,2,18)
                            )
                            SELECT A.full_name, to_char(to_timestamp(A.string_date, 'YYYYMMDD'), 'FMDay, DD Mon YYYY') AS date, 
                                CASE WHEN B.checkin_datetime IS NOT NULL
                                    THEN to_char(to_timestamp(B.checkin_datetime, 'YYYYMMDDHH24MISS'), 'HH24:MI:SS')
                                ELSE '-' END AS checkin,
                                CASE WHEN B.checkout_datetime IS NOT NULL and B.checkout_datetime != ''
                                    THEN to_char(to_timestamp(B.checkout_datetime, 'YYYYMMDDHH24MISS'), 'HH24:MI:SS')
                                ELSE '-' END AS checkout,
                                CASE WHEN A.flg_holiday = 'N' AND B.checkin_datetime IS NULL AND A.string_date <= '$dateNow'
                                THEN 'N'
                                WHEN A.string_date > '$dateNow'
                                THEN '-'
                                ELSE 'Y'
                                END AS status_masuk,
                                CASE WHEN A.flg_holiday = 'Y' AND B.checkin_datetime IS NOT NULL
                                THEN 'Y'
                                WHEN A.string_date > '$dateNow'
                                THEN '-'
                                ELSE 'N'
                                END AS status_lembur,
                                COALESCE(D.reason_name, '-') AS description
                            FROM one_month_absen A
                            LEFT JOIN at_attendance B ON A.string_date = SUBSTRING(B.checkin_datetime, 1, 8) AND A.user_id = B.user_id
                            LEFT JOIN t_manage_lost_checkin C ON A.string_date = C.checkin_date AND A.user_id = C.user_id
                            LEFT JOIN t_reason D ON C.reason_id = D.reason_id
                            WHERE (A.flg_holiday = 'N' OR (A.flg_holiday = 'Y' AND B.checkin_datetime IS NOT NULL))
                            ORDER BY A.string_date, A.full_name".$limit . $offset);

        return [
            "detailCheckinList" => $list==null ? []: $list
        ];
		
	}

    public static function getSummaryCheckinList($input){
        $dateNow = System::date();
        $startDate = $input['startDate'];
        $endDate = $input['endDate'];
        $limit = $input['limit'];
        $offset = $input['offset'];

        if(!is_null($limit) && $limit!='' && $limit!=-99) {
            $limit = ' LIMIT '.$limit;
        } else {
            $limit = '';
        }

        if(!is_null($offset) && $offset!='' && $offset!=-99) {
            $offset = ' OFFSET '.$offset;
        } else {
            $offset = '';
        }

        $list  = DB::select("WITH one_month_absen AS (
                                SELECT A.user_id, B.string_date, A.full_name, B.flg_holiday
                                FROM t_user A, dt_date B
                                WHERE B.string_date BETWEEN '$startDate' AND '$endDate'
                                AND A.user_id NOT IN (1,2,18)
                            ), jumlah_masuk AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_masuk
                                FROM one_month_absen A
                                LEFT JOIN at_attendance B ON A.string_date = SUBSTRING(B.checkin_datetime, 1, 8) AND A.user_id = B.user_id
                                WHERE A.flg_holiday = 'N'
                                AND B.checkin_datetime IS NOT NULL
                                AND B.checkin_datetime != ''
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_tidak_masuk AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_tidak_masuk
                                FROM one_month_absen A
                                LEFT JOIN at_attendance B ON A.string_date = SUBSTRING(B.checkin_datetime, 1, 8) AND A.user_id = B.user_id
                                WHERE A.flg_holiday = 'N'
                                AND A.string_date <= '$dateNow'
                                AND (B.checkin_datetime IS NULL OR B.checkin_datetime = '')
                                AND NOT EXISTS (
                                    SELECT 1 FROM t_manage_lost_checkin AX WHERE A.user_id = AX.user_id
                                    AND A.string_date = AX.checkin_date
                                )
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_lembur AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_lembur
                                FROM one_month_absen A
                                LEFT JOIN at_attendance B ON A.string_date = SUBSTRING(B.checkin_datetime, 1, 8) AND A.user_id = B.user_id
                                WHERE A.flg_holiday = 'Y'
                                AND A.string_date <= '$dateNow'
                                AND B.checkin_datetime IS NOT NULL
                                AND B.checkin_datetime != ''
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_dinas_luar_kota AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_dinas_luar
                                FROM one_month_absen A
                                INNER JOIN t_manage_lost_checkin B ON A.string_date = B.checkin_date AND A.user_id = B.user_id
                                INNER JOIN t_reason C ON B.reason_id = C.reason_id
                                WHERE A.string_date <= '$dateNow'
                                AND C.reason_code = 'DL'
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_dinas_dalam_kota AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_dinas_dalam
                                FROM one_month_absen A
                                INNER JOIN t_manage_lost_checkin B ON A.string_date = B.checkin_date AND A.user_id = B.user_id
                                INNER JOIN t_reason C ON B.reason_id = C.reason_id
                                WHERE A.string_date <= '$dateNow'
                                AND C.reason_code = 'DD'
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_cuti AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_cuti
                                FROM one_month_absen A
                                INNER JOIN t_manage_lost_checkin B ON A.string_date = B.checkin_date AND A.user_id = B.user_id
                                INNER JOIN t_reason C ON B.reason_id = C.reason_id
                                WHERE A.string_date <= '$dateNow'
                                AND C.reason_code = 'C'
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            ), jumlah_lupa_checkin AS (
                                SELECT A.user_id, A.full_name, COUNT(1) AS jumlah_lupa_checkin
                                FROM one_month_absen A
                                INNER JOIN t_manage_lost_checkin B ON A.string_date = B.checkin_date AND A.user_id = B.user_id
                                INNER JOIN t_reason C ON B.reason_id = C.reason_id
                                WHERE A.string_date <= '$dateNow'
                                AND C.reason_code = 'L'
                                GROUP BY A.user_id, A.full_name
                                ORDER BY A.full_name
                            )

                            SELECT A.full_name, COALESCE(B.jumlah_masuk, 0)+COALESCE(E.jumlah_dinas_luar, 0)+COALESCE(F.jumlah_dinas_dalam, 0) AS jumlah_masuk, COALESCE(C.jumlah_tidak_masuk, 0) AS jumlah_tidak_masuk, 
                            COALESCE(D.jumlah_lembur, 0) AS jumlah_lembur, COALESCE(E.jumlah_dinas_luar, 0) AS jumlah_dinas_luar, 
                            COALESCE(F.jumlah_dinas_dalam, 0) AS jumlah_dinas_dalam, COALESCE(G.jumlah_cuti, 0) AS jumlah_cuti,
                            COALESCE(H.jumlah_lupa_checkin, 0) AS jumlah_lupa_checkin,
                            COALESCE(E.jumlah_dinas_luar, 0)+COALESCE(F.jumlah_dinas_dalam, 0)+COALESCE(B.jumlah_masuk, 0)+COALESCE(D.jumlah_lembur, 0) AS total_masuk
                            FROM t_user A
                            LEFT JOIN jumlah_masuk B ON A.user_id = B.user_id
                            LEFT JOIN jumlah_tidak_masuk C ON A.user_id = C.user_id
                            LEFT JOIN jumlah_lembur D ON A.user_id = D.user_id 
                            LEFT JOIN jumlah_dinas_luar_kota E ON A.user_id = E.user_id 
                            LEFT JOIN jumlah_dinas_dalam_kota F ON A.user_id = F.user_id 
                            LEFT JOIN jumlah_cuti G ON A.user_id = G.user_id 
                            LEFT JOIN jumlah_lupa_checkin H ON A.user_id = H.user_id 
                            WHERE A.user_id NOT IN (1,2,18)
                            ORDER BY B.full_name".$limit . $offset);

        return [
            "summaryCheckinList" => $list==null ? []: $list
        ];
    }

}