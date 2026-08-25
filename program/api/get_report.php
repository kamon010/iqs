<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$year = $_POST["year"];
$month = $_POST["month"];
$day = $_POST["day"];

if($user_id!=''){
    $sql = 'SELECT SUM(sodium) AS sum FROM sodium_record WHERE user_id ='.$user_id.' AND date ="'.$year.'-'.$month.'-'.$day.''.'"';
    $data = $query->queryDB_to_row($sql);
    $sum = intval($data['sum']);
    $str = getStr($sum);

    $sql = 'SELECT * FROM sodium_record WHERE user_id ='.$user_id.' AND date="'.$year.'-'.$month.'-'.$day.''.'"';
    $record = $query->queryDB_to_array($sql);
    $str .= '<p>ตารางรายการอาหารของท่าน</p>';
    $str .= '<table class="table">';
    $str .= '<tr>'; 
    $str .= '<th class="text-center">อาหาร</th>';
    $str .= '<th class="text-center">ปริมาณโซเดียม</th>';  
    $str .= '</tr>'; 
    foreach($record as $key => $val){
        $str .= '<tr>'; 
        $str .= '<td class="text-center">'.$val['food'].'</td>';
        $str .= '<td class="text-center">'.$val['sodium'].'</td>';  
        $str .= '</tr>'; 
    }
    $str .= '</table>';
}
echo '<script>';
if($data){
    echo "$('#html_model').html('".$str."');";
    echo "$('#model').appendTo('body').modal('show');";
}
echo '</script>';

function getStr($n){
    if($n<=2000){
        return '<div class="alert alert-success"><span class="badge badge-success" style="font-size:20px"> <i class="far fa-smile-beam"></i></span> <b>คุณเยี่ยมมาก</b> อาหารที่คุณรับประทานวันนี้ มีปริมาณโซเดียม น้อยกว่า 2,000 มิลลิกรัม <br>ขอให้รับประทานแบบนี้เป็นประจำ คุณจะสุขภาพดี ห่างไกลโรคหัวใจและหลอดเลือด โรคความดันโลหิตสูง แน่นอน! <br><b>ลดโซเดียมเรื่องง่าย คุณทำได้</b> <br>เพียงลดอาหารที่มีปริมาณโซเดียมสูง ลดปรุงเพิ่ม ลดอาหารแปรรูป ลดขนม อ่านฉลากโภชนาการทุกครั้ง และดื่มน้ำเปล่าอย่างน้อย 1.5-2 ลิตรต่อวัน</div>';
    }
    else if($n<=2500){
        return '<div class="alert alert-warning"><span class="badge badge-warning" style="font-size:20px; color:#fff;"> <i class="far fa-smile"></i></span> <b>คุณทำได้ดี</b> แต่อาหารที่คุณรับประทานวันนี้ มีปริมาณโซเดียม 2,001-2,500 มิลลิกรัม (สูงกว่าปริมาณที่แนะนำ คือ 2000 มิลลิกรัมต่อวัน) <br><b>ลดโซเดียมเรื่องง่าย คุณทำได้</b> <br>เพียงลดอาหารที่มีปริมาณโซเดียมสูง ลดปรุงเพิ่ม ลดอาหารแปรรูป ลดขนม อ่านฉลากโภชนาการทุกครั้ง และดื่มน้ำเปล่าอย่างน้อย 1.5-2 ลิตรต่อวัน</div>';
    }
    else if($n<=3000){
        return '<div class="alert alert-danger"><span class="badge badge-danger" style="font-size:20px"> <i class="far fa-meh"></i></span> <b>คุณควรลดโซเดียมแล้ว</b> เพราะอาหารที่คุณรับประทานวันนี้ มีปริมาณโซเดียม 2,501-3,000 มิลลิกรัม (สูงกว่าปริมาณที่แนะนำ คือ 2000 มิลลิกรัมต่อวัน) <br><b>ลดโซเดียมเรื่องง่าย คุณทำได้</b> <br> เพียงลดอาหารที่มีปริมาณโซเดียมสูง ลดปรุงเพิ่ม ลดอาหารแปรรูป ลดขนม อ่านฉลากโภชนาการทุกครั้ง และดื่มน้ำเปล่าอย่างน้อย 1.5-2 ลิตรต่อวัน </div>';
    }
    else if($n>3000){
        return '<div class="alert alert-danger"> <span class="badge badge-danger" style="font-size:20px"> <i class="fas fa-exclamation-triangle"></i></span> <b>คุณควรจำกัดอาหารที่มีโซเดียมแล้ว</b> เพราะอาหารที่คุณรับประทานวันนี้ มีปริมาณโซเดียม 3,001-4,000 มิลลิกรัม เป็นปริมาณโซเดียมที่สูงเกือบสองเท่าของปริมาณที่แนะนำ (ปริมาณโซเดียมที่แนะนำต่อวัน คือ 2000 มิลลิกรัม)หากรับประทานแบบนี้เป็นประจำ จะทำให้คุณเสี่ยงต่อโรคหัวใจและหลอดเลือด โรคความดันโลหิตสูงได้มากขึ้น <br> <b>ลดโซเดียมเรื่องง่าย คุณทำได้</b> <br> เพียงลดอาหารที่มีปริมาณโซเดียมสูง ลดปรุงเพิ่ม ลดอาหารแปรรูป ลดขนม  อ่านฉลากโภชนาการทุกครั้ง และดื่มน้ำเปล่าอย่างน้อย 1.5-2 ลิตรต่อวัน </div>';
    }
    else{
        return '';
    }
}
?>