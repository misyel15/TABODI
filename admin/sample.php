<?php include('db_connect.php');?>
<?php 
    $i = 1;
    $course = $conn->query("SELECT * FROM rooms");
    while($records=$course->fetch_assoc()){
        $timearr = array($records['time']);
        print_r($timearr[0]);
        $rows=array();

        foreach($records as $key=>$val)
        {
            $rows[$key][]=$val;
        }

        foreach($rows as $row)
        {
        echo '<tr>';
        foreach($row as $cell)
        {
            echo "<th>$cell</th>";
        }
        echo '</tr>';
        }
    }
    
?>