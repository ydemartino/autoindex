<?php

class Lightbenc {
    public static function bdecode($s, &$pos=0) {
        if($pos>=strlen($s)) return null;
        switch($s[$pos]){
        case 'd':
            $pos++;
            $retval=array();
            while ($s[$pos]!='e'){
                $key=Lightbenc::bdecode($s, $pos);
                $val=Lightbenc::bdecode($s, $pos);
                if ($key===null || $val===null)
                    break;
                $retval[$key]=$val;
            }
            $retval['isDct']=true;
            ++$pos;
            return $retval;
        case 'l':
            $pos++;
            $retval=array();
            while ($s[$pos]!='e'){
                $val=Lightbenc::bdecode($s, $pos);
                if ($val===null)
                    break;
                $retval[]=$val;
            }
            $pos++;
            return $retval;

        case 'i':
            ++$pos;
            $digits=strpos($s, 'e', $pos)-$pos;
            $val=round((float)substr($s, $pos, $digits));
            $pos+=$digits+1;
            return $val;

    //	case "0": case "1": case "2": case "3": case "4":
    //	case "5": case "6": case "7": case "8": case "9":
        default:
            $digits=strpos($s, ':', $pos)-$pos;
            if ($digits<0 || $digits >20)
                return null;
            $len=(int)substr($s, $pos, $digits);
            $pos+=$digits+1;
            $str=substr($s, $pos, $len);
            $pos+=$len;
            return (string)$str;
        }
        return null;
    }

    public static function bdecode_file($filename){
        $f=file_get_contents($filename, FILE_BINARY);
        return Lightbenc::bdecode($f);
    }

    public static function bdecode_getinfo_file($filename){
        return self::bdecode_getinfo(file_get_contents($filename));
    }

    public static function bdecode_getinfo($data){
        $t = Lightbenc::bdecode($data);

        if(is_array($t['info']['files'])){ //multifile
            $t['info']['size'] = 0;
            $t['info']['filecount'] = 0;

            foreach($t['info']['files'] as $file){
                ++$t['info']['filecount'];
                $t['info']['size']+=$file['length'];
            }
        }else{
            $t['info']['size'] = $t['info']['length'];
            $t['info']['filecount'] = 1;
            $t['info']['files'][0]['path'] = $t['info']['name'];
            $t['info']['files'][0]['length'] = $t['info']['length'];
        }
        return $t;
    }
}
