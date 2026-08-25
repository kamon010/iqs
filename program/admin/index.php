<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/png" href="../img/icon.png" />

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue-light.css">

    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">

    <style>
        body,
        .content {
            font-family: 'Kanit', sans-serif;
        }

        .small-box .inner h3 {
            font-size: 34px;
            font-weight: bold;
        }

        .small-box .inner p {
            font-size: 16px;
        }

        .box-title {
            font-family: 'Kanit', sans-serif;
            font-weight: bold;
            font-size: 18px;
        }

        .dashboard-note {
            font-family: 'Kanit', sans-serif;
            color: #777;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 0;
        }

        .chart-box {
            min-height: 480px;
        }

        .chart-area {
            height: 370px;
            width: 100%;
        }

        .empty-chart {
            padding: 35px;
            text-align: center;
            color: #999;
            font-size: 15px;
        }

        .question-select {
            max-width: 480px;
            margin-top: 8px;
        }
    </style>

    <?php
        include("php/htmlCore.php");
        include_once("../php/applicant_options.php");
        include('indyEngine/engineControl.php');

        setInclude('htmlControl,dbControl,IDUControl,sessionControl');
        setPlugin('jquery,jquery-ui,alert,loaderbar');
        setPermission('login');

        HtmlCore::getTitle();

        $query = new DBControl();

        function h($text) {
            return htmlspecialchars(strval($text), ENT_QUOTES, 'UTF-8');
        }

        function jsonThai($data) {
            $option = 0;

            if (defined('JSON_UNESCAPED_UNICODE')) {
                $option = $option | JSON_UNESCAPED_UNICODE;
            }

            if (defined('JSON_NUMERIC_CHECK')) {
                $option = $option | JSON_NUMERIC_CHECK;
            }

            return json_encode($data, $option);
        }

        function chartRows($query, $sql) {
            $rows = $query->queryDB_to_array($sql);
            $data = array();

            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $name = isset($row['name']) ? trim($row['name']) : 'ไม่ระบุ';
                    $y = isset($row['y']) ? intval($row['y']) : 0;

                    if ($name == '') {
                        $name = 'ไม่ระบุ';
                    }

                    if ($y > 0) {
                        $data[] = array(
                            'name' => $name,
                            'y' => $y
                        );
                    }
                }
            }

            return $data;
        }

        function dbCount($query, $sql) {
            return intval($query->queryDB_to_string($sql));
        }

        /*
            =====================================================
            ตรวจสอบสิทธิ์ผู้ดูแล / มหาวิทยาลัย
            =====================================================
        */

        $admin_user_id = intval($_COOKIE["user"] ?? 0);
        $admin_university_id = 0;

        if ($admin_user_id > 0) {
            $admin_university_id = intval($query->queryDB_to_string(
                "SELECT university_id FROM `user` WHERE user_id = ".$admin_user_id
            ));
        }

        $userFilterSql = "u.user_type != 'admin'";

        $userFilterSql .= getApplicantAdminScopeCondition($admin_university_id, 'u.university_id');

        $memberFilterSql = "user_type = 'member'";

        $memberFilterSql .= getApplicantAdminScopeCondition($admin_university_id, 'university_id');

        /*
            =====================================================
            ข้อมูลล่าสุดของแต่ละ user จาก test_a และ test_e
            =====================================================

            test_a = บุหรี่มวน
            test_e = บุหรี่ไฟฟ้า

            เงื่อนไข:
            1) รวม test_a และ test_e
            2) หา date ล่าสุดของแต่ละ user
            3) ถ้าวันล่าสุดมี test_a อย่างเดียว = สูบบุหรี่มวนอย่างเดียว
            4) ถ้าวันล่าสุดมี test_e อย่างเดียว = สูบบุหรี่ไฟฟ้าอย่างเดียว
            5) ถ้าวันล่าสุดมีทั้ง test_a และ test_e = สูบทั้งสองอย่าง
            6) ถ้าวันเดียวกันมีหลายแถว ใช้ id ล่าสุด
            7) กรอง user ตามสิทธิ์ admin เหมือนหน้ารายงาน
        */

        $latestSmokeSql = "
            SELECT
                latest_user.user_id,
                latest_user.latest_date,

                a.id AS test_a_id,
                a.score AS score_a,
                a.q1 AS a_q1,
                a.q2 AS a_q2,
                a.q3 AS a_q3,
                a.q4 AS a_q4,
                a.q5 AS a_q5,
                a.q6 AS a_q6,

                e.id AS test_e_id,
                e.score AS score_e,
                e.q1 AS e_q1,
                e.q2 AS e_q2,
                e.q3 AS e_q3,
                e.q4 AS e_q4,
                e.q5 AS e_q5,
                e.q6 AS e_q6

            FROM (
                SELECT
                    all_test.user_id,
                    MAX(all_test.real_date) AS latest_date
                FROM (
                    SELECT
                        user_id,
                        COALESCE(
                            DATE(`date`),
                            DATE(STR_TO_DATE(`date`, '%Y-%c-%e'))
                        ) AS real_date
                    FROM `test_a`

                    UNION ALL

                    SELECT
                        user_id,
                        COALESCE(
                            DATE(`date`),
                            DATE(STR_TO_DATE(`date`, '%Y-%c-%e'))
                        ) AS real_date
                    FROM `test_e`
                ) AS all_test
                WHERE all_test.real_date IS NOT NULL
                GROUP BY all_test.user_id
            ) AS latest_user

            INNER JOIN `user` AS u
                ON u.user_id = latest_user.user_id

            LEFT JOIN `test_a` AS a
                ON a.id = (
                    SELECT MAX(a2.id)
                    FROM `test_a` AS a2
                    WHERE a2.user_id = latest_user.user_id
                    AND COALESCE(
                        DATE(a2.`date`),
                        DATE(STR_TO_DATE(a2.`date`, '%Y-%c-%e'))
                    ) = latest_user.latest_date
                )

            LEFT JOIN `test_e` AS e
                ON e.id = (
                    SELECT MAX(e2.id)
                    FROM `test_e` AS e2
                    WHERE e2.user_id = latest_user.user_id
                    AND COALESCE(
                        DATE(e2.`date`),
                        DATE(STR_TO_DATE(e2.`date`, '%Y-%c-%e'))
                    ) = latest_user.latest_date
                )

            WHERE ".$userFilterSql."
        ";

        function countByMap($query, $baseSql, $field, $map, $whereSql) {
            $caseName = "CASE ";
            $caseOrder = "CASE ";
            $i = 1;

            foreach ($map as $value => $label) {
                $safeValue = intval($value);
                $safeLabel = str_replace("'", "''", $label);

                $caseName .= " WHEN ".$field." = ".$safeValue." THEN '".$safeLabel."' ";
                $caseOrder .= " WHEN ".$field." = ".$safeValue." THEN ".$i." ";

                $i++;
            }

            $caseName .= " ELSE 'ไม่ระบุ' END ";
            $caseOrder .= " ELSE 999 END ";

            $sql = "
                SELECT
                    ".$caseName." AS name,
                    COUNT(*) AS y,
                    ".$caseOrder." AS order_no
                FROM (
                    ".$baseSql."
                ) AS latest_data
                WHERE ".$whereSql."
                GROUP BY name, order_no
                ORDER BY order_no ASC
            ";

            return chartRows($query, $sql);
        }

        function countScoreLevel($query, $baseSql, $scoreField, $whereSql) {
            $sql = "
                SELECT
                    CASE
                        WHEN ".$scoreField." IS NULL OR CAST(".$scoreField." AS CHAR) = ''
                            THEN 'ไม่ระบุ'
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 0 AND 2
                            THEN 'ต่ำมาก 0–2 คะแนน'
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 3 AND 4
                            THEN 'ต่ำ 3–4 คะแนน'
                        WHEN CAST(".$scoreField." AS UNSIGNED) = 5
                            THEN 'ปานกลาง 5 คะแนน'
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 6 AND 7
                            THEN 'สูง 6–7 คะแนน'
                        WHEN CAST(".$scoreField." AS UNSIGNED) >= 8
                            THEN 'สูงมาก 8 คะแนนขึ้นไป'
                        ELSE 'ไม่ระบุ'
                    END AS name,

                    COUNT(*) AS y,

                    CASE
                        WHEN ".$scoreField." IS NULL OR CAST(".$scoreField." AS CHAR) = ''
                            THEN 999
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 0 AND 2
                            THEN 1
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 3 AND 4
                            THEN 2
                        WHEN CAST(".$scoreField." AS UNSIGNED) = 5
                            THEN 3
                        WHEN CAST(".$scoreField." AS UNSIGNED) BETWEEN 6 AND 7
                            THEN 4
                        WHEN CAST(".$scoreField." AS UNSIGNED) >= 8
                            THEN 5
                        ELSE 999
                    END AS order_no

                FROM (
                    ".$baseSql."
                ) AS latest_data
                WHERE ".$whereSql."
                GROUP BY name, order_no
                ORDER BY order_no ASC
            ";

            return chartRows($query, $sql);
        }

        /*
            =====================================================
            ข้อมูลกล่องด้านบน
            =====================================================
        */

        $n_user = dbCount($query, "
            SELECT COUNT(user_id)
            FROM `user`
            WHERE ".$memberFilterSql."
        ");

        $n_screening = dbCount($query, "
            SELECT COUNT(screening.id)
            FROM `screening`
            INNER JOIN `user` AS u
                ON u.user_id = screening.user_id
            WHERE ".$userFilterSql."
        ");

		$n_testA = dbCount($query, "
			SELECT COUNT(test_a.id)
			FROM `test_a`
			INNER JOIN `user` AS u
				ON u.user_id = test_a.user_id
			WHERE ".$userFilterSql."
		");

		$n_testE = dbCount($query, "
			SELECT COUNT(test_e.id)
			FROM `test_e`
			INNER JOIN `user` AS u
				ON u.user_id = test_e.user_id
			WHERE ".$userFilterSql."
		");

        /*
            =====================================================
            เงื่อนไขตอบครบ
            =====================================================
            ใช้ CAST(... AS CHAR) <> '' เพราะบางคำตอบมีค่า 0
            ค่า 0 เป็นคำตอบจริง ไม่ใช่ค่าว่าง
        */

        $completeA = "
            a_q1 IS NOT NULL AND CAST(a_q1 AS CHAR) <> ''
            AND a_q2 IS NOT NULL AND CAST(a_q2 AS CHAR) <> ''
            AND a_q3 IS NOT NULL AND CAST(a_q3 AS CHAR) <> ''
            AND a_q4 IS NOT NULL AND CAST(a_q4 AS CHAR) <> ''
            AND a_q5 IS NOT NULL AND CAST(a_q5 AS CHAR) <> ''
            AND a_q6 IS NOT NULL AND CAST(a_q6 AS CHAR) <> ''
            AND score_a IS NOT NULL AND CAST(score_a AS CHAR) <> ''
        ";

        $completeE = "
            e_q1 IS NOT NULL AND CAST(e_q1 AS CHAR) <> ''
            AND e_q2 IS NOT NULL AND CAST(e_q2 AS CHAR) <> ''
            AND e_q3 IS NOT NULL AND CAST(e_q3 AS CHAR) <> ''
            AND e_q4 IS NOT NULL AND CAST(e_q4 AS CHAR) <> ''
            AND e_q5 IS NOT NULL AND CAST(e_q5 AS CHAR) <> ''
            AND e_q6 IS NOT NULL AND CAST(e_q6 AS CHAR) <> ''
            AND score_e IS NOT NULL AND CAST(score_e AS CHAR) <> ''
        ";

        /*
            =====================================================
            เงื่อนไขแยกกลุ่ม
            =====================================================
            - บุหรี่มวนอย่างเดียว = มี test_a ไม่มี test_e และตอบ test_a ครบ
            - บุหรี่ไฟฟ้าอย่างเดียว = มี test_e ไม่มี test_a และตอบ test_e ครบ
            - สูบทั้งสอง = มีทั้ง test_a และ test_e และตอบครบทั้งสองชุด
        */

        $whereOnlyAComplete = "test_a_id IS NOT NULL AND test_e_id IS NULL AND ".$completeA;
        $whereOnlyEComplete = "test_a_id IS NULL AND test_e_id IS NOT NULL AND ".$completeE;
        $whereBothComplete = "test_a_id IS NOT NULL AND test_e_id IS NOT NULL AND ".$completeA." AND ".$completeE;

        /*
            ใช้สำหรับกราฟของแต่ละกลุ่ม
        */
        $whereAOnly = $whereOnlyAComplete;
        $whereEOnly = $whereOnlyEComplete;
        $whereBothA = $whereBothComplete;
        $whereBothE = $whereBothComplete;

        /*
            =====================================================
            กราฟที่ 1: ประเภทการสูบจากข้อมูลล่าสุด
            =====================================================
        */

        $smokeTypeData = chartRows($query, "
            SELECT
                'สูบบุหรี่มวนอย่างเดียว' AS name,
                COUNT(*) AS y,
                1 AS order_no
            FROM (
                ".$latestSmokeSql."
            ) AS latest_data
            WHERE ".$whereOnlyAComplete."

            UNION ALL

            SELECT
                'สูบบุหรี่ไฟฟ้าอย่างเดียว' AS name,
                COUNT(*) AS y,
                2 AS order_no
            FROM (
                ".$latestSmokeSql."
            ) AS latest_data
            WHERE ".$whereOnlyEComplete."

            UNION ALL

            SELECT
                'สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า' AS name,
                COUNT(*) AS y,
                3 AS order_no
            FROM (
                ".$latestSmokeSql."
            ) AS latest_data
            WHERE ".$whereBothComplete."

            ORDER BY order_no ASC
        ");

        /*
            =====================================================
            คำตอบของ test_a = บุหรี่มวน
            =====================================================
        */

        $a_q1_map = array(
            1 => '10 มวน หรือน้อยกว่า',
            2 => '11–20 มวน',
            3 => '21–30 มวน',
            4 => 'มากกว่า 30 มวน'
        );

        $a_q2_map = array(
            1 => 'มากกว่า 60 นาที หลังตื่นนอน',
            2 => '31–60 นาที หลังตื่นนอน',
            3 => '6–30 นาที หลังตื่นนอน',
            4 => 'ภายใน 5 นาที หลังตื่นนอน'
        );

        $a_yes_no_map = array(
            1 => 'ไม่ใช่',
            2 => 'ใช่'
        );

        $a_q4_map = array(
            1 => 'มวนแรกตอนเช้า',
            2 => 'มวนอื่น ๆ'
        );

        /*
            =====================================================
            คำตอบของ test_e = บุหรี่ไฟฟ้า
            =====================================================
        */

        $e_q1_map = array(
            3 => 'ภายใน 5 นาที',
            2 => '6–30 นาที',
            1 => '31–60 นาที',
            0 => 'มากกว่า 60 นาที'
        );

        $e_q2_map = array(
            4 => 'มากกว่า 20 ครั้งต่อวัน',
            3 => '11–20 ครั้งต่อวัน',
            1 => '6–10 ครั้งต่อวัน',
            0 => '1–5 ครั้งต่อวัน'
        );

        $e_yes_no_map = array(
            1 => 'ใช่',
            0 => 'ไม่ใช่'
        );

        $e_q5_map = array(
            1 => 'ใช่',
            2 => 'ไม่ใช่'
        );

        $e_q6_map = array(
            2 => '≥ 35 mg/ml',
            1 => '20–34 mg/ml',
            0 => '< 20 mg/ml'
        );

        /*
            =====================================================
            กราฟที่ 2: บุหรี่มวนอย่างเดียว
            =====================================================
        */

        $aOnlyQuestionCharts = array(
            'score' => array(
                'title' => 'ระดับการติดนิโคติน',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countScoreLevel($query, $latestSmokeSql, 'score_a', $whereAOnly)
            ),
            'q1' => array(
                'title' => 'โดยปกติสูบกี่มวนต่อวัน',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q1', $a_q1_map, $whereAOnly)
            ),
            'q2' => array(
                'title' => 'หลังตื่นนอนสูบมวนแรกเมื่อไร',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q2', $a_q2_map, $whereAOnly)
            ),
            'q3' => array(
                'title' => 'สูบในบริเวณที่ไม่ควรสูบหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q3', $a_yes_no_map, $whereAOnly)
            ),
            'q4' => array(
                'title' => 'มวนไหนที่เลิกยากที่สุด',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q4', $a_q4_map, $whereAOnly)
            ),
            'q5' => array(
                'title' => 'รู้สึกลำบากเมื่อต้องอยู่ในสถานที่ปลอดบุหรี่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q5', $a_yes_no_map, $whereAOnly)
            ),
            'q6' => array(
                'title' => 'ต้องสูบแม้เจ็บป่วยหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่มวนอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'a_q6', $a_yes_no_map, $whereAOnly)
            )
        );

        /*
            =====================================================
            กราฟที่ 3: บุหรี่ไฟฟ้าอย่างเดียว
            =====================================================
        */

        $eOnlyQuestionCharts = array(
            // 'score' => array(
            //     'title' => 'ระดับการติดนิโคติน',
            //     'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
            //     'data' => countScoreLevel($query, $latestSmokeSql, 'score_e', $whereEOnly)
            // ),
            'q1' => array(
                'title' => 'หลังตื่นนอนใช้เมื่อไร',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q1', $e_q1_map, $whereEOnly)
            ),
            'q2' => array(
                'title' => 'ใช้กี่ครั้งต่อวัน',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q2', $e_q2_map, $whereEOnly)
            ),
            'q3' => array(
                'title' => 'การใช้ในช่วงสำคัญกว่าช่วงอื่นหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q3', $e_yes_no_map, $whereEOnly)
            ),
            'q4' => array(
                'title' => 'เคยพยายามหยุดแล้วหงุดหงิดหรือวิตกกังวลหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q4', $e_yes_no_map, $whereEOnly)
            ),
            'q5' => array(
                'title' => 'ใช้แม้เจ็บป่วยหรือต้องพักรักษาตัวหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q5', $e_q5_map, $whereEOnly)
            ),
            'q6' => array(
                'title' => 'ปริมาณนิโคตินในน้ำยาที่ใช้',
                'subtitle' => 'เฉพาะผู้ที่สูบบุหรี่ไฟฟ้าอย่างเดียว',
                'data' => countByMap($query, $latestSmokeSql, 'e_q6', $e_q6_map, $whereEOnly)
            )
        );

        /*
            =====================================================
            กราฟที่ 4: สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
            =====================================================
            ใน dropdown จะแยกให้เลือกดูคำถามฝั่งบุหรี่มวน และฝั่งบุหรี่ไฟฟ้า
        */

        $bothQuestionCharts = array(
            'a_score' => array(
                'title' => 'บุหรี่มวน: ระดับการติดนิโคติน',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countScoreLevel($query, $latestSmokeSql, 'score_a', $whereBothA)
            ),
            'a_q1' => array(
                'title' => 'บุหรี่มวน: โดยปกติสูบกี่มวนต่อวัน',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q1', $a_q1_map, $whereBothA)
            ),
            'a_q2' => array(
                'title' => 'บุหรี่มวน: หลังตื่นนอนสูบมวนแรกเมื่อไร',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q2', $a_q2_map, $whereBothA)
            ),
            'a_q3' => array(
                'title' => 'บุหรี่มวน: สูบในบริเวณที่ไม่ควรสูบหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q3', $a_yes_no_map, $whereBothA)
            ),
            'a_q4' => array(
                'title' => 'บุหรี่มวน: มวนไหนที่เลิกยากที่สุด',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q4', $a_q4_map, $whereBothA)
            ),
            'a_q5' => array(
                'title' => 'บุหรี่มวน: รู้สึกลำบากเมื่อต้องอยู่ในสถานที่ปลอดบุหรี่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q5', $a_yes_no_map, $whereBothA)
            ),
            'a_q6' => array(
                'title' => 'บุหรี่มวน: ต้องสูบแม้เจ็บป่วยหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'a_q6', $a_yes_no_map, $whereBothA)
            ),

            // 'e_score' => array(
            //     'title' => 'บุหรี่ไฟฟ้า: ระดับการติดนิโคติน',
            //     'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
            //     'data' => countScoreLevel($query, $latestSmokeSql, 'score_e', $whereBothE)
            // ),
            'e_q1' => array(
                'title' => 'บุหรี่ไฟฟ้า: หลังตื่นนอนใช้เมื่อไร',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q1', $e_q1_map, $whereBothE)
            ),
            'e_q2' => array(
                'title' => 'บุหรี่ไฟฟ้า: ใช้กี่ครั้งต่อวัน',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q2', $e_q2_map, $whereBothE)
            ),
            'e_q3' => array(
                'title' => 'บุหรี่ไฟฟ้า: การใช้ในช่วงสำคัญกว่าช่วงอื่นหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q3', $e_yes_no_map, $whereBothE)
            ),
            'e_q4' => array(
                'title' => 'บุหรี่ไฟฟ้า: เคยพยายามหยุดแล้วหงุดหงิดหรือวิตกกังวลหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q4', $e_yes_no_map, $whereBothE)
            ),
            'e_q5' => array(
                'title' => 'บุหรี่ไฟฟ้า: ใช้แม้เจ็บป่วยหรือต้องพักรักษาตัวหรือไม่',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q5', $e_q5_map, $whereBothE)
            ),
            'e_q6' => array(
                'title' => 'บุหรี่ไฟฟ้า: ปริมาณนิโคตินในน้ำยาที่ใช้',
                'subtitle' => 'เฉพาะผู้ที่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า',
                'data' => countByMap($query, $latestSmokeSql, 'e_q6', $e_q6_map, $whereBothE)
            )
        );

    ?>
</head>

<body class="hold-transition skin-blue-light">
<div class="wrapper">

    <header class="main-header">

        <?php
            HtmlCore::getTextHeader();
        ?>

        <nav class="navbar navbar-static-top" role="navigation">

            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <?php
                HtmlCore::getMenuHeader();
            ?>

        </nav>
    </header>

    <aside class="main-sidebar">

        <section class="sidebar">
            <?php
                HtmlCore::getMenu('index');
            ?>
        </section>

    </aside>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                Dashboard
                <small>TRC admin</small>
            </h1>

            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?php echo $n_user; ?>
                    <sup style="font-size: 20px"> คน</sup>
                </h3>
                <p>จำนวนผู้ใช้</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <p class="small-box-footer"></p>
        </div>
    </div>

				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-green">
						<div class="inner">
							<h3>
								<?php echo $n_screening; ?>
								<sup style="font-size: 20px"> ครั้ง</sup>
							</h3>
							<p>การประเมินคัดกรอง</p>
						</div>
						<div class="icon">
							<i class="fa fa-edit"></i>
						</div>
						<p class="small-box-footer"></p>
					</div>
				</div>

				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>
								<?php echo $n_testA; ?>
								<sup style="font-size: 20px"> ครั้ง</sup>
							</h3>
							<p>แบบประเมินบุหรี่มวน</p>
						</div>
						<div class="icon">
							<i class="fa fa-signal"></i>
						</div>
						<p class="small-box-footer"></p>
					</div>
				</div>

				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-red">
						<div class="inner">
							<h3>
								<?php echo $n_testE; ?>
								<sup style="font-size: 20px"> ครั้ง</sup>
							</h3>
							<p>แบบประเมินบุหรี่ไฟฟ้า</p>
						</div>
						<div class="icon">
							<i class="fa fa-bolt"></i>
						</div>
						<p class="small-box-footer"></p>
					</div>
				</div>

			</div>

            <!-- กราฟที่ 1 -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary chart-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-pie-chart"></i>
                                ประเภทการสูบจากข้อมูลล่าสุด
                            </h3>
                        </div>

                        <div class="box-body">
                            <div id="smokeTypePieChart" class="chart-area"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- กราฟที่ 2 และ 3 -->
            <div class="row">

                <div class="col-md-6">
                    <div class="box box-warning chart-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-pie-chart"></i>
                                บุหรี่มวนอย่างเดียว
                            </h3>

                            <p class="dashboard-note" id="aOnlyQuestionSubtitle">
                                เลือกคำถามที่ต้องการดูข้อมูล
                            </p>

                            <select id="aOnlyQuestionSelect" class="form-control question-select"></select>
                        </div>

                        <div class="box-body">
                            <div id="aOnlyQuestionPieChart" class="chart-area"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-danger chart-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-pie-chart"></i>
                                บุหรี่ไฟฟ้าอย่างเดียว
                            </h3>

                            <p class="dashboard-note" id="eOnlyQuestionSubtitle">
                                เลือกคำถามที่ต้องการดูข้อมูล
                            </p>

                            <select id="eOnlyQuestionSelect" class="form-control question-select"></select>
                        </div>

                        <div class="box-body">
                            <div id="eOnlyQuestionPieChart" class="chart-area"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- กราฟที่ 4 -->
            <div class="row">

                <div class="col-md-12">
                    <div class="box box-success chart-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-pie-chart"></i>
                                สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
                            </h3>

                            <p class="dashboard-note" id="bothQuestionSubtitle">
                                เลือกคำถามฝั่งบุหรี่มวนหรือบุหรี่ไฟฟ้าที่ต้องการดูข้อมูล
                            </p>

                            <select id="bothQuestionSelect" class="form-control question-select"></select>
                        </div>

                        <div class="box-body">
                            <div id="bothQuestionPieChart" class="chart-area"></div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

    </div>

    <footer class="main-footer">
        <?php
            HtmlCore::getMenuFooter();
        ?>
    </footer>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
$(function () {

    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

    var smokeTypeData = <?php echo jsonThai($smokeTypeData); ?>;
    var aOnlyQuestionCharts = <?php echo jsonThai($aOnlyQuestionCharts); ?>;
    var eOnlyQuestionCharts = <?php echo jsonThai($eOnlyQuestionCharts); ?>;
    var bothQuestionCharts = <?php echo jsonThai($bothQuestionCharts); ?>;

    function showNoData(container, message) {
        $('#' + container).html(
            '<div class="empty-chart">' +
                '<i class="fa fa-info-circle"></i><br>' +
                message +
            '</div>'
        );
    }

    function renderPieChart(container, title, subtitle, data) {

        if (!data || data.length === 0) {
            showNoData(container, 'ไม่พบข้อมูลสำหรับแสดงกราฟ');
            return;
        }

        Highcharts.chart(container, {
            chart: {
                type: 'pie',
                backgroundColor: '#ffffff'
            },

            title: {
                text: title,
                style: {
                    fontFamily: 'Kanit',
                    fontSize: '18px',
                    fontWeight: 'bold'
                }
            },

            subtitle: {
                text: subtitle,
                style: {
                    fontFamily: 'Kanit',
                    fontSize: '13px'
                }
            },

            tooltip: {
                useHTML: true,
                style: {
                    fontFamily: 'Kanit',
                    fontSize: '13px'
                },
                pointFormat:
                    '<b>{point.y:,.0f}</b> คน<br>' +
                    'คิดเป็น <b>{point.percentage:.1f}%</b>'
            },

            plotOptions: {
                pie: {
                    innerSize: '45%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format:
                            '<b>{point.name}</b><br>' +
                            '{point.y:,.0f} คน ({point.percentage:.1f}%)',
                        style: {
                            fontFamily: 'Kanit',
                            fontSize: '12px',
                            fontWeight: 'normal'
                        }
                    },
                    showInLegend: true
                }
            },

            legend: {
                itemStyle: {
                    fontFamily: 'Kanit',
                    fontSize: '12px'
                }
            },

            series: [{
                name: 'จำนวน',
                colorByPoint: true,
                data: data
            }],

            credits: {
                enabled: false
            }
        });
    }

    function fillSelect(selectId, chartData) {
        var select = $('#' + selectId);
        select.empty();

        $.each(chartData, function (key, item) {
            select.append(
                $('<option></option>')
                    .attr('value', key)
                    .text(item.title)
            );
        });
    }

    function updateQuestionChart(selectId, container, subtitleId, chartData, prefixTitle) {
        var key = $('#' + selectId).val();
        var item = chartData[key];

        if (!item) {
            showNoData(container, 'ไม่พบข้อมูลคำถามนี้');
            return;
        }

        $('#' + subtitleId).text(item.subtitle);

        renderPieChart(
            container,
            prefixTitle + ': ' + item.title,
            item.subtitle,
            item.data
        );
    }

    renderPieChart(
        'smokeTypePieChart',
        'ประเภทการสูบจากข้อมูลล่าสุด',
        'แบ่งกลุ่มจากข้อมูลล่าสุดของแต่ละ user และนับเฉพาะคนที่ตอบครบ',
        smokeTypeData
    );

    fillSelect('aOnlyQuestionSelect', aOnlyQuestionCharts);
    fillSelect('eOnlyQuestionSelect', eOnlyQuestionCharts);
    fillSelect('bothQuestionSelect', bothQuestionCharts);

    updateQuestionChart(
        'aOnlyQuestionSelect',
        'aOnlyQuestionPieChart',
        'aOnlyQuestionSubtitle',
        aOnlyQuestionCharts,
        'บุหรี่มวนอย่างเดียว'
    );

    updateQuestionChart(
        'eOnlyQuestionSelect',
        'eOnlyQuestionPieChart',
        'eOnlyQuestionSubtitle',
        eOnlyQuestionCharts,
        'บุหรี่ไฟฟ้าอย่างเดียว'
    );

    updateQuestionChart(
        'bothQuestionSelect',
        'bothQuestionPieChart',
        'bothQuestionSubtitle',
        bothQuestionCharts,
        'สูบทั้งสอง'
    );

    $('#aOnlyQuestionSelect').on('change', function () {
        updateQuestionChart(
            'aOnlyQuestionSelect',
            'aOnlyQuestionPieChart',
            'aOnlyQuestionSubtitle',
            aOnlyQuestionCharts,
            'บุหรี่มวนอย่างเดียว'
        );
    });

    $('#eOnlyQuestionSelect').on('change', function () {
        updateQuestionChart(
            'eOnlyQuestionSelect',
            'eOnlyQuestionPieChart',
            'eOnlyQuestionSubtitle',
            eOnlyQuestionCharts,
            'บุหรี่ไฟฟ้าอย่างเดียว'
        );
    });

    $('#bothQuestionSelect').on('change', function () {
        updateQuestionChart(
            'bothQuestionSelect',
            'bothQuestionPieChart',
            'bothQuestionSubtitle',
            bothQuestionCharts,
            'สูบทั้งสอง'
        );
    });

});
</script>

</body>
</html>
