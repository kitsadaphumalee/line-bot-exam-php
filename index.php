<?php
$strAccessToken = "9sPJbcBQa+A6cL+sjFHhUVy+W3OywjzVipx473VAkZTB1vcNtMX/N293MRn/4g7P3ebB7DvgwMLgUuHhyrHDEMntzDN47mOwCCmdkRZXl7ujtAsFSHtZveEL1MC4vZswo1edLDhJPPM+p/B3nuQQgwdB04t89/1O/w1cDnyilFU=";

$hostname_condb="localhost";
$username_condb="kitsadac";
$password_conndb="55zc56sCHd";
$db_name="kitsadac_checkid";

$conndb=mysqli_connect($hostname_condb,$username_condb,$password_conndb,$db_name);

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$strexp = isset($_REQUEST['strexp']) ? $_REQUEST['strexp'] : '';
$strexp = $arrJson['events'][0]['message']['text'];
      //$strexp = "#1229900480178,FT-2536 fds5g45df4g5";
$strchk = str_split($strexp);
    /*$show = substr($strexp,0,1);
    $space = iconv("tis-620", "utf-8", substr($strexp,1,1) );
    $idcard = substr($strexp,1,13);

    $detail = substr($strexp,15);
    $arrstr  = explode( "," , $strexp );
    if(substr($strexp,14,1)==","){
      print_r($arrstr);
      echo substr($strexp,14,1);
    echo $idcard." - ".substr($strexp,14,1)."-".$detail;
    }*/
  //echo $strchk[0];
$arrayloop = array();

if($strchk[0]=="*"){
  $arrstr  = explode( "*" , $strexp );
  for($k=1 ; $k < count( $arrstr ) ; $k++ ){
      $strchk = "*".$arrstr[$k];
      $idcard = substr($strchk,1);
      $chkid = substr($idcard,0,13);
            if(is_numeric($chkid)){
              $countid = strlen($chkid);
              if($countid == "13"){
                $idcard = $chkid;
              }
            }
            if(is_numeric($idcard)){
              $countid = strlen($idcard);
              if($countid == "13"){

                        //$input = 'http://vpn.idms.pw:9977/polis/imagebyte?id='.$idcard;
						//$r = 'http://vpn.idms.pw/id_pdc/index_image.php?uid='.$idcard;						
                        //$dirimg = 'pic/';            // directory in which the image will be saved
                        //$localfile = $dirimg. $idcard.'.jpg';         // set image name the same as the file name of the source

                      //echo $localfile;
                        // create the file with the image on the server

                      //$r = file_put_contents($localfile, getContentUrl($input));
                       $r = file_get_contents('http://vpn.idms.pw/id_pdc/index_image.php?uid='.$idcard);
                        //echo $content;
					   $rr = file_get_contents('http://www.kitsada.com/index_image.php?uid='.$idcard);
						
                        $status = "1";
                        $txt = "";
                      if($r == '1'){		   
                        $status = "1";
                      }else{
                        $status = "2";
                      }
                      $arrPostData = array();
                      $arrPostData["idcard"] = $idcard;
                      $arrPostData["detail"] = $txt;
                      $arrPostData["status"] = $status;
                      //print_r($arrPostData);
                      array_push($arrayloop,$arrPostData);
              }
            }
  }
}
 
$arrPostData = array();
$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
$num=0;
    foreach($arrayloop as $loop){
        $idcard = "";
        $status = "";
        $detail = "";
      foreach ($loop as $key => $value) {
        if($key=="idcard"){ $idcard = $value; }
        if($key=="status"){ $status = $value; }
        if($key=="detail"){ $detail = $value; }   
      }
      if($status=="1"){
                       $arrPostData['messages'][$num]['type'] = "image";
                       $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $num++;
      }
      if($status=="3"){
                       $arrPostData['messages'][$num]['type'] = "image";
                       $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $num++;
      }
      if($detail != ""){

                       $arrPostData['messages'][$num]['type'] = "text";
                       $arrPostData['messages'][$num]['text'] = $detail;
                       $num++;
      }
    }
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close ($ch);
function getContentUrl($url) {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses

            // gets the file content, trigger error if false

            $file = curl_exec($ch);

            if($file === false) trigger_error(curl_error($ch));
            curl_close ($ch);
            return $file;

          }  

?>
