<?php
function getApplicantTypeOptions()
{
    return array(
        'university' => 'นักศึกษาระดับมหาลัย',
        'secondary' => 'ระดับมัธยมศึกษา',
        'primary' => 'ระดับประถมศึกษา',
        'general' => 'บุคคลทั่วไป'
    );
}

function getApplicantLevelOptions($type = '')
{
    if ($type == 'university') {
        return array(
            'bachelor' => 'ระดับปริญญาตรี',
            'master' => 'ระดับปริญญาโท',
            'doctorate' => 'ระดับปริญญาเอก'
        );
    }

    if ($type == 'secondary') {
        return array(
            '1' => 'มัธยมศึกษาปีที่ 1',
            '2' => 'มัธยมศึกษาปีที่ 2',
            '3' => 'มัธยมศึกษาปีที่ 3',
            '4' => 'มัธยมศึกษาปีที่ 4',
            '5' => 'มัธยมศึกษาปีที่ 5',
            '6' => 'มัธยมศึกษาปีที่ 6'
        );
    }

    if ($type == 'primary') {
        return array(
            '1' => 'ประถมศึกษาปีที่ 1',
            '2' => 'ประถมศึกษาปีที่ 2',
            '3' => 'ประถมศึกษาปีที่ 3',
            '4' => 'ประถมศึกษาปีที่ 4',
            '5' => 'ประถมศึกษาปีที่ 5',
            '6' => 'ประถมศึกษาปีที่ 6'
        );
    }

    return array();
}

function normalizeApplicantType($type)
{
    $type = trim((string)$type);
    $types = getApplicantTypeOptions();

    return isset($types[$type]) ? $type : '';
}

function normalizeApplicantLevel($type, $level)
{
    $level = trim((string)$level);
    $levels = getApplicantLevelOptions($type);

    if ($type == 'general') {
        return '';
    }

    return isset($levels[$level]) ? $level : '';
}

function getApplicantTypeLabel($type)
{
    $types = getApplicantTypeOptions();

    return isset($types[$type]) ? $types[$type] : '';
}

function getApplicantLevelLabel($type, $level)
{
    $levels = getApplicantLevelOptions($type);

    return isset($levels[$level]) ? $levels[$level] : '';
}

function getApplicantEducationLabel($type, $level = '')
{
    if ($type == '') {
        return '';
    }

    $typeLabel = getApplicantTypeLabel($type);
    $levelLabel = getApplicantLevelLabel($type, $level);

    if ($levelLabel == '') {
        return $typeLabel;
    }

    return $typeLabel . ' - ' . $levelLabel;
}

function getApplicantAdminScopeCondition($adminUniversityId, $column = 'university_id')
{
    $adminUniversityId = intval($adminUniversityId);
    $column = trim((string)$column);

    if ($adminUniversityId <= 0 || $column == '') {
        return '';
    }

    return ' AND (' . $column . ' = ' . $adminUniversityId . ' OR ' . $column . ' = 0 OR ' . $column . ' IS NULL) ';
}
?>
