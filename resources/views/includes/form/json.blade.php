<?php
    echo formatNice($json);

    function formatNice(array $data)
    {
        $rtn='<dl class="dl-horizontal">';
        foreach ($data as $k => $v) {
            $rtn .= '<dt>' . $k . '</dt>';
            if (is_array($v)) {
                $rtn .= '<dd></dd><dd>' . formatNice($v) .'</dd>';
            }
            else {
                $rtn .= '<dd>' . $v .'</dd>';
            }
        }
        $rtn .= '</dl>';
        return $rtn;
    }

?>

