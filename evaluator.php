<?php
function count_points($result_a, $result_b, $tip_a, $tip_b) : int
{
    if($result_a == $tip_a && $result_b == $tip_b)
    {
        return 10;
    }
    else if($result_a - $result_b == $tip_a - $tip_b)
    {
        return 7;
    }
    else if(($result_a > $result_b && $tip_a > $tip_b) || ($result_a < $result_b && $tip_a < $tip_b))
    {
        return 2;
    }
    return 0;
}

class Result 
{
    public string $name = "";
    public string $entry_code = "";
    public int $points = 0;

    public function __construct($name, $entry_code, $points)
    {
        $this->name = $name;
        $this->points = $points;
        $this->entry_code = $entry_code;
    }
}
?>