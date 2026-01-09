<?php
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'invalid']);
    exit;
}

$id              = $_POST['id'] ?? '';
$instructor_id   = $_POST['instructor_id'] ?? '';
$course          = $_POST['coursecode_title'] ?? '';
$time            = $_POST['time'] ?? '';
$day             = $_POST['day'] ?? '';
$room            = $_POST['room'] ?? '';
$modality        = $_POST['modality_type'] ?? '';
$year_section    = $_POST['year_section'] ?? '';
$semester        = $_POST['semester'] ?? '';
$sy              = $_POST['sy'] ?? '';
$unit            = $_POST['unit'] ?? '';

/* ================================
   UPDATE (if existing)
================================ */
if (!empty($id)) {
    $stmt = $pdo->prepare("
        UPDATE class SET
            instructor_id   = :instructor_id,
            coursecode_title= :course,
            time            = :time,
            day             = :day,
            room            = :room,
            modality_type   = :modality,
            year_section    = :year_section,
            semester        = :semester,
            sy              = :sy,
            unit            = :unit
        WHERE id = :id
    ");

    $stmt->execute([
        ':id'=>$id,
        ':instructor_id'=>$instructor_id,
        ':course'=>$course,
        ':time'=>$time,
        ':day'=>$day,
        ':room'=>$room,
        ':modality'=>$modality,
        ':year_section'=>$year_section,
        ':semester'=>$semester,
        ':sy'=>$sy,
        ':unit'=>$unit
    ]);

    echo json_encode(['status'=>'updated','id'=>$id]);
    exit;
}

/* ================================
   INSERT (new row)
================================ */
$stmt = $pdo->prepare("
    INSERT INTO class (
        instructor_id,
        coursecode_title,
        time,
        day,
        room,
        modality_type,
        year_section,
        semester,
        sy,
        unit
    ) VALUES (
        :instructor_id,
        :course,
        :time,
        :day,
        :room,
        :modality,
        :year_section,
        :semester,
        :sy,
        :unit
    )
");

$stmt->execute([
    ':instructor_id'=>$instructor_id,
    ':course'=>$course,
    ':time'=>$time,
    ':day'=>$day,
    ':room'=>$room,
    ':modality'=>$modality,
    ':year_section'=>$year_section,
    ':semester'=>$semester,
    ':sy'=>$sy,
    ':unit'=>$unit
]);

echo json_encode([
    'status'=>'inserted',
    'id'=>$pdo->lastInsertId()
]);
