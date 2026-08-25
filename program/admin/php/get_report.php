<?
include_once('../indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_POST["user_id"];

$data_w1 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=1');			
$data_w2 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=2');			
$data_w3 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=3');			
$data_w4 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=4');	
$data_w5 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=5');	
$data_w6 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=6');	
$data_w7 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=7');	
$data_w8 = $query->queryDB_to_array('SELECT * FROM target WHERE user_id='.$user_id.' AND week_id=8');	

echo '    
<div class="row">
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">สัปดาห์ที่ 1</a></li>
                <li><a href="#tab_2" data-toggle="tab">สัปดาห์ที่ 2</a></li>
                <li><a href="#tab_3" data-toggle="tab">สัปดาห์ที่ 3</a></li>
                <li><a href="#tab_4" data-toggle="tab">สัปดาห์ที่ 4</a></li>
                <li><a href="#tab_5" data-toggle="tab">สัปดาห์ที่ 5</a></li>
                <li><a href="#tab_6" data-toggle="tab">สัปดาห์ที่ 6</a></li>
                <li><a href="#tab_7" data-toggle="tab">สัปดาห์ที่ 7</a></li>
                <li><a href="#tab_8" data-toggle="tab">สัปดาห์ที่ 8</a></li>
            
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w1 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_2">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w2 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w3 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_4">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w4 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_5">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w5 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_6">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w6 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_7">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w7 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab_8">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">หัวข้อเป้าหมาย</th>
                                <th class="text-center">จันทร์</th>
                                <th class="text-center">อังคาร</th>
                                <th class="text-center">พุธ</th>
                                <th class="text-center">พฤหัส</th>
                                <th class="text-center">ศุกร์</th>
                                <th class="text-center">เสาร์</th>
                                <th class="text-center">อาทิตย์</th>
                            </tr>
                        </thead>
                        <tbody>
                        ';
                        foreach($data_w8 as $key => $val){
							$str = $query->queryDB_to_string('SELECT title FROM goal_'.$val['goal_type'].' WHERE id='.$val['goal_id'].'');	
                            echo '
                                <tr>
                                    <td class="text-left">'.$str.'</td>
                                    <td class="text-center">'.getStat($val['day1']).'</td>
                                    <td class="text-center">'.getStat($val['day2']).'</td>
                                    <td class="text-center">'.getStat($val['day3']).'</td>
                                    <td class="text-center">'.getStat($val['day4']).'</td>
                                    <td class="text-center">'.getStat($val['day5']).'</td>
                                    <td class="text-center">'.getStat($val['day6']).'</td>
                                    <td class="text-center">'.getStat($val['day7']).'</td>
                                </tr>
                            ';
                        }
                        echo '
                        </tbody>
                    </table>
                </div>
				
				
				
				
            </div>
        </div>
    </div>
</div>
';

function getStat($stat){
	if($stat==1){
		return '<p class="text-green">ทำได้</p>';
	}
	else{
		return '<p class="text-red">ทำไม่ได้</p>';
	}
}
?>